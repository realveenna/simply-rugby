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

        // Get squad by squad type
        public static function getSquadByType($pdo, $squad_type){
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare
            (
                "SELECT * FROM squad 
                WHERE squad_type = :squad_type LIMIT 1"
            );

            $statement->execute([
                ":squad_type" => $squad_type
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        // Display all squads
        public static function getAllSquads($pdo){
            $statement = $pdo->prepare
            (
                "SELECT 
                    s.squad_id,
                    s.squad_name,
                    s.squad_type,
                    s.season,
                    COUNT(h.member_id) AS player_count
                FROM squad s
                LEFT JOIN squad_player_history h ON s.squad_id = h.squad_id
                    AND h.end_date IS NULL

                GROUP BY
                    s.squad_id,
                    s.squad_name,
                    s.squad_type,
                    s.season"
            );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function countSquad($pdo){
            $statement = $pdo->prepare("SELECT COUNT(*) FROM squad_member");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // List all player for each squad
        // public static function listSquadPlayer($pdo, $squad_id){
        //     $statement = $pdo->prepare
        //     (
        //         "SELECT
        //             s.squad_name,
        //             m.member_id, m.first_name, m.last_name,
        //             pp.*
        //         FROM squad_member sm
        //         JOIN squad s ON s.squad_id = sm.squad_id
        //         JOIN member m ON m.member_id = sm.member_id
        //         JOIN player_profile pp ON pp.member_id = m.member_id
        //         WHERE sm.squad_id = :squad_id"
        //     );
        //     $statement->execute(['squad_id' => $squad_id]);
        //     $squadPlayer =  $statement->fetchAll(PDO::FETCH_ASSOC);

        //     var_dump($squadPlayer);
        //     exit;
        //     return;
        // }

        // 
        public static function listSquadPlayer($pdo, $squad_id,){

            $statement = $pdo->prepare
            (
                "SELECT
                    m.member_id,
                    m.first_name,
                    m.last_name,
                    s.squad_name,
                    pp.*
                FROM squad_player_history h
                JOIN member m ON h.member_id = m.member_id
                JOIN squad s ON h.squad_id = s.squad_id
                JOIN player_profile pp  ON m.member_id = pp.member_id
                WHERE h.squad_id = :squad_id AND h.end_date IS NULL"
            );

            $statement->execute(['squad_id' => $squad_id]);
            $squadPlayer =  $statement->fetchAll(PDO::FETCH_ASSOC);
            
            return $squadPlayer;
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