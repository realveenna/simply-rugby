<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Doctor
    {
        private $doctor_id;
        public $doctor_name;
        public $doctor_tel;
        public $address_id;

        public function __construct($data)
        {
            $this->doctor_id = $data['doctor_id'] ?? null;
            $this->doctor_name = $data['doctor_name'];
            $this->doctor_tel = $data['doctor_tel'];
            $this->address_id = $data['address_id'];
        }
        public function insert($pdo)
        {
           try{
                $statement = $pdo->prepare
                    ("INSERT INTO doctor(doctor_name, doctor_tel, address_id) 
                    VALUES (:doctor_name, :doctor_tel, :address_id)");

                $statement->execute([
                    ':doctor_name' => $this->doctor_name,
                    ':doctor_tel' => $this->doctor_tel,
                    ':address_id' => $this->address_id,
                ]);
                
                return $pdo->lastInsertId();
           }
           catch(\PDOException $e){
                $sql = "SELECT doctor_id FROM doctor 
                        WHERE doctor_tel = :doctor_tel";

                $params = [
                    'doctor_tel' => $this->doctor_tel
                ];
                
                // Return the doctor_id if the doctor already exists
                $this->doctor_id = Database::errorFetchId($pdo, $sql, $params, $e);
                return $this->doctor_id;
            }
        }   
        public function getDoctorId()
        {
            return $this->doctor_id;
        }
       
    }
?>