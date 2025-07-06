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



    
}