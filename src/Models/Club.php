<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Club
    {
        public $name;
        public $line1;
        public $city;
        public $zipcode;
        public $country;
     
        public function __construct()
        {
            $this->name = "Simply Rugby";
            $this->line1 = "19 Hatfield Drive";
            $this->city = "Glasgow";
            $this->zipcode = "G12 0YE";
            $this->country = "United Kingdom";
        }
    }
?>