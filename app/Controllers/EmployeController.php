<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Models\EmployeModel;

class EmployeController extends BaseController
{
    private function requireEmploye(): void
    {
        if (!$this->session->get('isLoggedIn')) {
            redirect()->to('/login')->send(); exit;
        }
    }

    private function empId(): int
    {
        return (int) $this->session->get('userId');
    }

    // ─── DASHBOARD ───────────────────────────────────────────
    public function dashboard()
    {
        $this->requireEmploye();
        $empId  = $this->empId();
        $annee  = (int) date('Y');

        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conges = $congeModel->getByEmploye($empId);
        $soldes = $soldeModel->getSoldesEmploye($empId, $annee);

        // Compter par statut
        $stats = ['en_attente' => 0, 'approuve' => 0, 'refuse' => 0];
        foreach ($conges as $c) {
            if (isset($stats[$c['statut']])) {
                $stats[$c['statut']]++;
            }
        }

        // Jours restants (annuel)
        $joursRestants = 0;
        foreach ($soldes as $s) {
            if ($s['type_conge_id'] == 1) {
                $joursRestants = $s['jours_attribues'] - $s['jours_pris'];
            }
        }

        return view('employe/dashboard', [
            'title'         => 'Mon tableau de bord',
            'conges'        => array_slice($conges, 0, 5),
            'soldes'        => $soldes,
            'stats'         => $stats,
            'joursRestants' => $joursRestants,
        ]);
    }

    // ─── LISTE DES CONGÉS ─────────────────────────────────────
    public function index()
    {
        $this->requireEmploye();
        $congeModel = new CongeModel();

        $statut = $this->request->getGet('statut');
        $conges = $congeModel->getByEmploye($this->empId());

        if ($statut) {
            $conges = array_filter($conges, fn($c) => $c['statut'] === $statut);
        }

        return view('employe/index', [
            'title'  => 'Mes demandes de congé',
            'conges' => array_values($conges),
            'statut' => $statut,
        ]);
    }

    // ─── FORMULAIRE CRÉATION ─────────────────────────────────
    public function create()
    {
        $this->requireEmploye();
        $soldeModel = new SoldeModel();
        $typeModel  = new TypeCongeModel();

        $soldes = $soldeModel->getSoldesEmploye($this->empId());
        $types  = $typeModel->findAll();

        return view('employe/create', [
            'title'  => 'Nouvelle demande de congé',
            'soldes' => $soldes,
            'types'  => $types,
        ]);
    }

    // ─── SOUMISSION ───────────────────────────────────────────
    public function store()
    {
        $this->requireEmploye();
        $empId = $this->empId();

        $debut  = $this->request->getPost('date_debut');
        $fin    = $this->request->getPost('date_fin');
        $typeId = (int) $this->request->getPost('type_conge_id');
        $motif  = $this->request->getPost('motif');

        // Validation de base
        if (!$debut || !$fin || !$typeId) {
            return redirect()->back()->withInput()
                ->with('error', 'Tous les champs obligatoires doivent être remplis.');
        }

        if ($debut > $fin) {
            return redirect()->back()->withInput()
                ->with('error', 'La date de fin doit être postérieure à la date de début.');
        }

        if ($debut < date('Y-m-d')) {
            return redirect()->back()->withInput()
                ->with('error', 'La date de début ne peut pas être dans le passé.');
        }

        // Calcul des jours ouvrables
        $nbJours = CongeModel::calcJoursOuvrables($debut, $fin);

        if ($nbJours <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'La période sélectionnée ne contient pas de jours ouvrables.');
        }

        // Vérification chevauchement
        $congeModel = new CongeModel();
        if ($congeModel->hasChevauchement($empId, $debut, $fin)) {
            return redirect()->back()->withInput()
                ->with('error', 'Cette période chevauche une demande existante en cours.');
        }

        // Vérification solde
        $soldeModel = new SoldeModel();
        $annee      = (int) date('Y', strtotime($debut));
        $solde      = $soldeModel->getSolde($empId, $typeId, $annee);

        if (!$solde) {
            return redirect()->back()->withInput()
                ->with('error', 'Aucun solde disponible pour ce type de congé.');
        }

        $restant = $solde['jours_attribues'] - $solde['jours_pris'];
        if ($nbJours > $restant) {
            return redirect()->back()->withInput()
                ->with('error', "Solde insuffisant : vous avez $restant jour(s) restant(s) pour ce type.");
        }

        // Insertion
        $congeModel->insert([
            'employe_id'    => $empId,
            'type_conge_id' => $typeId,
            'date_debut'    => $debut,
            'date_fin'      => $fin,
            'nb_jours'      => $nbJours,
            'motif'         => $motif,
            'statut'        => 'en_attente',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/employe/conge')
            ->with('success', "Demande soumise avec succès ($nbJours jour(s) ouvrables). En attente de validation.");
    }

    // ─── ANNULER ──────────────────────────────────────────────
    public function annuler(int $id)
    {
        $this->requireEmploye();
        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);

        if (!$conge || $conge['employe_id'] != $this->empId()) {
            return redirect()->to('/employe/conge')
                ->with('error', 'Demande introuvable.');
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->to('/employe/conge')
                ->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }

        $congeModel->update($id, ['statut' => 'annule']);

        return redirect()->to('/employe/conge')
            ->with('success', 'Demande annulée.');
    }

    // ─── PROFIL ───────────────────────────────────────────────
    public function profil()
    {
        $this->requireEmploye();
        $model = new EmployeModel();
        $emp   = $model->findWithDepartement($this->empId());

        return view('employe/profil', [
            'title' => 'Mon profil',
            'emp'   => $emp,
        ]);
    }

    public function updateProfil()
    {
        $this->requireEmploye();
        $model = new EmployeModel();
        $empId = $this->empId();

        $data = [
            'nom'    => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
        ];

        $newPwd = $this->request->getPost('password');
        if ($newPwd) {
            $data['password'] = password_hash($newPwd, PASSWORD_DEFAULT);
        }

        $model->update($empId, $data);
        $this->session->set('userName', trim($data['prenom'] . ' ' . $data['nom']));

        return redirect()->to('/employe/profil')
            ->with('success', 'Profil mis à jour.');
    }
}
