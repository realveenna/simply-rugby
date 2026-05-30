<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Squad
    {
        private $squad_id;
        private $squad_name;
        private $section_name;
        private $season;
        private $start_date;
        private $end_date;
        private $min_age;
        private $max_age;



        public function __construct()
        {
            $this->squad_id = null;
            $this->squad_name = '';
            $this->section_name = '';
            $this->season = '';
            $this->start_date = null;
            $this->end_date = null;
            $this->min_age = null;
            $this->max_age = null;
        }
         // Player and Coach
        public static function getMemberSquad($pdo, $member_id){
            $statement = $pdo->prepare
            (
                "SELECT 
                    s.squad_id,
                    s.squad_name,
                    s.section_id,
                    s.season,
                    sec.section_name,
                    COUNT(own.member_id) AS total_members
                FROM squad s
                LEFT JOIN section sec ON s.section_id = sec.section_id
                LEFT JOIN squad_member own ON s.squad_id = own.squad_id
                WHERE own.member_id = :member_id

                GROUP BY
                    s.squad_id, 
                    s.squad_name,
                    s.season,
                    sec.section_name"
            );
                $statement->execute([
                ':member_id' => $member_id
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get renewwal reminder
        public static function getRenewalReminder($pdo, $squad_id)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    squad_name,
                    season,
                    end_date,
                    DATEDIFF(end_date, CURDATE()) AS days_left
                FROM squad
                WHERE squad_id = :squad_id
                AND DATEDIFF(end_date, CURDATE()) <= 30
                AND end_date >= CURDATE()"
            );

            $statement->execute([
                ':squad_id' => $squad_id
            ]);

            return $statement->fetch();
        }

        
        
        // Selct Squad Coaches
        public static function getSquadCoaches($pdo, $squad_id){
            $statement = $pdo->prepare
            (
                "SELECT 
                    m.member_id,
                    CONCAT(m.first_name, ' ', m.last_name) AS coach_name

                FROM squad_member sc
                INNER JOIN member m  ON m.member_id = sc.member_id
                WHERE sc.squad_id = :squad_id
                    AND role_id = 5

                ORDER BY m.first_name ASC"
            );
            
                $statement->execute([
                ':squad_id' => $squad_id
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fixture and Section Secretary
        public static function getSectionSquads($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    s.squad_id,
                    s.squad_name,
                    s.section_id,
                    s.season,
                    sec.section_name,
                    COUNT(sm.member_id) AS total_members
                FROM squad s
                LEFT JOIN squad_member sm ON s.squad_id = sm.squad_id
                LEFT JOIN section sec ON s.section_id = sec.section_id
                LEFT JOIN section_admin sa ON s.section_id = sa.section_id
                WHERE sa.member_id = :member_id"
            );

            $statement->execute([
                ':member_id' => $member_id
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

         // Update squad status
        public static function updateSquadStatus($pdo, $member_id, $status)
        {
            $statement = $pdo->prepare(
                "UPDATE squad_member
                SET status = :status
                WHERE member_id = :member_id"
            );

            return $statement->execute([
                ':status' => $status ?? 'Active',
                ':member_id' => $member_id
            ]);
        }


        // Display all squads for Club Chairperson and Membership Secretary
        public static function getAllSquads($pdo){
            $statement = $pdo->prepare
            (
                "SELECT 
                    s.squad_id,
                    s.squad_name,
                    s.season,
                    s.section_id,
                    sec.section_name,
                    COUNT(pp.member_id) AS total_members
                FROM squad s
                LEFT JOIN squad_member sm ON s.squad_id = sm.squad_id
                LEFT JOIN player_profile pp ON pp.member_id = sm.member_id
                LEFT JOIN section sec ON sec.section_id = s.section_id
                WHERE sm.status = 'Active'

                GROUP BY
                    s.squad_id, 
                    s.squad_name,
                    s.season,
                    sec.section_name"
            );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }


        // Get squad by squad type
        public static function getSquadByName($pdo, $squad_name){
            $statement = $pdo->prepare
            (
                "SELECT * FROM squad 
                WHERE squad_name = :squad_name LIMIT 1"
            );

            $statement->execute([
                ":squad_name" => $squad_name
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

  

        // Get squad details by id
        public static function getSquadById($pdo, $squad_id){
            $statement = $pdo->prepare
            (
                "SELECT * FROM squad WHERE squad_id = :squad_id"

            );
            $statement->execute([
                ':squad_id' => $squad_id
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        

        // Find squad suitable for player's renewal
        public static function getNextSquad($pdo, $player_age){
            $statement = $pdo->prepare
            (
                "SELECT 
                    *
                    FROM squad
                    WHERE :player_age BETWEEN min_age AND max_age"
            );

            $statement->execute([
                ":player_age" => $player_age
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }



        // inserts section/fixture secretary to section admin
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

        // insert member to a squad
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

        // list squad players
        public static function listSquadPlayer($pdo, $squad_id)
        {

            $statement = $pdo->prepare
            (
                "SELECT
                    m.member_id,
                    m.first_name,
                    m.last_name,
                    s.squad_name,
                    pp.*,
                    sm.status
                FROM squad_player_history h
                JOIN member m ON h.member_id = m.member_id
                JOIN squad s ON h.squad_id = s.squad_id
                JOIN player_profile pp  ON m.member_id = pp.member_id
                JOIN squad_member sm  ON m.member_id = sm.member_id
                WHERE h.squad_id = :squad_id 
                    AND sm.status = 'Active'
                    AND (h.end_date >= CURDATE() OR h.end_date  IS NULL)"
            );

            $statement->execute(['squad_id' => $squad_id]);
            $squadPlayer =  $statement->fetchAll(PDO::FETCH_ASSOC);
            
            return $squadPlayer;
        }

        // Get Squad Players
         public static function getSquadPlayers($pdo, $squad_id){

            $statement = $pdo->prepare
            (
                "SELECT
                    sm.*,
                    m.first_name,
                    m.last_name,

                    -- For senior player email
                    m.email AS player_email,

                    -- Primary guardian details
                    p.first_name AS guardian_first_name,
                    p.last_name AS guardian_last_name,
                    p.email AS guardian_email,

                    -- Player Profile
                    pp.position

                FROM squad_member sm
                JOIN member m ON sm.member_id = m.member_id
                LEFT JOIN player_contact pc ON m.member_id = pc.member_id
                    AND pc.is_primary = 1
                LEFT JOIN member p ON p.member_id = pc.contact_member_id
                JOIN player_profile pp ON pp.member_id = m.member_id

                WHERE 
                    sm.squad_id = :squad_id 
                    AND sm.status = 'Active' 
                    AND role_id != 5"
            );

            $statement->execute(['squad_id' => $squad_id]);
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

        // Identify squad_id based on age and season
        public static function identifySquad($pdo, $age){
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare
            (
                "SELECT * FROM squad 
                WHERE :age BETWEEN min_age AND max_age 
                LIMIT 1"
            );

            $statement->execute([
                ':age' => $age
            ]);

            $result = $statement->fetchColumn();

            if(!$result){
                return null;
            }
            return $result;
        }
    }
?>