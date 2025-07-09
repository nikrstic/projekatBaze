<?php
namespace App\Controllers;
use App\Models\MongoModel;
use App\Models\MysqlModel;

class RegistrationController extends BaseController{


    public function registration(){
        $session = session();
        // if ($session->has('database')) {
        //     echo 'Session exists!<br>';
        // } else {
        //      echo 'Session not exists!<br>';
        // }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $ime = $_POST['ime'];
            $prezime = $_POST['prezime'];
            $email=$_POST['email'];
            $password = $_POST['password'];
            $contact = $_POST['contact'];
            $pol = $_POST['pol'];
            if($_SESSION['database']=='mysql'){
                $model = new MysqlModel();
                $model->register($username, $ime, $prezime, $email, $password, $contact, $pol);
            }
            if($_SESSION['database']=='mongodb'){
                $model=new MongoModel();
                $model->register($username,$ime,$prezime,$email,$password,$contact,$pol);
            }
            return redirect()->to('/login-form');
        }
        //echo 'Vrednost: ' . $session->get('database') . '<br>';
        return view('registration');
    }
}