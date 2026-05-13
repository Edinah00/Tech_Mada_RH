<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserDetailModel;

/**
 * Gère l'authentification et l'inscription en 2 étapes.
 */
class AuthController extends BaseController
{
    public function index()
    {
        // Redirige si déjà connecté
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login', ['title' => 'Connexion']);
    }

    /**
     * Traite la connexion (POST /login).
     */
    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect.');
        }

        $this->session->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'userName'   => $user['nom'],
            'userEmail'  => $user['email'],
            'userRole'   => $user['role'],
            'isGold'     => (bool)$user['is_gold'],
        ]);

        return redirect()->to($user['role'] === 'admin' ? '/admin' : '/dashboard');
    }

    /**
     * Affiche la page d'inscription (étape 1).
     */
    public function register()
    {
        return view('auth/register', ['title' => 'Créer un compte']);
    }

    /**
     * AJAX — Étape 1 : valide les données de base et crée l'utilisateur temporaire.
     * Retourne JSON { success, userId, message }.
     */
    public function registerStep1()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/register');
        }

        $rules = [
            'nom'      => 'required|min_length[2]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'genre'    => 'required|in_list[M,F]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        // Sauvegarde temporaire en session pour l'étape 2
        $this->session->set('register_step1', [
            'nom'      => $this->request->getPost('nom'),
            'email'    => $this->request->getPost('email'),
            'genre'    => $this->request->getPost('genre'),
            'password' => $this->request->getPost('password'),
        ]);

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Étape 1 validée.',
            'csrfHash'  => csrf_hash(),
        ]);
    }

    /**
     * AJAX — Étape 2 : enregistre les données de santé et finalise l'inscription.
     * Retourne JSON { success, redirect, message }.
     */
    public function registerStep2()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/register');
        }

        $step1 = $this->session->get('register_step1');
        if (!$step1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session expirée. Recommencez.']);
        }

        $rules = [
            'taille' => 'required|numeric|greater_than[0.5]|less_than[3]',
            'poids'  => 'required|numeric|greater_than[20]|less_than[300]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        try {
            // Crée l'utilisateur et son porte-monnaie
            $userModel = new UserModel();
            $userId    = $userModel->createWithWallet($step1);

            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Impossible de créer le compte utilisateur.',
                    'csrfHash' => csrf_hash(),
                ]);
            }

            // Enregistre les détails de santé
            $detailModel = new UserDetailModel();
            $detailModel->insert([
                'user_id'      => $userId,
                'taille'       => $this->request->getPost('taille'),
                'poids_actuel' => $this->request->getPost('poids'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Erreur inscription étape 2: {message}', [
                'message' => $e->getMessage(),
            ]);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        // Connecte automatiquement l'utilisateur
        $user = $userModel->find($userId);
        $this->session->remove('register_step1');
        $this->session->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'userName'   => $user['nom'],
            'userEmail'  => $user['email'],
            'userRole'   => 'user',
            'isGold'     => (bool)($user['is_gold'] ?? false),
        ]);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Compte créé avec succès !',
            'redirect' => base_url('/dashboard'),
            'csrfHash' => csrf_hash(),
        ]);
    }

    /**
     * Déconnexion.
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
