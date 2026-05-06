<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Doctor
    {
        public $doctor_id;
        public $doctor_name;
        public $doctor_tel;
        public $address_id;

        public function __construct()
        {

        }
        public static function insert($pdo, $doctorData)
        {
           try{
                $pdo = Database::getInstance()->getConnection();
                
                $statement = $pdo->prepare
                    ("INSERT INTO doctor(doctor_name, doctor_tel, address_id) 
                    VALUES (:doctor_name, :doctor_tel, :address_id)");

                $statement->execute([
                    ':doctor_name' => $doctorData['doctor_name'],
                    ':doctor_tel' => $doctorData['doctor_tel'],
                    ':address_id' => $doctorData['address_id']
                ]);
                
                return $pdo->lastInsertId();
           }
           catch(\PDOException $e){
                $sql = "SELECT doctor_id FROM doctor 
                        WHERE doctor_tel = :doctor_tel";

                $params = [
                    'doctor_tel' => $doctorData['doctor_tel']
                ];

                return Database::errorFetchId($pdo, $sql, $params, $e);
            }
        }   
       
    }
?>