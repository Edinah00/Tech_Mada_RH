<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\EmployeModel;
use App\Models\DepartementModel;

class RhController extends BaseController
{
    private function requireRh(): void
    {
        if (!$this->session->get('isLoggedIn')) {
            redirect()->to('/login')->send(); exit;
        }
        $role = $this->session->get('userRole');
        if (!in_array($role, ['rh', 'admin'])) {
            redirect()->to('/employe/dashboard')->send(); exit;
        }
    }

    // ─── LISTE TOUTES LES DEMANDES ────────────────────────────
    public function index()
    {
        $this->requireRh();

        $congeModel = new CongeModel();
        $deptModel  = new DepartementModel();

        $statut = $this->request->getGet('statut');
        $deptId = $this->request->getGet('dept');

        if ($statut === 'en_attente') {
            $conges = $congeModel->getEnAttente($deptId ?: null);
        } else {
            $conges = $congeModel->getAllWithDetails();
            if ($statut) {
                $conges = array_filter($conges, fn($c) => $c['statut'] === $statut);
            }
            if ($deptId) {
                $conges = array_filter($conges, fn($c) => $c['departement_id'] == $deptId);
            }
        }

        // Compter par statut
        $all    = $congeModel->getAllWithDetails();
        $counts = ['total' => count($all), 'en_attente' => 0, 'approuvee' => 0, 'refusee' => 0];
        foreach ($all as $c) {
            if (isset($counts[$c['statut']])) $counts[$c['statut']]++;
        }

        // Ajouter le solde disponible pour chaque demande en attente
        $soldeModel = new SoldeModel();
        $congesArr  = array_values((array)$conges);
        foreach ($congesArr as &$c) {
            if ($c['statut'] === 'en_attente') {
                $annee = (int) date('Y', strtotime($c['date_debut']));
                $s = $soldeModel->getSolde($c['employe_id'], $c['type_conge_id'], $annee);
                $c['solde_dispo'] = $s ? ($s['jours_attribues'] - $s['jours_pris']) : 0;
            }
        }
        unset($c);

        return view('rh/index', [
            'title'   => 'Gestion des demandes',
            'conges'  => $congesArr,
            'counts'  => $counts,
            'statut'  => $statut,
            'deptId'  => $deptId,
            'depts'   => $deptModel->findAll(),
        ]);
    }

    // ─── APPROUVER ────────────────────────────────────────────
    public function approuver(int $id)
    {
        $this->requireRh();
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conge = $congeModel->find($id);
        if (!$conge || $conge['statut'] !== 'en_attente') {
            return redirect()->to('/rh')->with('error', 'Demande introuvable ou déjà traitée.');
        }

        // Vérifier solde
        $annee   = (int) date('Y', strtotime($conge['date_debut']));
        $solde   = $soldeModel->getSolde($conge['employe_id'], $conge['type_conge_id'], $annee);
        $restant = $solde ? ($solde['jours_attribues'] - $solde['jours_pris']) : 0;

        if ($conge['nb_jours'] > $restant) {
            return redirect()->to('/rh')->with('error', 'Solde insuffisant pour approuver cette demande.');
        }

        // Déduire le solde
        $soldeModel->deduire($conge['employe_id'], $conge['type_conge_id'], $annee, $conge['nb_jours']);

        // Mettre à jour le statut
        $congeModel->update($id, [
            'statut'     => 'approuvee',
            'traite_par' => $this->session->get('userId'),
            'commentaire_rh' => $this->request->getPost('commentaire_rh') ?: null,
        ]);

        return redirect()->to('/rh')
            ->with('success', 'Demande approuvée. Le solde a été mis à jour.');
    }

    // ─── REFUSER ──────────────────────────────────────────────
    public function refuser(int $id)
    {
        $this->requireRh();
        $congeModel = new CongeModel();
        $conge      = $congeModel->find($id);

        if (!$conge || $conge['statut'] !== 'en_attente') {
            return redirect()->to('/rh')->with('error', 'Demande introuvable ou déjà traitée.');
        }

        $commentaire = $this->request->getPost('commentaire_rh') ?: 'Demande refusée.';

        $congeModel->update($id, [
            'statut'         => 'refusee',
            'traite_par'     => $this->session->get('userId'),
            'commentaire_rh' => $commentaire,
        ]);

        return redirect()->to('/rh')
            ->with('success', 'Demande refusée.');
    }

    // ─── SOLDES EMPLOYÉS ─────────────────────────────────────
    public function soldes()
    {
        $this->requireRh();
        $soldeModel = new SoldeModel();
        $annee      = (int)($this->request->getGet('annee') ?? date('Y'));

        return view('rh/soldes', [
            'title'  => 'Soldes des employés',
            'soldes' => $soldeModel->getAllSoldesAnnee($annee),
            'annee'  => $annee,
        ]);
    }
}