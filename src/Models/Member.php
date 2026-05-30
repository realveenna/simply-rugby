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
        
        // Get all members that has email but do not have a login account 
        public static function selectAllNoLogin()
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "SELECT m.* FROM member m
                LEFT JOIN logins l ON m.member_id = l.member_id
                WHERE l.member_id IS NULL AND m.email IS NOT NULL"
            );

            $statement->execute();
            return  $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Select all members no senior nok
        public static function selectAll($role = null)
        {
            $pdo = Database::getInstance()->getConnection();

            $sql = "
                 SELECT 
                    m.*,
                    GROUP_CONCAT(r.role_name SEPARATOR ', ') AS roles
                FROM member m

                LEFT JOIN member_role mr  ON m.member_id = mr.member_id

                LEFT JOIN role r ON mr.role_id = r.role_id
            ";

            // Filter by role
            if($role === 'Admin'){
                $sql .= "
                    WHERE r.role_name IN (
                        'Section Secretary',
                        'Membership Secretary',
                        'Fixture Secretary',
                        'Club Chairperson'
                    )
                ";
            }
            else if($role){
                $sql .= "
                    WHERE r.role_name = :role
                ";
            }

            $sql .= "
                GROUP BY m.member_id
            ";

            $statement = $pdo->prepare($sql);

            // Execute with or without role filter
            if($role && $role !== 'Admin'){
                $statement->execute([
                    ':role' => $role
                ]);
            }
            else{
                $statement->execute();
            }

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function validateInsert($pdo){
            // Check if member already exist 
            $statement = $pdo->prepare(
                "SELECT member_id FROM member 
                WHERE mobile_num = :mobile_num AND email = :email
                LIMIT 1"
            );
            $statement->execute([
                ':mobile_num' => $this->mobile_num,
                ':email' => $this->email,
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

        // update member details
        public function update($pdo)
        {
            $statement = $pdo->prepare(
                "UPDATE member
                SET
                    first_name = :fname,
                    last_name = :lname,
                    dob = :dob,
                    address_id = :address_id,
                    mobile_num = :mobileNum,
                    membership_status = :membershipStatus,
                    email = :email

                WHERE member_id = :member_id"
            );

            return $statement->execute([
                ':fname' => $this->first_name,
                ':lname' => $this->last_name,
                ':dob' => $this->dob,
                ':address_id' => $this->address_id ?? null,
                ':mobileNum' => $this->mobile_num,
                ':membershipStatus' => $this->membership_status ?? 'active',
                ':email' => $this->email ?? null,
                ':member_id' => $this->member_id
            ]);
        }

        // Delete a member
        public static function delete($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "DELETE FROM member
                WHERE member_id = :member_id"
            );

            return $statement->execute([
                ':member_id' => $member_id
            ]);
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

        // Get member details
        public static function getMemberDetails($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT
                    m.*,
                    GROUP_CONCAT(DISTINCT r.role_name SEPARATOR ', ') AS roles,
                    -- Group concat since members can have multiple roles
                    GROUP_CONCAT(DISTINCT sq.squad_name SEPARATOR ', ') AS squads,
                    GROUP_CONCAT(DISTINCT COALESCE(s.section_name, sec_ad.section_name)SEPARATOR ', ') AS sections
                FROM member m
                
                LEFT JOIN member_role mr ON m.member_id = mr.member_id
                LEFT JOIN role r ON mr.role_id = r.role_id

                -- Squad_Member Joins
                LEFT JOIN squad_member sm ON m.member_id = sm.member_id
                LEFT JOIN squad sq ON sm.squad_id = sq.squad_id
                LEFT JOIN section s ON sq.section_id = s.section_id

                -- Section Admin Joins
                LEFT JOIN section_admin sa ON m.member_id = sa.member_id
                LEFT JOIN section sec_ad ON sa.section_id = sec_ad.section_id

                WHERE m.member_id = :member_id
                GROUP BY m.member_id"
            );

            $statement->execute([':member_id' => $member_id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        // Get all members by role
        public static function getMembersByRole($pdo, $role_name)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    m.member_id,
                    m.first_name,
                    m.last_name,
                    m.email
                FROM member m

                JOIN member_role mr ON m.member_id = mr.member_id
                JOIN role r ON mr.role_id = r.role_id

                WHERE r.role_name = :role_name
                AND m.email IS NOT NULL
                ORDER BY m.first_name ASC"
            );

            $statement->execute([
                ':role_name' => $role_name
            ]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);  
        }

        // Update membership status
        public static function updateMembershipStatus($pdo, $member_id, $status)
        {
            $statement = $pdo->prepare(
                "UPDATE member
                SET membership_status = :membership_status
                WHERE member_id = :member_id"
            );

            return $statement->execute([
                ':membership_status' => $status ?? 'Active',
                ':member_id' => $member_id
            ]);
        }

    }
?>