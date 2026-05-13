<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table      = 'departements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'description'];
}