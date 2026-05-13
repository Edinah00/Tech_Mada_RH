<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table      = 'types_conge';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'jours_annuels', 'deductible'];
}