<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Application
    {
        private $application_id;
        private $address_id;
        private $doctor_id;

        private $application_status;
        private $submitted_on;
        private $applicant_first_name;  
        private $applicant_last_name;
        private $applicant_dob;
        private $nickname;
        private $playerHeight;
        private $playerWeight;
        private $email;
        private $mobile_num;

     
        public function __construct()
        {

        }

        // Insert Player Applications
        public static function insert($pdo, $data)
        {
            $statement = $pdo->prepare(
                "INSERT INTO player_application
                    (application_status, applicant_first_name, applicant_last_name, applicant_dob, 
                    address_id, nickname, playerHeight, playerWeight, email, doctor_id, mobile_num,
                    primary_guardian_id, secondary_guardian_id, recommended_squad) 
                VALUES 
                    (:application_status, :fName, :lName, :dob, :address_id, :nickname, :playerHeight, :playerWeight, 
                    :email, :doctor_id, :mobile, :primary_guardian_id, :secondary_guardian_id, :recommended_squad)
                ON DUPLICATE KEY UPDATE 
                    application_id = LAST_INSERT_ID(application_id),
                    address_id  = VALUES(address_id),
                    nickname = VALUES(nickname),
                    playerHeight = VALUES(playerHeight),
                    playerWeight = VALUES(playerWeight),
                    mobile_num = VALUES(mobile_num),
                    primary_guardian_id = VALUES(primary_guardian_id),
                    secondary_guardian_id = VALUES(secondary_guardian_id),
                    recommended_squad = VALUES(recommended_squad)
                ");

            $statement->execute([
                ':application_status' => 'applied',
                ':fName' => $data['fName'],
                ':lName' => $data['lName'],
                ':dob' => $data['dob'],
                ':address_id' => $data['address_id'],
                ':nickname' => $data['playerNickname'],
                ':playerHeight' => $data['playerHeight'],
                ':playerWeight' => $data['playerWeight'],
                ':email' => $data['email'] ?? '',
                ':doctor_id' => $data['doctor_id'],
                ':mobile' => $data['mobileNum'],
                ':primary_guardian_id' => $data['primary_guardian_id'],
                ':secondary_guardian_id' => $data['secondary_guardian_id'],
                ':recommended_squad' => $data['recommended_squad']
            ]);

            return $pdo->lastInsertId(); 
        }

        // Get all applications
        public static function getPlayerApplications()
        {
            $pdo = Database::getInstance()->getConnection();
            
            $statement = $pdo->prepare(
                "SELECT 
                -- Table
                    player_application.*, 
                    address.*,

                -- Primary Guardian Alias
                    primary_guardian.first_name AS primary_fname,
                    primary_guardian.last_name AS primary_lname,
                    primary_guardian.mobile_number AS primary_mobile,

                -- Secondary Guardian Alias
                    secondary_guardian.first_name AS secondary_fname,
                    secondary_guardian.last_name AS secondary_lname,
                    secondary_guardian.mobile_number AS secondary_mobile

                FROM player_application

                -- Joins
                JOIN address 
                        ON player_application.address_id = address.address_id
                    LEFT JOIN application_guardian AS primary_guardian
                        ON player_application.primary_guardian_id = primary_guardian.guardian_id
                    LEFT JOIN application_guardian AS secondary_guardian
                        ON player_application.secondary_guardian_id = secondary_guardian.guardian_id
                "
            );

            $statement->execute();
            $allApplications = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $allApplications;
        }


        // Get a specific application by ID
        public static function getApplicationById($pdo, $application_id)
        {
            $statement = $pdo->prepare(
                "SELECT * FROM player_application
                WHERE player_application.application_id = :application_id"
            );

            $statement->execute([':application_id' => $application_id]);
            $application_id = $statement->fetch(PDO::FETCH_ASSOC);

            return $application_id;
        }



    }
?>