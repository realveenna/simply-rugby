<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class User
    {
        public $member_id;
        public $email;
        public $password;
        public $role;
        
        
        public function __construct()
        {
            $this->member_id = null;
            $this->email = '';
            $this->password = '';
            $this->role = [];
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

        // Find a member by email
        public static function findEmail($email)
        {
            $pdo = Database::getInstance()->getConnection();
            
            $statement = $pdo->prepare("SELECT email FROM member WHERE email = :email LIMIT 1");
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
            
            return $statement->fetch(PDO::FETCH_ASSOC);
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
        public static function insertLogin($pdo, $data)
        {
            $pdo = Database::getInstance()->getConnection();

            //Pass the variable values to be inserted into the database
            $statement = $pdo->prepare("INSERT INTO logins(member_id, pass)
                VALUES (:member_id, :pass)");

            $statement->bindValue(':member_id', $data['member_id'], PDO::PARAM_INT);
            $statement->bindValue(':pass', $data['password'], PDO::PARAM_STR);

            return $statement->execute();
        }

        public static function insertRole($pdo, $data)
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare("INSERT INTO member_role(member_id, role_id)
                VALUES (:member_id, :role_id)");

            $statement->bindValue(':member_id', $data['member_id'], PDO::PARAM_INT);
            $statement->bindValue(':role_id', $data['selectedRole'], PDO::PARAM_INT);

            return $statement->execute();
        }
    }
?>