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
     * Déconnexion.
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
