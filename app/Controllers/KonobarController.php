<?php
namespace App\Controllers;

use App\Models\MysqlModel;
use App\Models\MongoModel;
class KonobarController extends BaseController
{
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
    public function prikaziNarudzbine()
    {
        $narudzbine = $this->model->getNarudzbineSaStavkama();
        return view('konobar', ['narudzbine' => $narudzbine]);
    }

    public function promeniStatus()
    {
        $id = $this->request->getPost('narudzbina_id');
        $status = $this->request->getPost('novi_status');
        $this->model->azurirajStatusNarudzbine($id, $status);
        return redirect()->back()->with('success', 'Status narudžbine promenjen.');
    }
}