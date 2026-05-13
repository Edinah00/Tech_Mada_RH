<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegimeModel;
use App\Models\ActiviteModel;
use App\Models\AlimentModel;

/**
 * Back Office : administration complète.
 */
class AdminController extends BaseController
{
    /** Middleware admin. */
    protected function requireAdmin()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('userRole') !== 'admin') {
            redirect()->to('/login')->send();
            exit;
        }
    }

    // ─────────────────────────────────────────────────────────
    // DASHBOARD ADMIN
    // ─────────────────────────────────────────────────────────
    public function index()
    {
        $this->requireAdmin();
        return redirect()->to('/admin/stats');
    }

    public function stats()
    {
        $this->requireAdmin();

        $userModel   = new UserModel();
        $db          = \Config\Database::connect();

        $statsObjectif = $userModel->statsParObjectif();
        $revenuTotal   = $userModel->revenuTotal();

        $nbUsers       = $db->table('users')->where('role', 'user')->countAllResults();
        $nbGold        = $db->table('users')->where('is_gold', 1)->countAllResults();
        $nbProgrammes  = $db->table('user_programmes')->countAllResults();

        // Revenus par mois (6 derniers mois)
        $revenusMensuels = $db->query("
            SELECT DATE_FORMAT(date_achat, '%Y-%m') AS mois,
                   SUM(prix_total_paye) AS total
            FROM user_programmes
            WHERE date_achat >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mois
            ORDER BY mois ASC
        ")->getResultArray();

        return view('admin/stats', [
            'title'           => 'Statistiques',
            'statsObjectif'   => $statsObjectif,
            'revenuTotal'     => $revenuTotal,
            'nbUsers'         => $nbUsers,
            'nbGold'          => $nbGold,
            'nbProgrammes'    => $nbProgrammes,
            'revenusMensuels' => $revenusMensuels,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // CRUD RÉGIMES
    // ─────────────────────────────────────────────────────────
    public function regimes()
    {
        $this->requireAdmin();
        $model    = new RegimeModel();
        $alimentModel = new AlimentModel();

        return view('admin/regimes', [
            'title'    => 'Gestion des Régimes',
            'regimes'  => $model->findAll(),
            'aliments' => $alimentModel->findAll(),
        ]);
    }

    public function storeRegime()
    {
        $this->requireAdmin();
        $model = new RegimeModel();

        $model->insert([
            'nom_regime'      => $this->request->getPost('nom_regime'),
            'poids_impact'    => $this->request->getPost('poids_impact'),
            'duree_jours'     => $this->request->getPost('duree_jours'),
            'prix_journalier' => $this->request->getPost('prix_journalier'),
            'description'     => $this->request->getPost('description'),
        ]);

        // Enregistre la composition si fournie
        $aliments     = $this->request->getPost('aliments');
        $pourcentages = $this->request->getPost('pourcentages');
        if ($aliments && $pourcentages) {
            $db       = \Config\Database::connect();
            $regimeId = $model->insertID();
            foreach ($aliments as $i => $alimentId) {
                if ($alimentId && isset($pourcentages[$i])) {
                    $db->table('regime_aliments')->insert([
                        'id_regime'   => $regimeId,
                        'id_aliment'  => $alimentId,
                        'pourcentage' => $pourcentages[$i],
                    ]);
                }
            }
        }

        return redirect()->to('/admin/regimes')->with('success', 'Régime ajouté.');
    }

    public function updateRegime(int $id)
    {
        $this->requireAdmin();
        $model = new RegimeModel();
        $model->update($id, [
            'nom_regime'      => $this->request->getPost('nom_regime'),
            'poids_impact'    => $this->request->getPost('poids_impact'),
            'duree_jours'     => $this->request->getPost('duree_jours'),
            'prix_journalier' => $this->request->getPost('prix_journalier'),
            'description'     => $this->request->getPost('description'),
        ]);
        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour.');
    }

    public function deleteRegime(int $id)
    {
        $this->requireAdmin();
        $db = \Config\Database::connect();

        $db->table('regime_aliments')->where('id_regime', $id)->delete();
        (new RegimeModel())->delete($id);

        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé.');
    }

    // ─────────────────────────────────────────────────────────
    // CRUD ACTIVITÉS
    // ─────────────────────────────────────────────────────────
    public function activites()
    {
        $this->requireAdmin();
        return view('admin/activites', [
            'title'    => 'Gestion des Activités',
            'activites'=> (new ActiviteModel())->findAll(),
        ]);
    }

    public function storeActivite()
    {
        $this->requireAdmin();
        (new ActiviteModel())->insert([
            'nom_activite' => $this->request->getPost('nom_activite'),
            'poids_impact' => $this->request->getPost('poids_impact'),
            'duree_jours'  => $this->request->getPost('duree_jours'),
        ]);
        return redirect()->to('/admin/activites')->with('success', 'Activité ajoutée.');
    }

    public function updateActivite(int $id)
    {
        $this->requireAdmin();
        (new ActiviteModel())->update($id, [
            'nom_activite' => $this->request->getPost('nom_activite'),
            'poids_impact' => $this->request->getPost('poids_impact'),
            'duree_jours'  => $this->request->getPost('duree_jours'),
        ]);
        return redirect()->to('/admin/activites')->with('success', 'Activité mise à jour.');
    }

    public function deleteActivite(int $id)
    {
        $this->requireAdmin();
        (new ActiviteModel())->delete($id);
        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée.');
    }

    // ─────────────────────────────────────────────────────────
    // CRUD ALIMENTS
    // ─────────────────────────────────────────────────────────
    public function aliments()
    {
        $this->requireAdmin();
        return view('admin/aliments', [
            'title'    => 'Gestion des Aliments',
            'aliments' => (new AlimentModel())->findAll(),
            'types'    => AlimentModel::types(),
        ]);
    }

    public function storeAliment()
    {
        $this->requireAdmin();
        (new AlimentModel())->insert([
            'nom_aliment'  => $this->request->getPost('nom_aliment'),
            'type_aliment' => $this->request->getPost('type_aliment'),
        ]);
        return redirect()->to('/admin/aliments')->with('success', 'Aliment ajouté.');
    }

    public function deleteAliment(int $id)
    {
        $this->requireAdmin();
        $db = \Config\Database::connect();

        $estUtilise = $db->table('regime_aliments')
            ->where('id_aliment', $id)
            ->countAllResults() > 0;

        if ($estUtilise) {
            return redirect()->to('/admin/aliments')->with(
                'error',
                'Impossible de supprimer cet aliment car il est encore utilise dans un ou plusieurs regimes.'
            );
        }

        (new AlimentModel())->delete($id);
        return redirect()->to('/admin/aliments')->with('success', 'Aliment supprimé.');
    }

    // ─────────────────────────────────────────────────────────
    // GESTION DES CODES DE RECHARGE
    // ─────────────────────────────────────────────────────────
    public function codes()
    {
        $this->requireAdmin();
        $db = \Config\Database::connect();

        // Codes avec infos d'utilisation
        $codes = $db->query("
            SELECT cr.*,
                   CASE WHEN cr.est_utilise = 1 THEN u.email ELSE NULL END AS utilise_par
            FROM codes_recharge cr
            LEFT JOIN portemonnaie pm ON pm.user_id IS NOT NULL
            LEFT JOIN users u ON u.id = pm.user_id
            GROUP BY cr.id
            ORDER BY cr.id DESC
        ")->getResultArray();

        return view('admin/codes', [
            'title' => 'Codes de Recharge',
            'codes' => $codes,
        ]);
    }

    public function storeCode()
    {
        $this->requireAdmin();
        $db = \Config\Database::connect();
        $db->table('codes_recharge')->insert([
            'code'      => strtoupper($this->request->getPost('code')),
            'valeur'    => $this->request->getPost('valeur'),
            'est_valide'=> 0,
        ]);
        return redirect()->to('/admin/codes')->with('success', 'Code créé.');
    }

    public function validerCode(int $id)
    {
        $this->requireAdmin();
        $db = \Config\Database::connect();
        $db->table('codes_recharge')->where('id', $id)->update(['est_valide' => 1]);
        return redirect()->to('/admin/codes')->with('success', 'Code validé.');
    }

    public function deleteCode(int $id)
    {
        $this->requireAdmin();
        $db = \Config\Database::connect();
        $db->table('codes_recharge')->where('id', $id)->delete();
        return redirect()->to('/admin/codes')->with('success', 'Code supprimé.');
    }

    // ─────────────────────────────────────────────────────────
    // GESTION UTILISATEURS
    // ─────────────────────────────────────────────────────────
    public function users()
    {
        $this->requireAdmin();
        $db    = \Config\Database::connect();
        $users = $db->query("
            SELECT u.*, p.solde,
                   ud.taille, ud.poids_actuel,
                   COUNT(up.id) AS nb_programmes
            FROM users u
            LEFT JOIN portemonnaie p ON p.user_id = u.id
            LEFT JOIN user_details ud ON ud.user_id = u.id
            LEFT JOIN user_programmes up ON up.user_id = u.id
            WHERE u.role = 'user'
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ")->getResultArray();

        return view('admin/users', [
            'title' => 'Gestion des Utilisateurs',
            'users' => $users,
        ]);
    }

    public function toggleGold(int $id)
    {
        $this->requireAdmin();
        (new UserModel())->toggleGold($id);
        return redirect()->to('/admin/users')->with('success', 'Statut Gold modifié.');
    }

    public function requestGold(int $id)
    {
        $this->requireAdmin();
        (new UserModel())->requestGold($id);
        return redirect()->to('/admin/users')->with('success', 'Demande Gold enregistrée.');
    }
}
