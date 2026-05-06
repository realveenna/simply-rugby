<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Guardian
    {
        public $application_id;
        public $address_id;
        public $first_name;
        public $last_name;
        public $relationship;
        public $mobile_number;
        public $is_primary;

        public function __construct($data)
        {
            $this->application_id = $data['application_id'];
            $this->address_id = $data['address_id'];
            $this->first_name = $data['first_name'];
            $this->last_name = $data['last_name'];
            $this->relationship = $data['relationship'];
            $this->mobile_number = $data['mobile_number'];
            $this->is_primary = $data['is_primary'];
        }

        public function insertGuardianApplication()
        {
           try{
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                "INSERT INTO application_guardian 
                    (application_id, address_id, first_name,last_name,
                    relationship,mobile_number,is_primary)
                VALUES (:application_id, :address_id, :first_name, :last_name,
                    :relationship, :mobile_number, :is_primary)"
            );

            $statement->execute([
                ':application_id' => $this->application_id,
                ':address_id' => $this->address_id,
                ':first_name' => $this->first_name,
                ':last_name' => $this->last_name,
                ':relationship' => $this->relationship,
                ':mobile_number' => $this->mobile_number,
                ':is_primary' => $this->is_primary,
            ]);
            return $pdo->lastInsertId();
            
           }
           catch(\PDOException $e){
                $sql = "SELECT guardian_id FROM application_guardian 
                        WHERE doctor_tel = :doctor_tel";

                $params = [
                    'doctor_tel' => $doctorData['doctor_tel']
                ];

                return Database::errorFetchId($pdo, $sql, $params, $e);
            }
        }
    }

?>