<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function index()
    {
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectByRole();
        }
        return view('auth/login', ['title' => 'Connexion — TechMada RH']);
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new EmployeModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }

        if (!$user['actif']) {
            return redirect()->back()
                ->with('error', 'Votre compte a été désactivé. Contactez l\'administrateur.');
        }

        $this->session->set([
            'isLoggedIn'    => true,
            'userId'        => $user['id'],
            'userName'      => trim($user['prenom'] . ' ' . $user['nom']),
            'userEmail'     => $user['email'],
            'userRole'      => $user['role'],
            'departementId' => $user['departement_id'],
        ]);

        return $this->redirectByRole();
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Déconnexion réussie.');
    }

    private function redirectByRole()
    {
        return match ($this->session->get('userRole')) {
            'admin' => redirect()->to('/admin/dashboard'),
            'rh'    => redirect()->to('/rh'),
            default => redirect()->to('/employe/dashboard'),
        };
    }
}