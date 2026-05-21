<?php
    namespace Test\Models;

    use PDO;
    
    class AccessControl 
    {
        // Get Each Database Statement Role Access For Squad
        public static function getAuthorizedSquads($pdo, $member_id)
        {
            // Club Chairperson/Admin can access all squads
            if (isAdmin()) {
                return Squad::getAllSquads($pdo);
            }

            // Can acccess their own section
            if (hasRole('Fixture Secretary') || hasRole('Section Secretary')) {
                return Squad::getSectionSquads($pdo, $member_id);
            }

            // Can acccess their own squad or own profile
            if (hasRole('Coach') || hasRole('Senior Player')) {
                return Squad::getMemberSquad($pdo, $member_id);
            }
            return [];
        }

        ## AUTHORIZATION 

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

        ### TRAINING ACCESS ###
        // Get Each Database Statement Role Access For Training Session Details
        public static function getAuthorizedTraining($pdo, $member_id = null)
        {
            // Permission check
            if (!hasPermission('view_training_session')) {
                return [];
            }

            // Admin full access get all training
            if (hasRole('Club Chairperson')) {
                return Training::getTraining($pdo);
            }

            // Show Training sessions if user manages the squad/section
             $statement = $pdo->prepare(
                "SELECT
                    ts.*,
                    s.squad_name,
                    sec.section_name,
                    CONCAT(coach.first_name, ' ', coach.last_name) AS coach_name,
                    SUM(ta.attendance_status = 'Pending') AS pending_count


                FROM training_session ts
                LEFT JOIN squad s ON ts.squad_id = s.squad_id
                LEFT JOIN section sec ON s.section_id = sec.section_id
                LEFT JOIN member coach ON ts.coach_member_id = coach.member_id
                LEFT JOIN squad_member sm ON ts.squad_id = sm.squad_id
                LEFT JOIN training_attendance ta ON ts.training_session_id = ta.training_session_id
                LEFT JOIN section_admin sa ON s.section_id = sa.section_id
                WHERE
                    sm.member_id = :squad_member_id OR sa.member_id = :section_admin_id
                GROUP BY ts.training_session_id
                ORDER BY ts.date DESC
            ");

                $statement->execute([
                    ':squad_member_id' => $member_id,
                    ':section_admin_id' => $member_id
                ]);
                
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        ### MATCH ACCESS ###
        // Get Each Database Statement Role Access For Training Session Details
        public static function getAuthorizedMatches($pdo, $member_id = null)
        {
            // Admin full access get all match
            if (hasRole('Club Chairperson')) {
                return Matches::getMatches($pdo);
            }

            // Show matches based on user access to the squad/section
            $statement = $pdo->prepare
             (
                "SELECT 
                    m.*, s.squad_name, sec.section_id

                FROM matches m

                LEFT JOIN squad s ON m.squad_id = s.squad_id
                LEFT JOIN squad_member sm ON m.squad_id = sm.squad_id
                LEFT JOIN section_admin sa ON s.section_id = sa.section_id
                LEFT JOIN section sec ON sec.section_id = sa.section_id

                WHERE
                    sm.member_id = :squad_member_id
                    OR sa.member_id = :section_admin_id
                    OR sec.section_name = 'Senior'

                GROUP BY m.match_id
                ORDER BY
                    m.result IS NOT NULL,
                    m.match_date DESC
            ");

                $statement->execute([
                    ':squad_member_id' => $member_id,
                    ':section_admin_id' => $member_id
                ]);
                
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function canViewPlayer($player, $currentUser, $member_id)
        {
            // General permission check
            if (!$_SESSION['rbac']->hasPermission('view_player_details')) {
                return false;
            }

            // If is higher admin
            if (isAdmin()) {
                return true;
            }

            // User access own details
            if (isOwner($currentUser, $member_id)) {
                return true;
            }

            // User is parent 
            if (hasPlayerAccess($member_id)) {
                return true;
            }

            // User is a coach, access own squad
            if (hasSquadAccess($player['squad_id'])) {
                return true;
            }

            // User has section access to own section junior/senior
            if (hasSectionAccess($player['section_id'])) {
                return true;
            }

            return false;
        }


        # FOR LOGIN # 
        // Get squads assigned to the member
        // For Coach
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

        // Get sections manaaged by the member 
        // For Fixture and Section Secretary
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

        # Global # 

        // Validates Squad Access and returns Squad Details
        public static function validateSquadAccess($pdo, $squad_id){
            // Get squad details
            $squad = Squad::getSquadById($pdo, $squad_id);

            // Squad does not exist abort
            if(!$squad){
                abort(404, 'Squad not found.');
            }

            // Unauthorized
            if (!self::canViewSquad($squad)) {
                abort(403);
            }
        }
    }
?>