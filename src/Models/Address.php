<?php
    namespace Test\Models;

    use Exception;
    use Test\Database;
    use PDO;
    
    class Address
    {
        private $address_id;
        private $line_1;
        private $line_2;
        private $city;
        private $postcode;
        private $country;

        public function __construct()
        {

        }
        public static function insert($pdo, $data)
        {
            $pdo = Database::getInstance()->getConnection();
            
            try{
                // Insert address to database
                $statement = $pdo->prepare
                    ("INSERT INTO address(line_1, line_2, city, postcode, country) 
                    VALUES (:line1, :line2, :city, :postcode, :country)");
                
                $statement->execute([
                    ':line1' => $data['line1'],
                    ':line2' => $data['line2'],
                    ':city' => $data['city'],
                    ':postcode' => $data['postcode'],
                    ':country' => $data['country']
                ]);
                return $pdo->lastInsertId();
            }
            catch(\PDOException $e){
                $sql = "SELECT address_id FROM address 
                        WHERE line_1 = :line1 AND postcode = :postcode";

                $params = [
                    'line1' => $data['line1'],
                    'postcode' => $data['postcode']
                ];

                return Database::errorFetchId($pdo, $sql, $params, $e);
            }
        }
    }
?>