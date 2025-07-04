<?php

namespace App\Controllers;

class LogoutController extends BaseController
{
    public function userLogout()
    {
        session()->remove(['user_id', 'username', 'ime', 'role_id', 'isLoggedIn']);
        return redirect()->to('/login-form');
    }

    public function fullLogout()
    {
        session()->destroy(); // uništava sve, uključujući i 'database'
        return redirect()->to('/');
    }
}
