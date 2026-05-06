<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class User
    {
        public $member_id;
        public $email;

        public function __construct()
        {

        }
        public static function registerPlayer($email, $rawPassword)
        {
            
        }

        public static function validate($email, $rawPassword)
        {
           $member = self::findEmail($email);

            // Email is not found
            if (!$member) {
                return [
                    'success' => false,
                    'emailError' => 'Email is not registered.'
                ];
            }

            // Check password
            $salt ="4g£yc7!L(";
            $password = md5($rawPassword.$salt);

            if($member['pass'] === $password){
                return [
                    'success' => true,
                    'member' => $member
                ];
            }
            else{
                return [
                    'error' => false,
                    'passErr' => 'Incorrect Password.'
                ];
            }
        }

        public static function findEmail($email)
        {
            $pdo = Database::getInstance()->getConnection();
            
            $statement = $pdo->prepare("SELECT * FROM logins WHERE email = :email LIMIT 1");
            $statement->bindValue(':email', $email, PDO::PARAM_STR);
            $statement->execute();
            // return TRUE or FALSE
            return $statement->fetch(PDO::FETCH_ASSOC);

        }
        public static function checkEmailExists($email)
        {
            $member = self::findEmail($email);

            if ($member) {
                return [
                    'exists' => true,
                    'emailError' => 'Email already registered. Please log in.'
                ];
            }

            return [
                'exists' => false
            ];
        }
        public static function create($email, $password){
            $pdo = Database::getInstance()->getConnection();

            $member_id = 4;

            //Pass the variable values to be inserted into the database
            $statement = $pdo->prepare("INSERT INTO logins(member_id, email, pass)
                VALUES (:member_id, :email, :pass)");

            $statement->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $statement->bindValue(':email', $email, PDO::PARAM_STR);
            $statement->bindValue(':pass', $password, PDO::PARAM_STR);

            // return TRUE or FALSE
            return $statement->execute();
        }
        public static function applySenior($applicant_first_name, $applicant_last_name, 
            $applicant_dob, $address_id, $nickname, $height, $weight, $email, $doctor_id, $allergies, 
            $current_medical_condition, $medical_history){
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

            // return TRUE or FALSE
            return $statement->execute();
        }
        public static function applyJunior($data = []){
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