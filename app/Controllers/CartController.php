<?php
namespace App\Controllers;
use App\Models\MysqlModel;

class CartController extends BaseController{
    public function dodaj()
    {
        $user_id = session()->get('user_id');
        $proizvod_id = $this->request->getPost('proizvod_id');
        $model = new MysqlModel();
        $model->addToCart($user_id, $proizvod_id);
        return redirect()->back();
    }
      

    public function obrisi()
    {
        $model = new MysqlModel();
        $model->deleteItem($this->request->getPost('stavka_id'));
        return redirect()->back();
    }
    public function naruci(){
        $model = new MysqlModel();
        $user_id = session()->get('user_id');
        $korpa = $model->getCart($user_id);
        

    }
}
