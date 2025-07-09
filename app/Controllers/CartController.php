<?php
namespace App\Controllers;
use App\Models\MysqlModel;
use App\Models\MongoModel;
class CartController extends BaseController{
     protected $session;
    protected $model;
   public function __construct()
{
    $this->session = session();
    
    if ($this->session->get('database') === 'mysql') {
        $this->model = new MysqlModel();
    } elseif ($this->session->get('database') === 'mongodb') {
        $this->model = new MongoModel();
    }
}
    public function dodaj()
    {
        $user_id = session()->get('user_id');
        $proizvod_id = $this->request->getPost('proizvod_id');
        log_message('debug', 'ss'.(string)$proizvod_id);
        $this->model->addToCart($user_id, $proizvod_id);
        return redirect()->back();
    }
      

    public function obrisi()
    {
        $this->model->deleteItem($this->request->getPost('stavka_id'));
        return redirect()->back();
    }
    public function naruci(){
        
        $user_id = session()->get('user_id');
        $this->model->getCart($user_id);
        

    }
}
