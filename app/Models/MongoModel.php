<?php
namespace App\Models;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;


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
    log_message('debug',print_r($cursor));
    
    return iterator_to_array($cursor);
}
public function getAllCategories()
{
    $cursor = $this->client->bazaprojekat->kategorije->find([], [
        'projection' => ['_id' => 0]
    ]);

    return iterator_to_array($cursor);
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
    
}