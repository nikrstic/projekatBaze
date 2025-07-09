<?php
namespace App\Controllers;

use App\Models\MongoModel;
use App\Models\MysqlModel;
use Kint\Parser\MysqliPlugin;

class GostController extends BaseController{
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
    public function index(){
       
        $kategorije= $this->model->getCategories();
        return view("gost", [
            'kategorije' => $kategorije,
            
        ]);
    }
        public function prikaziProizvode($kategorija_id){
        session();
        $kategorije = $this->model->getCategories();
        $proizvodi = $this->model->getProductsByCategory($kategorija_id);
        $kategorija = $this->model->getCategory($kategorija_id);
        //log_message('debug', 'sesija: '. print_r($_SESSION));
        $korpa = $this->model->getCart(session()->get('user_id'));
        $path = WRITEPATH . 'uploads/';
        return view('meni', [
            'proizvodi' => $proizvodi,
            'kategorije' => $kategorije,
            'kategorija' => $kategorija,
            'korpa' => $korpa
        ]);
    

    }
    public function prikaziStolove()
    {
        $stolovi = $this->model->getTables();
        return view('tables', ['stolovi'=>$stolovi]);        
    }

    public function izaberiSto(){
        //log_message('debug','izberiSto');
        $session = session();
        $izabran = $this->request->getPost('sto_id'); 
        $session->set('sto', $izabran);

        return redirect()->to('/meni/kategorija');
    }
//     public function naruci()
// {
//     $korisnik_id = session()->get('user_id');
//     $sto_id = session()->get('sto');

//     $model = new MysqlModel();
//     $stavke = $model->getCart($korisnik_id);

//     $narudzbina_id = $model->kreirajNarudzbinu($korisnik_id, $sto_id);

//     foreach ($stavke as $s) {
//         $model->dodajStavkuNarudzbine($narudzbina_id, $s['id'], $s['kolicina']);
//     }
//     $model->ocistiKorpu($korisnik_id);
//     $session = session();
//     $session->setFlashdata('success', 'Narudzbina je uspesno kreirana');
//     return redirect()->to('/meni/kategorija')->with('success', 'Narudžbina je uspešno kreirana!');
// }
    public function naruci()
{
    $korisnik_id = session()->get('user_id');
    $sto_id = session()->get('sto');

    
    $this->model->pozoviProceduruNarudzbine($korisnik_id, $sto_id);

    return redirect()->to('/meni/kategorija')->with('success', 'Narudžbina uspešno napravljena!');
}
}