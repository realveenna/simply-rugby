<?php
namespace Test;
use Test\Database;

    class Base 
    {
        protected $pdo;

        // Base class that construct the signleton pdo connection
        public function __construct()
        {
            $this->pdo = Database::getInstance()->getConnection() ?? null ;
        }
    }
?>