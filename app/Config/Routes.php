<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// ── AUTHENTIFICATION ──────────────────────────────────────────
$routes->get('/',                'AuthController::index');
$routes->get('/login',           'AuthController::index');
$routes->post('/login',          'AuthController::login');
$routes->get('/logout',          'AuthController::logout');
 
// Inscription étape 1
$routes->get('/register',        'AuthController::register');
$routes->post('/register/step1', 'AuthController::registerStep1');  // AJAX
// Inscription étape 2
$routes->post('/register/step2', 'AuthController::registerStep2');  // AJAX
 
// ── FRONT OFFICE (utilisateurs connectés) ────────────────────
$routes->get('/dashboard',       'DashboardController::index');
 
// Moteur de suggestion
$routes->get('/suggestions',     'SuggestionController::index');
$routes->post('/suggestions/get','SuggestionController::getSuggestions'); // AJAX
 
// Programme : achat + PDF
$routes->post('/programme/acheter',  'ProgrammeController::acheter');
$routes->get('/programme/pdf/(:num)','ProgrammeController::exportPdf/$1');
$routes->get('/mes-programmes',      'ProgrammeController::mesProgrammes');
 
// Porte-monnaie
$routes->get('/portefeuille',        'PortefeuilleController::index');
$routes->post('/portefeuille/recharger', 'PortefeuilleController::recharger'); // AJAX
$routes->post('/portefeuille/demander-gold', 'PortefeuilleController::demanderGold'); // AJAX
 
// ── BACK OFFICE (admin) ───────────────────────────────────────
$routes->get('/admin',               'AdminController::index');
$routes->get('/admin/stats',         'AdminController::stats');
 
// CRUD Régimes
$routes->get('/admin/regimes',           'AdminController::regimes');
$routes->post('/admin/regimes/store',    'AdminController::storeRegime');
$routes->post('/admin/regimes/update/(:num)', 'AdminController::updateRegime/$1');
$routes->post('/admin/regimes/delete/(:num)', 'AdminController::deleteRegime/$1');
 
// CRUD Activités
$routes->get('/admin/activites',             'AdminController::activites');
$routes->post('/admin/activites/store',      'AdminController::storeActivite');
$routes->post('/admin/activites/update/(:num)', 'AdminController::updateActivite/$1');
$routes->post('/admin/activites/delete/(:num)', 'AdminController::deleteActivite/$1');
 
// CRUD Aliments
$routes->get('/admin/aliments',              'AdminController::aliments');
$routes->post('/admin/aliments/store',       'AdminController::storeAliment');
$routes->post('/admin/aliments/delete/(:num)','AdminController::deleteAliment/$1');
 
// Gestion codes de recharge
$routes->get('/admin/codes',                 'AdminController::codes');
$routes->post('/admin/codes/store',          'AdminController::storeCode');
$routes->post('/admin/codes/valider/(:num)', 'AdminController::validerCode/$1');
$routes->post('/admin/codes/delete/(:num)',  'AdminController::deleteCode/$1');
 
// Gestion utilisateurs
$routes->get('/admin/users',                 'AdminController::users');
$routes->post('/admin/users/gold/(:num)',     'AdminController::toggleGold/$1');
$routes->post('/admin/users/request-gold/(:num)', 'AdminController::requestGold/$1');