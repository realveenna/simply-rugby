<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class User
    {
        public $member_id;
        public $email;
        public $member_name;
        public $password;
        
        
        public function __construct()
        {
 
        }

        public static function registerPlayer($email, $rawPassword)
        {
            
        }

        public static function validate($email, $rawPassword)
        {
            
        }

        // Find a member by email
        public static function findEmail($email)
        {
            $pdo = Database::getInstance()->getConnection();
            
            $statement = $pdo->prepare("SELECT * FROM member WHERE email = :email LIMIT 1");
            $statement->bindValue(':email', $email, PDO::PARAM_STR);
            $statement->execute();

            // Returns member details if email exists
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        // Find a member login details by member_id
        public static function findMemberLogin($pdo, $member_id)
        {
            $pdo = Database::getInstance()->getConnection();
        
            $statement = $pdo->prepare("SELECT * FROM logins WHERE member_id = :member_id LIMIT 1");
            $statement->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $statement->execute();
            $result =  $statement->fetch(PDO::FETCH_ASSOC);
            if(!$result){
                return null;
            }
            return $result;
        }
        
        // Update Password
        public static function updatePassword($pdo, $member_id, $newPass)
        {
            $statement = $pdo->prepare
            (
                "UPDATE logins SET pass = :newPass
                WHERE member_id = :member_id LIMIT 1"
            );
            return $statement->execute([
                'member_id' => $member_id,
                'newPass' => $newPass
            ]);

        }

        // // Check Member Login Credentials
        public static function getCredentials($member_id)
        {
            $pdo = Database::getInstance()->getConnection();
        
            $statement = $pdo->prepare(
                "SELECT 
                    logins.*, 
                    member.*
                FROM logins 
                JOIN member ON member.member_id = logins.member_id
                WHERE logins.member_id = :member_id LIMIT 1");

            $statement->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $statement->execute();
            $result =  $statement->fetch(PDO::FETCH_ASSOC);
            if(!$result){
                return null;
            }
            return $result;
        }
     
        // Check if email already exists in the database and display error message
        public static function checkEmailExists($email, $message)
        {
            $member = self::findEmail($email);

            if ($member) {
                return [
                    'exists' => true,
                    'emailError' => $message
                ];
            }

            return [
                'exists' => false
            ];
        }

        // Create a new login for a member in the database
        // User must be already a member
        public static function insertNewMemberLogin($pdo, $member_id, $password){

            //Pass the variable values to be inserted into the database
            $statement = $pdo->prepare("INSERT INTO logins(member_id, pass)
                VALUES (:member_id, :pass)");

            $statement->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $statement->bindValue(':pass', $password, PDO::PARAM_STR);

            return $statement->execute();
        }
    }
?>