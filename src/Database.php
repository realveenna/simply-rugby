<?php
    namespace Test;
    
    use PDO;
    use Exception;

   class Database {
    // Store the single instance
        private static ?Database $instance = null;
        
        // Database connection object
        private PDO $conn;
        private function __clone() {}
        public function __wakeup() {}
        
        // Private constructor to prevent direct instantiation
        private function __construct()
        {
            //Set variables for the Server Name / Address, Database Name and Username & Password details.
            $my_host = "localhost";
            $my_db = 'simply_rugby_new';
            $my_db_username = "root";
            $my_db_passwd = "";

            try {
                $this->conn = new PDO("mysql:host=$my_host;dbname=$my_db;charset=utf8mb4", $my_db_username, $my_db_passwd);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                if(!$this->conn){
                    die("Database connection can not be established.");
                }
            } catch (Exception $ex) {
                die($ex->getMessage());
            }
        }
        // The method to get the singleton instance
        public static function getInstance(): ?Database
        {
            if (self::$instance === null) {
                self::$instance = new Database();
            }

            return self::$instance;
        }

        // Get the database connection
        public function getConnection(): PDO
        {
            return $this->conn;
        }

        // Catch block if there is a unique constrant violation
        public static function errorFetchId($pdo, $sql, $params, $e){
            // 23000 is the SQLSTATE for integrity constraint violations
            if ($e->getCode() == '23000') {

                // return address_id
                $id = $pdo->prepare($sql);
                $id->execute($params);
                return $id->fetchColumn();
                
            } else {
                // Re-throw exception if it's a different error
                throw $e;
            }
        }
   }
   
?>