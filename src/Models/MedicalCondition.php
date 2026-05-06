<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class MedicalCondition
    {
        public function insertApplication($application_id, $condition_id, $condition_status)
        {
            // $DB = new Database();
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare("INSERT INTO application_condition 
                (application_id, condition_id, condition_status)
            VALUES (:application_id, :condition_id, :condition_status)");

            $statement->bindValue(':application_id', $application_id, PDO::PARAM_INT);
            $statement->bindValue(':condition_id', $condition_id, PDO::PARAM_INT);
            $statement->bindValue(':condition_status', $condition_status, PDO::PARAM_STR);

            $statement->execute();
            // return TRUE or FALSE
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
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
    }

?>