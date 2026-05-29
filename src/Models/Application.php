<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Application
    {
        public function __construct()
        {

        }

        public static function checkApplicationStatus($pdo, $application_id)
        {
            $statement = $pdo->prepare(
                "SELECT application_status
                FROM player_application
                WHERE application_id = :application_id"
            );

            $statement->execute([
                ':application_id' => $application_id
            ]);

            return $statement->fetch(PDO::FETCH_COLUMN);
        }

        public static function updateStatus($pdo, $application_id, $new_status)
        {
            $statement = $pdo->prepare(
                "UPDATE player_application
                SET application_status = :new_status
                WHERE application_id = :application_id"
            );

            $updated = $statement->execute([
                ':new_status' => $new_status,
                ':application_id' => $application_id
            ]);

            // Check if the update was successful
            if (!$updated) {
                throw new \Exception('Failed to update application status.');
            }
            return true;
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
               
                -- Prevent duplicate registration
                ON DUPLICATE KEY UPDATE 
                    application_id = LAST_INSERT_ID(application_id),
                    address_id  = VALUES(address_id),
                    nickname = VALUES(nickname),
                    playerHeight = VALUES(playerHeight),
                    playerWeight = VALUES(playerWeight),
                    doctor_id = VALUES(doctor_id),
                    mobile_num = VALUES(mobile_num),
                    primary_guardian_id = VALUES(primary_guardian_id),
                    secondary_guardian_id = VALUES(secondary_guardian_id),
                    recommended_squad = VALUES(recommended_squad)
                ");

            $statement->execute([
                ':application_status' => 'applied',
                ':fName' => ucwords($data['fName']),
                ':lName' => ucwords($data['lName']),
                ':dob' => $data['dob'],
                ':address_id' => $data['address_id'],
                ':nickname' => ucfirst($data['playerNickname']),
                ':playerHeight' => $data['playerHeight'],
                ':playerWeight' => $data['playerWeight'],
                ':email' => strtolower($data['email']) ?? '',
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
                ORDER BY submitted_on DESC"
            );

            $statement->execute();
            $allApplications = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $allApplications;
        }


        // Get a specific application by ID
        public static function getApplicationById($pdo, $application_id)
        {
            try{
                $statement = $pdo->prepare(
                    "SELECT application_id FROM player_application
                    WHERE application_id = :application_id"
                );

                $statement->execute([':application_id' => $application_id]);
                $application_id = $statement->fetchColumn();

                return $application_id;

            } catch (\PDOException $e) {
                // Handle any database errors
                alert('error', 'An error occurred while fetching list of players application details', 'player-applications');
                return null; 
            }
        }
        // View all player applications details
        public static function getPlayerApplicationDetails($pdo, $application_id)
        {
            $statement = $pdo->prepare(
                "SELECT * FROM player_application
                WHERE player_application.application_id = :application_id
                ORDER BY submitted_on DESC");

            $statement->execute([':application_id' => $application_id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if(!$result){
                alert('error', 'Application details not found', '/player-applications');
                return null; 
            }
            return $result;
        }
    }
?>