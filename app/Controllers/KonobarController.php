<?php
namespace App\Controllers;

use App\Models\MysqlModel;

class KonobarController extends BaseController
{
    public function prikaziNarudzbine()
    {
        $model = new MysqlModel();
        $narudzbine = $model->getNarudzbineSaStavkama();
        return view('konobar', ['narudzbine' => $narudzbine]);
    }

    public function promeniStatus()
    {
        $id = $this->request->getPost('narudzbina_id');
        $status = $this->request->getPost('novi_status');
        $model = new MysqlModel();
        $model->azurirajStatusNarudzbine($id, $status);
        return redirect()->back()->with('success', 'Status narudžbine promenjen.');
    }
}