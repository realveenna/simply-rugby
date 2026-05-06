<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Application
    {
     
        public function __construct()
        {

        }
        public static function insert($pdo, $data)
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "INSERT INTO player_application
                    (application_status, applicant_first_name, applicant_last_name, applicant_dob, 
                    address_id, nickname, playerHeight, playerWeight, email, doctor_id, mobile_num) 
                VALUES 
                    (:application_status, :fName, :lName, :dob, :address_id, :nickname, :playerHeight, :playerWeight, 
                    :email, :doctor_id, :mobile)");

            $statement->execute([
                ':application_status' => 'applied',
                ':fName' => $data['fName'],
                ':lName' => $data['lName'],
                ':dob' => $data['dob'],
                ':address_id' => $data['address_id'],
                ':nickname' => $data['playerNickname'],
                ':playerHeight' => $data['playerHeight'],
                ':playerWeight' => $data['playerWeight'],
                ':email' => $data['email'],
                ':doctor_id' => $data['doctor_id'],
                ':mobile' => $data['mobileNum']
            ]);
            return $pdo->lastInsertId(); 
        }
        
        public static function applySenior($data){
            $pdo = Database::getInstance()->getConnection();

            //Pass the variable values to be inserted into the database
            $statement = $pdo->prepare
                ("INSERT INTO `player_application`(
                    `application_status`, , `applicant_first_name`, 
                    `applicant_last_name`, `applicant_dob`, `address_id`, `nickname`, `height`, `weight`, 
                    `email`, `doctor_id`, `allergies`, `current_medical_condition`, `medical_history`) 
                VALUES 
                    ('applied','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]',
                    '[value-8]','[value-9]','[value-10]','[value-11]','[value-12]','[value-13]',
                    '[value-14]')
            ");

            //  Execute
        }
        public static function applyJunior($data){
            $pdo = Database::getInstance()->getConnection();

            //Pass the variable values to be inserted into the database
            $statement = $pdo->prepare("INSERT INTO logins(member_id, email, pass)
                VALUES (:member_id, :email, :pass)
            ");

            // $statement->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            // $statement->bindValue(':email', $email, PDO::PARAM_STR);
            // $statement->bindValue(':pass', $password, PDO::PARAM_STR);

            // return TRUE or FALSE
            return $statement->execute();
        }
    }
    
?>