<?php
namespace App\Controllers;
use App\Models\MongoModel;
use App\Models\MysqlModel;
use CodeIgniter\Files\File;



class AdminController extends BaseController
{
    protected $helpers = ['form'];
    protected function db()
    {
        return \Config\Database::connect('default');
    }
   public function adminPage()
{
    $session = session();
    if($_SESSION['database']=='mysql'){
        $model = new MysqlModel();
         
    }
    
    if($_SESSION['database']=='mongodb'){
        $model= new MongoModel();
    }
    if ($session->get('role_id') != 1 && $session->get('role')!= 'admin') {
        log_message('debug','usli ovde'.$session->get('role'));
        return redirect()->to('login-form');
    }
   

    $korisnici = $model->getAllUsers();
    log_message('debug',"aaaaaaaaaaaa");
    log_message('debug',print_r($korisnici));
    log_message('debug',"aaaaaaaaaaaa");
    $kategorije = $model->getAllCategories();
    $narudzbine = $model->getAllOrders();
    $poruke = $model->getAllMessages();
    $statistika = $model->getStats();
    $logovi = $model->getLogs();

    return view('admin', [
        'korisnici' => $korisnici,
        'kategorije' => $kategorije,
        'narudzbine' => $narudzbine,
        'poruke' => $poruke,
        'statistika' => $statistika,
        'logovi' => $logovi,
        ['errors'=>[]]
    ]);
}
    public function changeRole()
{
    $username = $this->request->getPost('username');
    $role_id = $this->request->getPost('role_id');

    $model = new MysqlModel();
    $model->updateRole($username, $role_id);

    return redirect()->to('/admin#korisnici'); // osveži stranicu na delu "korisnici"
}
public function addCategory()
{
    $ime = $this->request->getPost('ime');
    $opis = $this->request->getPost('opis');
    $model = new MysqlModel();
    $model->addCategory($ime, $opis);
    return redirect()->to('/admin#kategorije');
}
public function addProduct(){
    $naziv = $this->request->getPost('naziv');
    $cena = $this->request->getPost('cena');
    $opis = $this->request->getPost('opis');
    $kategorija_id = $this->request->getPost('kategorija_id');
    $dostupno= $this->request->getPost('dostupno');
    $model = new MysqlModel();
    
    $img = $this->request->getFile('slika');

    if (
        $img &&
        $img->isValid() &&
        ! $img->hasMoved() &&
        in_array($img->getClientExtension(), ['jpg', 'jpeg', 'png'])
    ) {
        $imeSlike = $img->getRandomName();
        $img->move(WRITEPATH . 'uploads', $imeSlike);
    } else {
        return redirect()->back()->with('error', 'Dozvoljeni su samo JPG i PNG fajlovi.');
    }


    $model->addProduct($naziv, $cena, $opis, $kategorija_id, $dostupno, $imeSlike);
    return redirect()->to('/admin#proizvodi');
}

public function addTable()
{
    $oznaka = $this->request->getPost('oznaka');
    $aktivan= $this->request->getPost('aktivan');
    $model = new MysqlModel();
    $model->addTable($oznaka, $aktivan);
   
    return redirect()->to('/admin#stolovi');
}





}