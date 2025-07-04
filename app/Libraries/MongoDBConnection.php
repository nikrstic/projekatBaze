<?php

namespace App\Libraries;

use MongoDB\Client;

class MongoDBConnection
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Client('mongodb://localhost:27017'))->selectDatabase('filmovi_baza');
    }

    public function getDB()
    {
        return $this->db;
    }
}
