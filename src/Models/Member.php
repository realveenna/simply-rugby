<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Member
    {
        public $member_id;
        public $first_name;
        public $last_name;
        public $dob;
        public $address_id;
        public $mobile_num;
        public $membership_status;
        public $profile_image;

        public $email;
        protected $password;

        public $allNoLogin = [];


        public function __construct()
        {
            $this->member_id = null;
            $this->first_name = '';       
            $this->last_name = '';
            $this->dob = '';
            $this->address_id = null;
            $this->mobile_num = '';
            $this->membership_status = '';
            $this->profile_image = '';
            $this->email = '';
            $this->password = '';
        }

        // Get member_id 
        private function getMemberId()
        {
            return $this->member_id;
        }
        public function registerPlayer($email, $rawPassword)
        {
     

        }
        
        // Get all members that do not have a login account
        public static function selectAllNoLogin()
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "SELECT * FROM member m
                LEFT JOIN logins l ON m.member_id = l.member_id
                WHERE l.member_id IS NULL"
            );

            $statement->execute();
            return  $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Select all members with login accounts
        public static function selectAll()
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "SELECT * FROM member"
            );

            $statement->execute();
            return  $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function validateInsert($pdo){
            // Check if member already exist 
            $statement = $pdo->prepare(
                "SELECT member_id FROM member 
                WHERE first_name = :fname AND last_name = :lname AND dob = :dob 
                AND mobile_num = :mobileNum
                LIMIT 1"
            );
            $statement->execute([
                ':fname' => $this->first_name,
                ':lname' => $this->last_name,
                ':dob' => $this->dob,
                ':mobileNum' => $this->mobile_num
            ]);
            return $statement->fetchColumn();
        }
     
        // Insert a new member to the database
        public function insert($pdo)
        {
            // Insert member to database
            $statement = $pdo->prepare
                ("INSERT INTO member(first_name, last_name, dob, address_id, mobile_num, membership_status, email) 
                VALUES (:fname, :lname, :dob, :address_id, :mobileNum, :membershipStatus, :email)");
            
                $statement->execute([
                    ':fname' => $this->first_name,
                    ':lname' => $this->last_name,
                    ':dob' => $this->dob,
                    ':address_id' => $this->address_id,
                    ':mobileNum' => $this->mobile_num,
                    ':membershipStatus' => $this->membership_status,
                    ':email' => $this->email ?? null
                ]);

            $this->member_id = $pdo->lastInsertId();
            return $this->member_id;
        }

        public static function selectEmail($member_id)
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "SELECT email FROM member WHERE member_id = :member_id LIMIT 1"
            );

            $statement->execute([':member_id' => $member_id]);
            return $statement->fetchColumn();
        }
        public static function selectIdByEmail($email)
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "SELECT member_id FROM member WHERE email = :email LIMIT 1"
            );

            $statement->execute([':email' => $email]);
            return $statement->fetchColumn();
        }

        // Get all member details
         public static function getMemberDetails($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT * FROM member
                WHERE member_id = :member_id");

            $statement->execute([':member_id' => $member_id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return $result;
           
        }
    }
?>