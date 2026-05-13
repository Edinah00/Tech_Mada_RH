<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table      = 'conges';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'type_conge_id', 'date_debut', 'date_fin',
        'nb_jours', 'motif', 'statut', 'commentaire_rh',
        'traite_par', 'created_at',
    ];

    // Demandes d'un employé avec le libellé du type
    public function getByEmploye(int $empId): array
    {
        return $this->select('conges.*, types_conge.libelle AS type_libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $empId)
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();
    }

    // Toutes les demandes avec infos employé et type
    public function getAllWithDetails(): array
    {
        return $this->select('conges.*, 
                types_conge.libelle AS type_libelle,
                employes.nom, employes.prenom, employes.departement_id,
                departements.nom AS dept_nom')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();
    }

    // Demandes en attente
    public function getEnAttente(?int $deptId = null): array
    {
        $q = $this->select('conges.*, 
                types_conge.libelle AS type_libelle,
                employes.nom, employes.prenom, employes.departement_id,
                departements.nom AS dept_nom')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->where('conges.statut', 'en_attente')
            ->orderBy('conges.created_at', 'ASC');

        if ($deptId) {
            $q->where('employes.departement_id', $deptId);
        }
        return $q->findAll();
    }

    // Vérifier chevauchement
    public function hasChevauchement(int $empId, string $debut, string $fin, ?int $excludeId = null): bool
    {
        $q = $this->where('employe_id', $empId)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->groupStart()
                ->where('date_debut <=', $fin)
                ->where('date_fin >=', $debut)
            ->groupEnd();

        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }
        return $q->countAllResults() > 0;
    }

    // Calculer jours ouvrables (sans week-ends)
    public static function calcJoursOuvrables(string $debut, string $fin): int
    {
        $d = new \DateTime($debut);
        $f = new \DateTime($fin);
        $count = 0;
        while ($d <= $f) {
            $dow = (int)$d->format('N');
            if ($dow < 6) {
                $count++;
            }
            $d->modify('+1 day');
        }
        return $count;
    }

    // Stats pour admin
    public function statsAdmin(): array
    {
        $db = \Config\Database::connect();
        $annee = date('Y');
        $mois  = date('m');

        return [
            'total_en_attente' => $this->where('statut', 'en_attente')->countAllResults(),
            'approuvees_mois'  => $db->table('conges')
                ->where('statut', 'approuvee')
                ->like('created_at', "$annee-$mois", 'after')
                ->countAllResults(),
            'absents_auj' => $db->table('conges')
                ->where('statut', 'approuvee')
                ->where('date_debut <=', date('Y-m-d'))
                ->where('date_fin >=', date('Y-m-d'))
                ->countAllResults(),
        ];
    }
}