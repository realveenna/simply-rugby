<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Training
    {
        private $training_session_id;
        private $squad_id;
        private $coach_member_id;
        private $date;
        private $time;
        private $skill_activities;


        public function __construct()
        {
            $this->training_session_id = null;
            $this->squad_id = '';
            $this->coach_member_id = '';
            $this->date = '';
            $this->time = '';
            $this->skill_activities = '';
        }


        // Create training session 
        public static function insert($pdo, $data)
        {
            $statement = $pdo->prepare
            (
                "INSERT INTO training_session 
                    (squad_id, coach_member_id, date, start_time, end_time, skills_activities) 
                VALUES (:squad_id, :coach_member_id, :date, :start_time, :end_time, :skills_activities)"
            );

            $statement->execute([
                ':squad_id' => $data['squad_id'],
                ':coach_member_id' => $data['coach_member_id'],
                ':date' => $data['training_date'],
                ':start_time' => $data['start_time'],
                ':end_time' => $data['end_time'],
                ':skills_activities' => $data['skills_activities']
            ]);
            
            return $pdo->lastInsertId();
        }

        public static function insertSectionAdmin($pdo, $member_id, $section_id){
            $statement = $pdo->prepare
            (
                "INSERT IGNORE INTO section_admin (section_id, member_id) 
                VALUES (:section_id, :member_id)"
            );

            $result = $statement->execute([
                ':section_id' => $section_id,
                ':member_id' => $member_id
            ]);

            return $result;
        }

        public static function insertSquadMember($pdo, $member_id, $squad_id, $role_id){
            $statement = $pdo->prepare
            (
                "INSERT IGNORE INTO squad_member (squad_id, member_id, role_id) 
                VALUES (:squad_id, :member_id, :role_id)"
            );

            $result = $statement->execute([
                ':squad_id' => $squad_id,
                ':member_id' => $member_id,
                ':role_id' => $role_id
            ]);

            return $result;
        }

        // Display all squads
        public static function getAllSquads($pdo){
            $statement = $pdo->prepare
            (
                "SELECT 
                    s.squad_id,
                    s.squad_name,
                    s.season,
                    sec.section_name,
                    COUNT(sm.member_id) AS total_members
                FROM squad s
                LEFT JOIN squad_member sm ON s.squad_id = sm.squad_id
                LEFT JOIN section sec ON sec.section_id = s.section_id

                GROUP BY
                    s.squad_id, 
                    s.squad_name,
                    s.season,
                    sec.section_name"
            );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function countSquad($pdo){
            $statement = $pdo->prepare("SELECT COUNT(*) FROM squad_member");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
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