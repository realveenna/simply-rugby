<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Guardian
    {
        private $guardian_id;
        protected $application_id;
        protected $member_id;
        protected $address_id;
        protected $contact_member_id;
        private $first_name;
        private $last_name;
        private $relationship;
        private $mobile_number;
        private $is_primary;
        private $apply_coach;
        private $email; 
        private $access_level; 

        public function __construct($data, $memberId = null)
        {
            $this->application_id = $data['application_id'] ?? '';
            $this->member_id = $data['member_id'] ?? $memberId ?? '';
            $this->address_id = $data['address_id'] ?? null;
            $this->contact_member_id = $data['contact_member_id'] ?? '';
            $this->first_name = $data['first_name'] ?? '';
            $this->last_name = $data['last_name'] ?? '';
            $this->relationship = $data['relationship'] ?? '';
            $this->mobile_number = $data['mobile_number'] ?? '';
            $this->is_primary = $data['is_primary'] ?? 0;
            $this->apply_coach = $data['apply_coach'] ?? 0;
            $this->email = $data['email'] ?? null;
            $this->access_level = $data['access_level'] ?? 'No Access';
        }
        public function checkGuardianApplicationExists($pdo)
        {
            $statement = $pdo->prepare(
                "SELECT guardian_id FROM application_guardian
                WHERE email = :email 
                    AND mobile_number = :mobile_number 
                    AND is_primary = 1
                LIMIT 1"
            );
            $statement->execute([
                ':email' => $this->email,
                ':mobile_number' => $this->mobile_number
            ]);
            return $statement->fetch(PDO::FETCH_COLUMN);
        }

        public function insertGuardianApplication($pdo)
        {
            try{
                // // Check if the email already exists in the logins table
                // $isMember = User::checkEmailExists($this->email, "This email has an existing registration with another player");
                        
                // if($isMember['exists']){
                //     throw new \Exception("This email is already a registered member. Please log in.");
                // }

                $statement = $pdo->prepare(
                    "INSERT INTO application_guardian 
                        (address_id, first_name,last_name,
                        relationship,mobile_number,is_primary, email, apply_coach)
                    VALUES (:address_id, :first_name, :last_name,
                        :relationship, :mobile_number, :is_primary, :email, :apply_coach)
                    ON DUPLICATE KEY UPDATE 
                        guardian_id = LAST_INSERT_ID(guardian_id),
                        address_id = VALUES(address_id), 
                        first_name = VALUES(first_name), 
                        last_name = VALUES(last_name), 
                        apply_coach = VALUES(apply_coach)
                    ;"
                );

                $statement->execute([
                    ':address_id' => $this->address_id,
                    ':first_name' => $this->first_name,
                    ':last_name' => $this->last_name,
                    ':relationship' => $this->relationship,
                    ':mobile_number' => $this->mobile_number,
                    ':is_primary' => $this->is_primary,
                    ':email' => $this->email ?? null,
                    ':apply_coach' => $this->apply_coach
                ]);

                // Set the guardian ID after successful insertion
                $guardianId = $pdo->lastInsertId();

                if ($guardianId <= 0) {
                    throw new \Exception('Failed to get guardian ID.');
                }
                return $guardianId;
            }
            // If guardian exists, then fetch the guardian_id    
            catch(\PDOException $e){
                $sql = "SELECT guardian_id FROM application_guardian 
                        WHERE first_name = :first_name AND last_name = :last_name AND
                            mobile_number = :mobile_number AND is_primary = :is_primary
                            AND email = :email";

                $params = [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'mobile_number' => $this->mobile_number,
                    'is_primary' => $this->is_primary,
                    'email' => $this->email
                ];

                // Return the guardian_id if the guardian already exists
                $guardianId = Database::errorFetchId($pdo, $sql, $params, $e);
                return $guardianId;
            }
        }

        // Insert to player_contact
        public function insertPlayerContact($pdo)
        {
            try{
                $statement = $pdo->prepare(
                    "INSERT INTO player_contact (member_id,contact_member_id,relationship,is_primary,access_level) 
                    VALUES (:member_id, :contact_member_id, :relationship, :is_primary, :access_level)"
                );

                $statement->execute([
                    ':member_id' => $this->member_id,
                    ':contact_member_id' => $this->contact_member_id,
                    ':relationship' => $this->relationship,
                    ':is_primary' => $this->is_primary,
                    ':access_level' => $this->access_level ?? 'None'
                ]);

                if($statement->rowCount() === 0){
                    throw new \Exception("Failed to insert player contact details.");
                }
            }
            catch(\PDOException $e){
                throw new \Exception("Database error: " . $e->getMessage());
            }
            return true;
        }
        

        // Get application primary guardians by application id
        public static function getPrimaryApplicationGuardians($pdo, $guardian_id , $is_primary = 1){
            try{
                $statement = $pdo->prepare(
                    "SELECT * FROM application_guardian
                    LEFT JOIN address ON application_guardian.address_id = address.address_id
                    WHERE application_guardian.guardian_id = :guardian_id AND application_guardian.is_primary = :is_primary"
                );

                $statement->execute([
                    ':guardian_id' => $guardian_id,
                    ':is_primary' => $is_primary
                ]);

                $result = $statement->fetch(PDO::FETCH_ASSOC);

                if(!$result){
                    return null;
                }
                return $result;
            }
            catch(\PDOException $e){
                alert('error', 'There is a database error in fetching nok/primary guardians.', '/player-applications');
            }
        }
        // Get application secondary guardians by application id
        public static function getSecondaryApplicationGuardians($pdo, $guardian_id, $is_primary = 0){
            try{
                $statement = $pdo->prepare(
                "SELECT * FROM application_guardian
                JOIN address ON application_guardian.address_id = address.address_id
                WHERE application_guardian.guardian_id = :guardian_id AND application_guardian.is_primary = :is_primary"
                );

                $statement->execute([
                    ':guardian_id' => $guardian_id,
                    ':is_primary' => $is_primary
                ]);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if(!$result){
                    return null;
                }
                return $result;
            }
            catch(\PDOException $e){
                alert('error', 'There is a database error in fetching secondary guardians.', '/player-applications');
            }
        }
        
        public static function getPlayerGuardian($pdo, $member_id, $is_primary){
             $statement = $pdo->prepare(
                "SELECT
                    pc.member_id AS player_member_id,
                    pc.contact_member_id AS guardian_member_id,
                    pc.relationship,
                    pc.is_primary,
                    pc.access_level,

                    m.first_name,
                    m.last_name,
                    m.dob,
                    m.mobile_num,
                    m.email,
                    m.membership_status,

                    a.*
                FROM player_contact pc
                JOIN member m ON m.member_id = pc.contact_member_id
                lEFT JOIN address a ON m.address_id = a.address_id
                WHERE pc.member_id = :member_id 
                AND pc.is_primary = :is_primary
                
                LIMIT 1"
            );

            $statement->execute([
                ':member_id' => $member_id,
                'is_primary' => $is_primary
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }
?>