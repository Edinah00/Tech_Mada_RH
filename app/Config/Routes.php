<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── AUTHENTIFICATION ──────────────────────────────────────────
$routes->get('/',               'AuthController::index');
$routes->get('/login',          'AuthController::index');
$routes->post('/login',         'AuthController::login');
$routes->get('/logout',         'AuthController::logout');


// ── EMPLOYÉ ───────────────────────────────────────────────────
$routes->get('/employe/dashboard',       'EmployeController::dashboard');

// Demandes de congé
$routes->get('/employe/conge',           'EmployeController::index');
$routes->get('/employe/conge/create',    'EmployeController::create');
$routes->post('/employe/conge/store',    'EmployeController::store');
$routes->post('/employe/conge/annuler/(:num)', 'EmployeController::annuler/$1');

// Profil
$routes->get('/employe/profil',          'EmployeController::profil');
$routes->post('/employe/profil/update',  'EmployeController::updateProfil');


// ── RESPONSABLE RH ────────────────────────────────────────────
$routes->get('/rh',                      'RhController::index');
$routes->post('/rh/approuver/(:num)',    'RhController::approuver/$1');
$routes->post('/rh/refuser/(:num)',      'RhController::refuser/$1');
$routes->get('/rh/soldes',               'RhController::soldes');


// ── ADMIN ─────────────────────────────────────────────────────
$routes->get('/admin/dashboard',         'AdminController::stats');

// CRUD Employés
$routes->get('/admin/employe',                      'AdminController::employes');
$routes->post('/admin/employe/store',               'AdminController::storeEmploye');
$routes->post('/admin/employe/update/(:num)',        'AdminController::updateEmploye/$1');
$routes->post('/admin/employe/desactiver/(:num)',    'AdminController::desactiverEmploye/$1');
$routes->post('/admin/employe/solde/(:num)',         'AdminController::ajusterSolde/$1');

// CRUD Départements
$routes->get('/admin/departement',                  'AdminController::departements');
$routes->post('/admin/departement/store',           'AdminController::storeDepartement');
$routes->post('/admin/departement/update/(:num)',   'AdminController::updateDepartement/$1');
$routes->post('/admin/departement/delete/(:num)',   'AdminController::deleteDepartement/$1');

// CRUD Types de congé
$routes->get('/admin/type-conge',                   'AdminController::typesConge');
$routes->post('/admin/type-conge/store',            'AdminController::storeTypeConge');
$routes->post('/admin/type-conge/update/(:num)',    'AdminController::updateTypeConge/$1');
$routes->post('/admin/type-conge/delete/(:num)',    'AdminController::deleteTypeConge/$1');

// Historique & Stats
$routes->get('/admin/historique',                   'AdminController::historique');
$routes->get('/admin/stats',                        'AdminController::stats');
