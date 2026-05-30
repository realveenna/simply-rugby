<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class MedicalInformation
    {

        // View Medical Condition Table
        public static function viewAllCondition()
        {
            // $DB = new Database();
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare("SELECT * FROM medical_condition");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

         // View Allergy Table
        public static function viewAllAllergy()
        {
            // $DB = new Database();
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare("SELECT * FROM allergy");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Inserts member or member_application condition to their bridge table
        public static function insertConditionApplication($pdo, $data)
        {
            // $DB = new Database();
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare
            (
                "INSERT INTO application_condition(application_id, condition_id, condition_status) 
                VALUES (:application_id, :condition_id, :condition_status)
                ON DUPLICATE KEY UPDATE condition_id = condition_id"
            );
            foreach($data as $d){
                $statement->execute([
                    ':application_id' => $d['application_id'],
                    ':condition_id' => (int)$d['condition_id'],
                    ':condition_status' => $d['condition_status']
                ]);
            }
            return true;
        }

        public static function insertAllergyApplication($pdo, $allergyData, $application_id)
        {
            // $DB = new Database();
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare(
                "INSERT INTO application_allergy(application_id, allergy_id) 
                VALUES (:application_id, :allergy_id) 
                ON DUPLICATE KEY UPDATE allergy_id = allergy_id"
            );

            foreach($allergyData as $allergy){
                $statement->execute([
                    ':application_id' => $application_id,
                    ':allergy_id' => $allergy
                ]);
            }
            return true;
        }


        // Inserts player condition to their bridge table
        public static function copyToPlayerCondition($pdo, $application_id, $member_id)
        {
            $statement = $pdo->prepare
            (
                "INSERT IGNORE INTO player_condition(member_id, condition_id, condition_status) 
                SELECT :member_id, condition_id, condition_status
                FROM application_condition
                WHERE application_id = :application_id"
            );
            
            $statement->execute([
                ':member_id' => $member_id,
                ':application_id' => $application_id
            ]);
        }
        // Insert player allergy to table
        public static function copyToPlayerAllergy($pdo, $application_id, $member_id)
        {
               $statement = $pdo->prepare
               (
                "INSERT IGNORE INTO player_allergy (member_id, allergy_id)
                SELECT 
                    :member_id,
                    allergy_id
                FROM application_allergy
                WHERE application_id = :application_id"
            );

            $statement->execute([
                ':application_id' => $application_id,
                ':member_id' => $member_id
            ]);
        }

        // Get Allergies for a specific player/application id
        // Existing Player 
        // $table = player_allergy, $id_name = member_id 
        // $view = /player

        // New Player Application
        // $table = application_allergy, $id_name = application_allergy_id
        // $view = /player-applications
        public static function getPlayerAllergies($pdo, $table, $player_id, $id_name, $view)
        {
            try{
                $statement = $pdo->prepare(
                    "SELECT * FROM $table
                    JOIN allergy ON $table.allergy_id = allergy.allergy_id
                    WHERE $table.$id_name = :id"
                    );

                $statement->execute([':id' => $player_id]);
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (\PDOException $e) {
                // Handle any database errors
                alert('error', 'Something went wrong.', '/'.$view);
                return null;
            }
        }
        
        // Get Medical Conditions for a specific player/application id
        // Existing Player
        // $table = player_condition, $id_name = player_condition_id
        // $view = /players
        // New Player Application
        // $table = application_condition, $id_name = application_id
        // $view = /player-applications
        public static function getPlayerConditions($pdo, $table, $player_id, $id_name, $status, $view)
        {
            try{
                $statement = $pdo->prepare(
                    "SELECT * FROM $table
                    JOIN medical_condition ON $table.condition_id = medical_condition.condition_id
                    WHERE $table.$id_name = :id AND condition_status = :condition_status"
                    );

                $statement->execute([
                    ':id' => $player_id,
                    ':condition_status' => $status
                ]);
                
                $playerConditions = $statement->fetchAll(PDO::FETCH_ASSOC);
                if(!$playerConditions){
                    return null;
                }
                return $playerConditions;  
            }

            catch (\PDOException $e) {
                alert('error', 'Something went wrong.', '/'.$view);   
                return null;
            }
        }

        // Get Doctor Details for a specific player/application id
        // Existing Player
        // $table = player_profile, $id_name = membber_id
        // $view = /players
        // New Player Application
        // $table = player_application, $id_name = application_id
        // $view = /player-applications
        public static function getPlayerDoctor($pdo, $table, $player_id, $id_name)
        {
            try{
                $statement = $pdo->prepare(
                    "SELECT doctor.* FROM $table
                    JOIN doctor ON $table.doctor_id = doctor.doctor_id
                    WHERE $table.$id_name = :id"
                );

                $statement->execute([':id' => $player_id]);
                return $statement->fetch(PDO::FETCH_ASSOC);
            }
            catch (\PDOException $e) {
                // Handle any database errors
                throw new \Exception('Something went wrong in fetching doctor details.');
            }
        }

        // Get Doctor Address Id
        public static function getDoctorAddressId($pdo, $doctor_id)
        {
            try{
                $statement = $pdo->prepare(
                    "SELECT address_id FROM doctor
                    WHERE doctor_id = :doctor_id"
                    );

                $statement->execute([':doctor_id' => $doctor_id]);
                $result = $statement->fetchColumn();
                if(!$result){
                    throw new \Exception('Doctor address id not found.');
                }
                $address = Address::getAddressDetails($pdo, $result);
                return $address;
            }
            catch (\PDOException $e) {
                // Handle any database errors
                throw new \Exception('Something went wrong in fetching doctor address details.');
            }
        }
    }
?>