<?php
namespace App\Models;
use Exception;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;


use CodeIgniter\I18n\Time;

class MongoModel
{
    protected $client;
    protected $korisnici;
    public function __construct()
    {
        $this->client = new Client("mongodb://localhost:27017");
        $this->korisnici = $this->client->bazaprojekat->korisnici;
    }
   public function register($username, $ime, $prezime, $email, $password, $contact, $pol)
{
    // Provera da li korisnik već postoji
    $postoji = $this->korisnici->findOne([
    '$or' => [
        ['email' => $email],
        ['username' => $username]
    ]
]);
    //log_message('debug',json_decode($postoji));   
    if ($postoji) {
        log_message('debug', 'radi ovo');
        return ['status' => false, 'msg' => 'Korisnik već postoji.'];
    }
    else{
        $dt= new \DateTime('now');
        log_message('debug', 'radi ovo1111');
        $insert = $this->korisnici->insertOne([
            'username' => $username,
            'ime' => $ime,
            'prezime' => $prezime,
            'email' => $email,
            'password' => $password,
            'contact' => $contact,
            'pol' => $pol,
            'role' => 'korisnik',
            'created_at' => new UTCDateTime()

        ]);

        return ['status' => true, 'id' => (string)$insert->getInsertedId()];
}
    
}
public function login($username, $password){
    $korisnik = $this->korisnici->findOne(['username' => $username]);
    $password1 = $this->korisnici->findOne(
    ['username' => $username],
    ['projection' => ['_id' => 0, 'password' => 1]]
);
//log_message('debug', json_encode($password1['password']));
//log_message('debug', $password);
    if ($password1 && $password1['password']==$password)
    {
        log_message('debug', "tu smo");   
         session()->set([
                'username' => $korisnik['username'],
                'ime' => $korisnik['ime'],
                'role' => $korisnik['role'],
                'user_id' =>$korisnik['_id'],
                'isLoggedIn' => true
            ]);
        return 1;
    }
    else{
        return 0;
    }   
}

public function getAllUsers()
{
    $cursor = $this->korisnici->find([], [
        'projection' => [
            '_id' => 0,
            'username' => 1,
            'ime' => 1,
            'prezime' => 1,
            'email' => 1,
            'contact' => 1,
            'pol' => 1,
            'role' => 1
        ]
    ]);
    //log_message('debug',print_r($cursor));
    
    return iterator_to_array($cursor);
}
public function getCategories()
{
    //log_message('debug', 're');
    $cursor = $this->client->bazaprojekat->kategorija->find([], []);
    //log_message('debug',  print_r(iterator_to_array($cursor), true));
    return iterator_to_array( $cursor);
}
public function getAllOrders()
{
    $narudzbine = $this->client->bazaprojekat->narudzbine->aggregate([
        [
            '$lookup' => [
                'from' => 'korisnici',
                'localField' => 'korisnik_id',
                'foreignField' => '_id',
                'as' => 'korisnik'
            ]
        ],
        [
            '$lookup' => [
                'from' => 'stolovi',
                'localField' => 'sto_id',
                'foreignField' => '_id',
                'as' => 'sto'
            ]
        ],
        ['$unwind' => '$korisnik'],
        ['$unwind' => '$sto'],
        ['$sort' => ['vreme' => -1]],
        ['$project' => [
            '_id' => 0,
            'id' => ['$toString' => '$_id'],
            'vreme' => 1,
            'status' => 1,
            'username' => '$korisnik.username',
            'sto' => '$sto.oznaka'
        ]]
    ]);

    return iterator_to_array($narudzbine);
}
public function getAllMessages()
{
    $poruke = $this->client->bazaprojekat->poruke->aggregate([
        [
            '$lookup' => [
                'from' => 'korisnici',
                'localField' => 'korisnik_id',
                'foreignField' => '_id',
                'as' => 'korisnik'
            ]
        ],
        [
            '$lookup' => [
                'from' => 'narudzbine',
                'localField' => 'narudzbina_id',
                'foreignField' => '_id',
                'as' => 'narudzbina'
            ]
        ],
        ['$unwind' => '$korisnik'],
        ['$unwind' => '$narudzbina'],
        ['$sort' => ['vreme' => -1]],
        ['$project' => [
            '_id' => 0,
            'id' => ['$toString' => '$_id'],
            'tekst' => 1,
            'vreme' => 1,
            'procitano' => 1,
            'username' => '$korisnik.username',
            'narudzbina_id' => ['$toString' => '$narudzbina._id']
        ]]
    ]);

    return iterator_to_array($poruke);
}
public function getStats()
{
    $statistika = $this->client->bazaprojekat->ukupna_potrosnja->aggregate([
        [
            '$lookup' => [
                'from' => 'korisnici',
                'localField' => 'korisnik_id',
                'foreignField' => '_id',
                'as' => 'korisnik'
            ]
        ],
        ['$unwind' => '$korisnik'],
        ['$sort' => ['iznos' => -1]],
        ['$limit' => 5],
        ['$project' => [
            '_id' => 0,
            'username' => '$korisnik.username',
            'iznos' => 1
        ]]
    ]);

    return iterator_to_array($statistika);
}
public function getLogs()
{
    $logovi = $this->client->bazaprojekat->logovi->aggregate([
        [
            '$lookup' => [
                'from' => 'korisnici',
                'localField' => 'korisnik_id',
                'foreignField' => '_id',
                'as' => 'korisnik'
            ]
        ],
        ['$unwind' => '$korisnik'],
        ['$sort' => ['vreme' => -1]],
        ['$project' => [
            '_id' => 0,
            'id' => ['$toString' => '$_id'],
            'radnja' => 1,
            'vreme' => 1,
            'username' => '$korisnik.username'
        ]]
    ]);

    return iterator_to_array($logovi);
}
    public function updateRole($username, $role_id){
        $role = $role_id==1 ? 'admin': 'korisnik'; 
        if($role_id==2)
            $role='konobar';
        log_message('debug', $username.' '.$role);
        $this->client->bazaprojekat->korisnici->updateOne(['username'=>$username],['$set' =>['role'=>$role]]);
    }
    public function addCategory($ime, $opis){
        $this->client->bazaprojekat->kategorija->insertOne(['naziv'=>$ime, 'opis'=>$opis]);
    }
    public function addProduct($naziv, $cena, $opis, $kategorija_id, $dostupno, $imeSlike){
        $this->client->bazaprojekat->proizvodi->insertOne(['naziv'=>$naziv, 'cena'=>(float)$cena, 'opis'=>$opis, 'kategorija_id'=>new ObjectId($kategorija_id), 'dostupno'=> $dostupno, 'slika'=>$imeSlike]);
    }
    public function addTable($oznaka, $aktivan){
        $this->client->bazaprojekat->stolovi->insertOne(['oznaka'=>$oznaka, 'aktivan'=>$aktivan]);
    }

    // gost
    public function getTables(){
        return  iterator_to_array($this->client->bazaprojekat->stolovi->find());
    }
    public function getCategory($kategorija_id)
{
    // if (!ObjectId::isValid($kategorija_id)) {
    //     log_message('error', 'Nevalidan kategorija ID: ' . $kategorija_id);
    //     return null;
    // }

    return $this->client->bazaprojekat->kategorija->findOne([
        '_id' => new ObjectId($kategorija_id)
    ]);
}
public function getProductsByCategory($kategorija_id){
    return iterator_to_array($this->client->bazaprojekat->proizvodi->find(['kategorija_id'=>new ObjectID($kategorija_id)]));
}
public function getCart($user_id)
{
    //$user_id ? log_message('debug', $user_id) : log_message('debug', 'null');
    $cursor = $this->client->bazaprojekat->korpa->find([
        'korisnik_id' => $user_id
    ]);
    $array =iterator_to_array($cursor);
    return $array;
    
}

    // public function getCategory($kategorija_id)
    // {
    //     return $this->client->bazaprojekat->kategorija->findOne(['_id' => new ObjectId($kategorija_id)]);
    // }


public function addToCart($user_id, $proizvod_id)
{
    $collection = $this->client->bazaprojekat->korpa;
    $proizvod = $this->client->bazaprojekat->proizvodi->findOne(
    ['_id' => new ObjectId($proizvod_id)],
    ['projection' => ['_id' => 0, 'naziv' => 1, 'cena' => 1]]);    
    $postoji = $collection->findOne([
        'korisnik_id' => new ObjectId($user_id),
        'proizvod_id' => new ObjectId($proizvod_id)
    ]);

    if ($postoji) {
        $collection->updateOne(
            ['_id' => $postoji['_id']],
            ['$inc' => ['kolicina' => 1]]
        );
    } else {
        $collection->insertOne([
            'korisnik_id' => new ObjectId($user_id),
            'proizvod_id' => new ObjectId($proizvod_id),
            'kolicina' => 1,
            'naziv' => $proizvod['naziv'],
            'cena' => $proizvod['cena'],
            'dodato' => new \MongoDB\BSON\UTCDateTime()
        ]);
    }
}
public function getStoById($sto_id){
    $sto = $this->client->bazaprojekat->stolovi->findOne([
        '_id' => new ObjectId($sto_id)
    ], [
        'projection' => [
            '_id' => 0,
            'oznaka' => 1
        ]
    ]);
    return $sto['oznaka'];
}
// los naziv funkcije, ali ostavljam ovako da bi kod iz kontrolera bio jedinstven
public function pozoviProceduruNarudzbine($korisnik_id, $sto_id){
    
    $korpa = iterator_to_array($this->client->bazaprojekat->korpa->find(
    ['korisnik_id' => new ObjectId($korisnik_id)],
    ['projection' => ['_id' => 0, 'naziv' => 1, 'proizvod_id'=>1, 'cena' => 1,'korisni_id'=>$korisnik_id, 'kolicina' =>1]]));
    $stavke = [];
    foreach ($korpa as $item){
        $stavke[] = [
        'proizvod_id' => $item['naziv'],
        'kolicina' => (int)$item['kolicina'],
        'naziv' => (string)$item['naziv']
    ];

    }
    $this->client->bazaprojekat->narudzbine->insertOne([
            'korisnik_id'=>$korisnik_id,
            'sto_id'=>$sto_id,
            'vreme'=> new \MongoDB\BSON\UTCDateTime(),
            'status'=> 'na cekanju',
            'artikli' => $stavke,
            'sto' => $this->getStoById($sto_id)
        ]);
    $this->client->bazaprojekat->korpa->deleteOne(['korisnik_id'=>$korisnik_id]);
}
public function deleteItem($stavka_id){
    $this->client->bazaprojekat->korpa->deleteOne(['_id'=>new ObjectId($stavka_id)]);
}
public function getNarudzbineSaStavkama(){
    return iterator_to_array($this->client->bazaprojekat->narudzbine->find([]));
}

public function azurirajStatusNarudzbine($id, $status){
    try{
        $this->client->bazaprojekat->narudzbine->updateOne(['_id'=>new ObjectId($id)],['$set'=>['status'=>$status]]);
    }
    catch(Exception $e){
        log_message('debug', $e);
    }
}
}
