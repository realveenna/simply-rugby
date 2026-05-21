<?php
namespace Test;
use Test\Database;
use Test\Models\Squad;

    class Base 
    {
        protected $squads;
        protected $pdo;

        public function __construct()
        {
            $this->pdo = Database::getInstance()->getConnection();
            $this->squads = Squad::getAllSquads($this->pdo);
        }
    }

?>