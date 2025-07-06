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
    $postoji = $this->korisnici->findOne(['email' => $email]);
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
            'created_at' => new UTCDateTime()

        ]);

        return ['status' => true, 'id' => (string)$insert->getInsertedId()];
}
}
    public function login(){
        $mongo = 'db.';
    }

    public function setup()
    {
        $count = $this->collection->countDocuments();
        if ($count === 0) {
            $this->collection->insertMany([
                ['ime' => 'Mila', 'prezime' => 'Milic'],
                ['ime' => 'Nemanja', 'prezime' => 'Nemanic']
            ]);
        }
    }

    public function getAll()
    {
        return $this->collection->find()->toArray();
    }
}