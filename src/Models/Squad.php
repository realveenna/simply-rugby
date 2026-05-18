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

        ########
        // Get Authorization for viewing squad
        public static function canViewSquad($squad)
        {
            // General permission check
            if (!$_SESSION['rbac']->hasPermission('view_squad')) {
                return false;
            }

            // If is higher admin
            if (isAdmin()) {
                return true;
            }

            // User is a coach, access own squad
            if (hasSquadAccess($squad['squad_id'])) {
                return true;
            }

            // User has section access to own section junior/senior
            if (hasSectionAccess($squad['section_id'])) {
                return true;
            }

            return false;
        }
        #################


        public static function getAccessSquads($pdo, $member_id)
        {
            $statement = $pdo->prepare
            ("
                 SELECT squad_id
                FROM squad_member
                WHERE member_id = :member_id
                AND role_id IN (5)
            ");

            $statement->execute([
                ':member_id' => $member_id
            ]);

            return $statement->fetchAll(PDO::FETCH_COLUMN);
        }
        ######

        #####
        public static function getAccessSections($pdo, $member_id)
        {
            $statement = $pdo->prepare
            ("
                SELECT section_id
                FROM section_admin
                WHERE member_id = :member_id
            ");

            $statement->execute([
                ':member_id' => $member_id
            ]);

            return $statement->fetchAll(PDO::FETCH_COLUMN);
        }
        #####

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

        // Get squad by squad by member id
        public static function getSquadOfMember($pdo, $member_id){
            $statement = $pdo->prepare
            (
                "SELECT * FROM squad 
                WHERE member_id = :member_id LIMIT 1"
            );

            $statement->execute([
                ":member_id" => $member_id
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        // Get Each Database Statement Role Access For Squad
        public static function getSquadAccess($pdo, $member_id, $rbac)
        {
            // Club Chairperson/Admin can access all squads
            if (hasRole('Club Chairperson') || hasRole('Membership Secretary')) {
                return Squad::getAllSquads($pdo);
            }

            if (hasRole('Fixture Secretary') || hasRole('Section Secretary')) {
                return Squad::getSectionSquads($pdo, $member_id);
            }

            if (hasRole('Coach') || hasRole('Senior Player')) {
                return Squad::getMemberSquad($pdo, $member_id);
            }

            return [];
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


        // public static function getSectionName($pdo, $squad_id){
        //     $statement = $pdo->prepare
        //     (
        //         "SELECT 
        //             s.squad_name,
        //             sec.section_name
        //             LEFT JOIN squad s 
        //             LEFT JOIN section sec ON s.section_id = sec.section_id
        //             WHERE squad_id = :squad_id LIMIT 1"
        //     );

        //     $statement->execute([
        //         ":squad_id" => $squad_id
        //     ]);

        //     $result = $statement->fetch(PDO::FETCH_ASSOC);
        //     return $result;
        // }

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