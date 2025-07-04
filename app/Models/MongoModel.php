<?php
namespace App\Models;
use MongoDB\Client;

class MongoModel
{
    protected $client;
    protected $collection;

    public function __construct()
    {
        $this->client = new Client("mongodb://localhost:27017");
        $this->collection = $this->client->bazaprojekat->korisnici;
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