<?php
namespace App\Controllers;
use App\Models\MongoModel;
use App\Models\MysqlModel;
use CodeIgniter\Files\File;



class AdminController extends BaseController
{
    protected $helpers = ['form'];
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

   public function adminPage()
{
   
    if ($this->session->get('role_id') != 1 && $this->session->get('role')!= 'admin') {
        //log_message('debug','usli ovde'.$session->get('role'));
        return redirect()->to('login-form');
    }
   
    $korisnici = $this->model->getAllUsers();
    // log_message('debug',"aaaaaaaaaaaa");
    // log_message('debug',print_r($korisnici));
    // log_message('debug',"aaaaaaaaaaaa");
    $kategorije = $this->model->getCategories();
    $narudzbine = $this->model->getAllOrders();
    $poruke = $this->model->getAllMessages();
    $statistika = $this->model->getStats();
    $logovi = $this->model->getLogs();

    return view('admin', [
        'korisnici' => $korisnici,
        'kategorije' => $kategorije,
        'narudzbine' => $narudzbine,
        'poruke' => $poruke,
        'statistika' => $statistika,
        'logovi' => $logovi,
        'errors'=>[]
    ]);
}
    public function changeRole()
{
    $username = $this->request->getPost('username');
    $role_id = $this->request->getPost('role_id');

    $this->model->updateRole($username, $role_id);

    return redirect()->to('/admin#korisnici'); // osveži stranicu na delu "korisnici"
}
public function addCategory()
{
    $ime = $this->request->getPost('ime');
    $opis = $this->request->getPost('opis');
    $this->model->addCategory($ime, $opis);
    return redirect()->to('/admin#kategorije');
}
public function addProduct(){
    $naziv = $this->request->getPost('naziv');
    $cena = $this->request->getPost('cena');
    $opis = $this->request->getPost('opis');
    $kategorija_id = $this->request->getPost('kategorija_id');
    $dostupno= $this->request->getPost('dostupno');
    
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


    $this->model->addProduct($naziv, $cena, $opis, $kategorija_id, $dostupno, $imeSlike);
    return redirect()->to('/admin#proizvodi');
}

public function addTable()
{
    $oznaka = $this->request->getPost('oznaka');
    $aktivan= $this->request->getPost('aktivan');
    
    $this->model->addTable($oznaka, $aktivan);
   
    return redirect()->to('/admin#stolovi');
}





}