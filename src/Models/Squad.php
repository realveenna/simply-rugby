<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Squad
    {
        private $squad_id;
        private $squad_name;
        private $squad_type;
        private $season;
        private $start_date;
        private $end_date;
        private $min_age;
        private $max_age;



        public function __construct()
        {
            $this->squad_id = null;
            $this->squad_name = '';
            $this->squad_type = '';
            $this->season = '';
            $this->start_date = null;
            $this->end_date = null;
            $this->min_age = null;
            $this->max_age = null;
        }

        // Get squad id by squad type
        public static function getSquadIdByType($pdo, $squad_type){
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare
            (
                "SELECT squad_id FROM squad 
                WHERE squad_type = :squad_type LIMIT 1"
            );
            $statement->execute(array(":squad_type" => $squad_type));

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $row["squad_id"];
            }
            return null;
        }

        // Display all squads
        public static function getAllSquads($pdo){
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare
            (
                "SELECT squad_id, squad_name FROM squad"
            );
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Insert member to squad_member table
        public static function insertSquadMember($pdo, $squad_id, $member_id){

            $statement = $pdo->prepare
            (
                "INSERT INTO squad_member (squad_id, member_id, status) 
                VALUES (:squad_id, :member_id, :status)"
            );

            $result = $statement->execute([
                ':squad_id' => $squad_id,
                ':member_id' => $member_id,
                ':status' => $status ?? 'Active'
            ]);

            return $result;
        }

        // Insert member to squad_member table
        public static function insertSquadHistory($pdo, $squad_id, $member_id){

            $statement = $pdo->prepare
            (
                "INSERT INTO squad_player_history (squad_id, member_id) 
                VALUES (:squad_id, :member_id)"
            );

            $result = $statement->execute([
                ':squad_id' => $squad_id,
                ':member_id' => $member_id,
            ]);
            return $result;
        }

        // Identify squad based on age and season
        public static function identifySquad($pdo, $age){
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare
            (
                "SELECT squad_id FROM squad 
                WHERE :age BETWEEN min_age AND max_age 
                LIMIT 1"
            );

            $statement->execute([
                ':age' => $age
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if(!$result){
                return null;
            }
            return $result;
        }
    }
?>