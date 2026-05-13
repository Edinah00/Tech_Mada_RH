<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table      = 'employes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom', 'prenom', 'email', 'password', 'role',
        'departement_id', 'date_embauche', 'actif',
    ];

    // Trouver par email
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    // Tous les employés actifs avec leur département
    public function getAllWithDepartement(): array
    {
        return $this->select('employes.*, departements.nom AS departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('employes.nom')
            ->findAll();
    }

    public function findWithDepartement(int $id): ?array
    {
        return $this->select('employes.*, departements.nom AS departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->where('employes.id', $id)
            ->first();
    }

    // Employés d'un département
    public function getByDepartement(int $deptId): array
    {
        return $this->where('departement_id', $deptId)->where('actif', 1)->findAll();
    }

    // Initiales pour l'avatar
    public static function initiales(array $emp): string
    {
        return strtoupper(
            substr($emp['prenom'] ?? '', 0, 1) .
            substr($emp['nom']    ?? '', 0, 1)
        );
    }
}
