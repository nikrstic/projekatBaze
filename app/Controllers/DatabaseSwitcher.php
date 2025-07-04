<?php
namespace App\Controllers;
use App\Models\MysqlModel;
use App\Models\MongoModel;
use CodeIgniter\Session\Session;
class DatabaseSwitcher extends BaseController
{   
    protected $session;
   public function __construct()
    {
        $this->session=session();
    }

    public function menu()
    {
        
        return view('menu');
    }

    // public function loadMysql()
    // {
    //     $model = new MysqlModel();
    //     $model->setup();
    //     $data['podaci'] = $model->getAll();
    //     $data['tip'] = 'MySQL';
    //     return view('prikaz', $data);
    // }
    public function loadMysql(){
    
    $session = session();
    //$session->start();
    $session->set('database', 'mysql');
    $model = new MysqlModel();
    $model->setup();
    return redirect()->to('/login-form');
    }
public function loadMongo(){
    $session=session();
    $session->set('database', 'mongodb');
    return redirect()->to('/login-form');
}

    // public function loadMongo()
    // {
    //     $model = new MongoModel();
    //     $model->setup();
    //     $data['podaci'] = $model->getAll();
    //     $data['tip'] = 'MongoDB';
    //     return view('prikaz', $data);
    // }
}