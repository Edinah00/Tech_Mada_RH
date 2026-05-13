<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');

        // Compteur global utilisé par la sidebar.
        // On le recalcule à chaque requête pour éviter un badge figé.
        if ($this->session->get('isLoggedIn')) {
            $role = $this->session->get('userRole');
            $db = \Config\Database::connect();
            $builder = $db->table('conges');

            if ($role === 'employe') {
                $pendingCount = $builder
                    ->where('employe_id', (int) $this->session->get('userId'))
                    ->where('statut', 'en_attente')
                    ->countAllResults();
            } else {
                $pendingCount = $builder
                    ->where('statut', 'en_attente')
                    ->countAllResults();
            }

            $this->session->set('pendingCount', $pendingCount);
        }
    }
}
