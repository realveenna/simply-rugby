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
            $this->address_id = null;
            $this->line_1 = '';
            $this->line_2 = '';
            $this->city = '';
            $this->postcode = '';
            $this->country = '';
        }
        
        // Get address by ID
        public static function getAddressDetails($pdo, $address_id)
        {
            $statement = $pdo->prepare("SELECT * FROM address WHERE address_id = :address_id");
            $statement->execute([':address_id' => $address_id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if(!$result){
                return null;
            }

            return $result;
        }

        // Insert address to database
        public static function insert($pdo, $data)
        {
            try{
                // Insert address to database
                $statement = $pdo->prepare
                    ("INSERT INTO address(line_1, line_2, city, postcode, country) 
                    VALUES (:line1, :line2, :city, :postcode, :country)");
                
                $statement->execute([
                    ':line1' => ucwords($data['line1']),
                    ':line2' => ucwords($data['line2']),
                    ':city' => $data['city'],
                    ':postcode' => strtoupper($data['postcode']),
                    ':country' => $data['country']
                ]);
                return $pdo->lastInsertId();
            }
            // If address already exists, fetch the existing address_id
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