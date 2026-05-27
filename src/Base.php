<?php
namespace Test;
use Test\Database;
use Test\Models\AccessControl;
use Test\Models\Squad;

    class Base 
    {
        protected $pdo;

        public function __construct()
        {
            $this->pdo = Database::getInstance()->getConnection() ?? null ;
        }
    }
?>