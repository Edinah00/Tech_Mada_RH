<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table      = 'soldes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris',
    ];

    // Soldes d'un employé pour l'année
    public function getSoldesEmploye(int $empId, ?int $annee = null): array
    {
        $annee = $annee ?? (int)date('Y');
        return $this->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $empId)
            ->where('soldes.annee', $annee)
            ->findAll();
    }

    // Un solde précis
    public function getSolde(int $empId, int $typeId, int $annee): ?array
    {
        return $this->where('employe_id', $empId)
            ->where('type_conge_id', $typeId)
            ->where('annee', $annee)
            ->first();
    }

    // Déduire des jours
    public function deduire(int $empId, int $typeId, int $annee, int $nbJours): bool
    {
        $solde = $this->getSolde($empId, $typeId, $annee);
        if (!$solde) return false;

        $restant = $solde['jours_attribues'] - $solde['jours_pris'];
        if ($nbJours > $restant) return false;

        return $this->update($solde['id'], [
            'jours_pris' => $solde['jours_pris'] + $nbJours,
        ]);
    }

    // Recréditer des jours
    public function recréditer(int $empId, int $typeId, int $annee, int $nbJours): bool
    {
        $solde = $this->getSolde($empId, $typeId, $annee);
        if (!$solde) return false;

        $nouveauxPris = max(0, $solde['jours_pris'] - $nbJours);
        return $this->update($solde['id'], ['jours_pris' => $nouveauxPris]);
    }

    // Initialiser les soldes d'un nouvel employé
    public function initialiserPourEmploye(int $empId, int $annee): void
    {
        $db    = \Config\Database::connect();
        $types = $db->table('types_conge')->get()->getResultArray();

        foreach ($types as $t) {
            $existe = $this->getSolde($empId, $t['id'], $annee);
            if (!$existe) {
                $this->insert([
                    'employe_id'      => $empId,
                    'type_conge_id'   => $t['id'],
                    'annee'           => $annee,
                    'jours_attribues' => $t['jours_annuels'],
                    'jours_pris'      => 0,
                ]);
            }
        }
    }

    // Tous les soldes d'une année avec infos employé
    public function getAllSoldesAnnee(int $annee): array
    {
        return $this->select('soldes.*, types_conge.libelle,
                employes.nom, employes.prenom, employes.departement_id,
                departements.nom AS dept_nom')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->where('soldes.annee', $annee)
            ->orderBy('employes.nom')
            ->findAll();
    }
}