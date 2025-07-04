<?php
namespace App\Controllers;
use App\Models\MysqlModel;

class LoginController extends BaseController{
    protected function db()
    {
        return \Config\Database::connect('default');
    }

    public function loginForm()
{
    return view('login');
}

public function login()
{
        helper(['form']);
        log_message('debug', $this->request->getMethod());
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        if($_SESSION['database']=='mysql'){
        $model = new MysqlModel();
        if ($model->login($username, $password)) {
            log_message('debug', $_SESSION['role_id']);
            if($_SESSION['role_id']==1){
                log_message('debug', "admin");
                return redirect()->to('/admin');
            }
            else if($_SESSION['role_id']==2){
                log_message('debug', "narudzbine");
                return redirect()->to('/konobar');
            }
            else {
                log_message('debug', "gost");
                return redirect()->to('/stolovi');
            }
            
        } else {
            return view('login', ['error' => 'Podaci nisu ispravni']);
        
    }
}
    return redirect()->to('/login-form');
}


}