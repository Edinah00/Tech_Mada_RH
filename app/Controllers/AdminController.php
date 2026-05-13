<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Models\DepartementModel;

/**
 * AdminController — Back-office TechMada RH
 *
 * Routes couvertes :
 *   GET  /admin/dashboard
 *   GET  /admin/stats
 *   GET  /admin/historique
 *   GET  /admin/employe
 *   POST /admin/employe/store
 *   POST /admin/employe/update/:id
 *   POST /admin/employe/desactiver/:id
 *   POST /admin/employe/solde/:id
 *   GET  /admin/departement
 *   POST /admin/departement/store
 *   POST /admin/departement/update/:id
 *   POST /admin/departement/delete/:id
 *   GET  /admin/type-conge
 *   POST /admin/type-conge/store
 *   POST /admin/type-conge/update/:id
 *   POST /admin/type-conge/delete/:id
 */
class AdminController extends BaseController
{
    // ─────────────────────────────────────────────────────────
    // GUARD — rôle admin ou rh
    // ─────────────────────────────────────────────────────────
    private function requireAdmin(): void
    {
        if (!$this->session->get('isLoggedIn')) {
            redirect()->to('/login')->send();
            exit;
        }
        if (!in_array($this->session->get('userRole'), ['admin', 'rh'])) {
            redirect()->to('/employe/dashboard')->send();
            exit;
        }
    }

    private function requireSuperAdmin(): void
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('userRole') !== 'admin') {
            redirect()->to('/rh')->send();
            exit;
        }
    }

    // ─────────────────────────────────────────────────────────
    // DASHBOARD / INDEX
    // ─────────────────────────────────────────────────────────
    public function index()
    {
        $this->requireAdmin();
        return redirect()->to('/admin/stats');
    }

    // ─────────────────────────────────────────────────────────
    // STATS — tableau de bord admin
    // ─────────────────────────────────────────────────────────
    public function stats()
    {
        $this->requireAdmin();

        $congeModel = new CongeModel();
        $db         = \Config\Database::connect();
        $annee      = (int) date('Y');

        // Métriques globales
        $nbEmployes   = $db->table('employes')->where('role', 'employe')->where('actif', 1)->countAllResults();
        $nbRh         = $db->table('employes')->where('role', 'rh')->where('actif', 1)->countAllResults();
        $nbDepts      = $db->table('departements')->countAllResults();
        $stats        = $congeModel->statsAdmin();

        // Demandes récentes (5 dernières)
        $recentes = $db->query("
            SELECT c.*, tc.libelle AS type_libelle, e.nom, e.prenom, d.nom AS dept_nom
            FROM conges c
            JOIN types_conge tc ON tc.id = c.type_conge_id
            JOIN employes e ON e.id = c.employe_id
            LEFT JOIN departements d ON d.id = e.departement_id
            ORDER BY c.created_at DESC
            LIMIT 5
        ")->getResultArray();

        // Absents aujourd'hui
        $absentsAuj = $db->query("
            SELECT e.nom, e.prenom, tc.libelle AS type_libelle, c.date_fin
            FROM conges c
            JOIN employes e ON e.id = c.employe_id
            JOIN types_conge tc ON tc.id = c.type_conge_id
              WHERE c.statut = 'approuve'
              AND c.date_debut <= date('now')
              AND c.date_fin   >= date('now')
            ORDER BY c.date_fin ASC
        ")->getResultArray();

        // Employés avec solde critique (≤ 2 jours restants sur congé annuel)
        $soldesCritiques = $db->query("
            SELECT e.nom, e.prenom, d.nom AS dept_nom,
                   (s.jours_attribues - s.jours_pris) AS restant
            FROM soldes s
            JOIN employes e ON e.id = s.employe_id
            LEFT JOIN departements d ON d.id = e.departement_id
            JOIN types_conge tc ON tc.id = s.type_conge_id
            WHERE tc.libelle = 'Congé annuel'
              AND s.annee = ?
              AND e.actif = 1
              AND e.role = 'employe'
              AND (s.jours_attribues - s.jours_pris) <= 2
            ORDER BY restant ASC
        ", [$annee])->getResultArray();

        return view('admin/dashboard', [
            'title'           => 'Tableau de bord',
            'nbEmployes'      => $nbEmployes,
            'nbRh'            => $nbRh,
            'nbDepts'         => $nbDepts,
            'stats'           => $stats,
            'recentes'        => $recentes,
            'absentsAuj'      => $absentsAuj,
            'soldesCritiques' => $soldesCritiques,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // HISTORIQUE — toutes les demandes
    // ─────────────────────────────────────────────────────────
    public function historique()
    {
        $this->requireAdmin();

        $congeModel = new CongeModel();
        $deptModel  = new DepartementModel();

        $statut = $this->request->getGet('statut');
        $deptId = $this->request->getGet('dept');

        $conges = $congeModel->getAllWithDetails();

        if ($statut) {
            $conges = array_values(array_filter($conges, fn($c) => $c['statut'] === $statut));
        }
        if ($deptId) {
            $conges = array_values(array_filter($conges, fn($c) => $c['departement_id'] == $deptId));
        }

        // Compteurs par statut
        $all    = $congeModel->getAllWithDetails();
        $counts = ['total' => count($all), 'en_attente' => 0, 'approuve' => 0, 'refuse' => 0, 'annule' => 0];
        foreach ($all as $c) {
            if (isset($counts[$c['statut']])) $counts[$c['statut']]++;
        }

        return view('admin/historique', [
            'title'  => 'Historique des demandes',
            'conges' => $conges,
            'counts' => $counts,
            'statut' => $statut,
            'deptId' => $deptId,
            'depts'  => $deptModel->findAll(),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // CRUD EMPLOYÉS
    // ─────────────────────────────────────────────────────────
    public function employes()
    {
        $this->requireAdmin();

        $model     = new EmployeModel();
        $deptModel = new DepartementModel();

        $employes = $model->getAllWithDepartement();

        return view('admin/employe', [
            'title'    => 'Gestion des employés',
            'employes' => $employes,
            'depts'    => $deptModel->findAll(),
        ]);
    }

    public function storeEmploye()
    {
        $this->requireSuperAdmin();

        $model = new EmployeModel();

        $email = $this->request->getPost('email');
        if ($model->findByEmail($email)) {
            return redirect()->to('/admin/employe')
                ->with('error', 'Un employé avec cet email existe déjà.');
        }

        $id = $model->insert([
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $email,
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => $this->request->getPost('role') ?? 'employe',
            'departement_id' => $this->request->getPost('departement_id'),
            'date_embauche'  => $this->request->getPost('date_embauche') ?: date('Y-m-d'),
            'actif'          => 1,
        ]);

        // Initialiser les soldes pour l'année en cours
        if ($id) {
            $soldeModel = new SoldeModel();
            $soldeModel->initialiserPourEmploye((int)$model->insertID(), (int)date('Y'));
        }

        return redirect()->to('/admin/employe')
            ->with('success', 'Employé créé avec succès. Soldes initialisés.');
    }

    public function updateEmploye(int $id)
    {
        $this->requireSuperAdmin();

        $model = new EmployeModel();
        $emp   = $model->find($id);
        if (!$emp) {
            return redirect()->to('/admin/employe')->with('error', 'Employé introuvable.');
        }

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'role'           => $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id'),
            'date_embauche'  => $this->request->getPost('date_embauche'),
        ];

        $newPwd = $this->request->getPost('password');
        if ($newPwd) {
            $data['password'] = password_hash($newPwd, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);

        return redirect()->to('/admin/employe')
            ->with('success', 'Employé mis à jour.');
    }

    public function desactiverEmploye(int $id)
    {
        $this->requireSuperAdmin();

        $model = new EmployeModel();
        $emp   = $model->find($id);
        if (!$emp) {
            return redirect()->to('/admin/employe')->with('error', 'Employé introuvable.');
        }

        // Ne pas désactiver le compte admin courant
        if ($id == $this->session->get('userId')) {
            return redirect()->to('/admin/employe')
                ->with('error', 'Impossible de désactiver votre propre compte.');
        }

        $nouvelEtat = $emp['actif'] ? 0 : 1;
        $model->update($id, ['actif' => $nouvelEtat]);

        $msg = $nouvelEtat ? 'Employé réactivé.' : 'Employé désactivé.';
        return redirect()->to('/admin/employe')->with('success', $msg);
    }

    public function ajusterSolde(int $id)
    {
        $this->requireAdmin();

        $soldeModel = new SoldeModel();
        $typeId     = (int) $this->request->getPost('type_conge_id');
        $annee      = (int) ($this->request->getPost('annee') ?: date('Y'));
        $nouveau    = (int) $this->request->getPost('jours_attribues');

        $solde = $soldeModel->getSolde($id, $typeId, $annee);
        if ($solde) {
            $soldeModel->update($solde['id'], ['jours_attribues' => $nouveau]);
        } else {
            $soldeModel->insert([
                'employe_id'      => $id,
                'type_conge_id'   => $typeId,
                'annee'           => $annee,
                'jours_attribues' => $nouveau,
                'jours_pris'      => 0,
            ]);
        }

        return redirect()->to('/admin/employe')
            ->with('success', 'Solde ajusté avec succès.');
    }

    // ─────────────────────────────────────────────────────────
    // CRUD DÉPARTEMENTS
    // ─────────────────────────────────────────────────────────
    public function departements()
    {
        $this->requireAdmin();

        $model = new DepartementModel();
        $db    = \Config\Database::connect();

        // Récupérer les départements avec le nombre d'employés
        $depts = $db->query("
            SELECT d.*, COUNT(e.id) AS nb_employes
            FROM departements d
            LEFT JOIN employes e ON e.departement_id = d.id AND e.actif = 1
            GROUP BY d.id
            ORDER BY d.nom
        ")->getResultArray();

        return view('admin/departement', [
            'title' => 'Gestion des départements',
            'depts' => $depts,
        ]);
    }

    public function storeDepartement()
    {
        $this->requireSuperAdmin();

        $model = new DepartementModel();
        $model->insert([
            'nom'         => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/departement')
            ->with('success', 'Département créé.');
    }

    public function updateDepartement(int $id)
    {
        $this->requireSuperAdmin();

        $model = new DepartementModel();
        $model->update($id, [
            'nom'         => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/departement')
            ->with('success', 'Département mis à jour.');
    }

    public function deleteDepartement(int $id)
    {
        $this->requireSuperAdmin();

        $db = \Config\Database::connect();

        // Vérifier si des employés sont rattachés
        $nbEmp = $db->table('employes')->where('departement_id', $id)->countAllResults();
        if ($nbEmp > 0) {
            return redirect()->to('/admin/departement')
                ->with('error', "Impossible de supprimer : $nbEmp employé(s) rattaché(s) à ce département.");
        }

        (new DepartementModel())->delete($id);

        return redirect()->to('/admin/departement')
            ->with('success', 'Département supprimé.');
    }

    // ─────────────────────────────────────────────────────────
    // CRUD TYPES DE CONGÉ
    // ─────────────────────────────────────────────────────────
    public function typesConge()
    {
        $this->requireAdmin();

        $model = new TypeCongeModel();
        return view('admin/type_conge', [
            'title' => 'Types de congé',
            'types' => $model->findAll(),
        ]);
    }

    public function storeTypeConge()
    {
        $this->requireSuperAdmin();

        $model = new TypeCongeModel();
        $model->insert([
            'libelle'       => $this->request->getPost('libelle'),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible'    => (int) ($this->request->getPost('deductible') ?? 1),
        ]);

        return redirect()->to('/admin/type-conge')
            ->with('success', 'Type de congé créé.');
    }

    public function updateTypeConge(int $id)
    {
        $this->requireSuperAdmin();

        $model = new TypeCongeModel();
        $model->update($id, [
            'libelle'       => $this->request->getPost('libelle'),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible'    => (int) ($this->request->getPost('deductible') ?? 1),
        ]);

        return redirect()->to('/admin/type-conge')
            ->with('success', 'Type de congé mis à jour.');
    }

    public function deleteTypeConge(int $id)
    {
        $this->requireSuperAdmin();

        $db = \Config\Database::connect();

        // Vérifier si des demandes utilisent ce type
        $nbConges = $db->table('conges')->where('type_conge_id', $id)->countAllResults();
        if ($nbConges > 0) {
            return redirect()->to('/admin/type-conge')
                ->with('error', "Impossible de supprimer : $nbConges demande(s) utilisent ce type.");
        }

        // Supprimer aussi les soldes associés
        $db->table('soldes')->where('type_conge_id', $id)->delete();
        (new TypeCongeModel())->delete($id);

        return redirect()->to('/admin/type-conge')
            ->with('success', 'Type de congé supprimé.');
    }
}
