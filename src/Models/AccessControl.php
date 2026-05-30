<?php
    namespace Test\Models;

    use PDO;
    
    class AccessControl
    {
        public $authorizedMatchIds = [];

        public function __construct($pdo)
        {
            $this->authorizedMatchIds = $this->getAuthorizedMatchIds($pdo);
        }

        ## AUTHORIZATION FOR SQUAD, TRAINING AND MATCHES###

        ### SQUAD ACCESS ###
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
                    -- Get pending count to access other crud
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
        // Get All Database Statement Role Access For Match Details
        public static function getAuthorizedMatches($pdo)
        {
            $matches = Matches::getMatches($pdo);

            $authorizedMatchIds = self::getAuthorizedMatchIds($pdo);
            
            $authorizedMatches = [];

            foreach ($matches as $match) {
                if (in_array($match['match_id'], $authorizedMatchIds, true)) {
                    $authorizedMatches[] = $match;
                }
            }

            return $authorizedMatches;
        }

        // Get Each Database Statement Role Access For Injury Squad
        public static function getAuthorizedInjury($pdo, $member_id)
        {
            // Club Chairperson/Admin can access all Injury
            if (isAdmin()) {
                return Injury::getAllPlayerInjuries($pdo);
            }

            // Can acccess their own squad
            if (hasRole('Coach')) {
                return Injury::getAllPlayerInjuriesBySquad($pdo, $member_id);
            }

            return [];
        }

         // Get authorized match IDs
        private static function getAuthorizedMatchIds($pdo)
        {
            $matches = Matches::getMatches($pdo);

            $authorizedMatchesId = [];

            foreach ($matches as $match) {
                // Full access
                if (isAdmin()) {
                    $authorizedMatchesId[] = $match['match_id'];
                    continue;
                }

                // Parent access
                if (hasRole('Parent') && self::lineupParentAccess
                    ($pdo, $_SESSION['user']['member_id'], $match['match_id'])) 
                {
                    $authorizedMatchesId[] = $match['match_id'];
                    continue;
                }

                // Squad access
                if (hasSquadAccess($match['squad_id'])) {
                    $authorizedMatchesId[] = $match['match_id'];
                    continue;
                }

                // Section access
                if (hasSectionAccess($match['section_id'])) {
                    $authorizedMatchesId[] = $match['match_id'];
                    continue;
                }

                // Public senior
                if ($match['section_name'] === 'Senior') {
                    $authorizedMatchesId[] = $match['match_id'];
                    continue;
                }

            }

            return $authorizedMatchesId;
        }
        

        #################################################################
        ## GET ID of Player, Squad or Section a User/Member has access ##
        #################################################################

        // Get junior player of Parent
        public static function getAccessPlayers($pdo, $member_id)
        {
            $statement = $pdo->prepare("
                SELECT member_id
                FROM player_contact
                WHERE contact_member_id = :member_id
            ");

            $statement->execute([
                ':member_id' => $member_id
            ]);

            return $statement->fetchAll(PDO::FETCH_COLUMN);
        }

        // Get squads assigned to the member
        // For Coach
        public static function getAccessSquads($pdo, $member_id)
        {
            $statement = $pdo->prepare
            (
                "SELECT squad_id
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

        // Get Parent Match Access with lineup
        public static function lineupParentAccess($pdo, $parent_id, $match_id){
            $statement = $pdo->prepare
            (
                "SELECT ml.member_id
                FROM match_lineup ml
                INNER JOIN player_contact pc ON ml.member_id = pc.member_id
                WHERE pc.contact_member_id = :parent_id AND ml.match_id = :match_id
                LIMIT 1
            ");
            $statement->execute([
                ':parent_id' => $parent_id,
                ':match_id' => $match_id
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        }


        #################################################################
        ## VALIDATES USER ACCESS FOR SINGLE DATA BY ID AND RETURN DATA ##
        #################################################################

        // Validates Single Squad Access and returns Squad Details
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
            return $squad;
        }

        // Validates Single Match Access and returns Match Details
        public static function validateMatchAccess($pdo, $match_id)
        {
            // Get match
            $match = Matches::getMatchById($pdo, $match_id);

            // Match does not exist
            if (!$match) {
                abort(404, 'Match not found.');
            }

            // Unauthorized
            if (!self::canViewMatch($pdo, $match_id)) {
                abort(403);
            }
            
            return $match;
        }

        // Validates a member access and returns member details
        public static function validateMemberAccess($pdo, $member_id){
            // Get member details
            $member = Member::getMemberDetails($pdo, $member_id);

            // member does not exist abort
            if(!$member){
                abort(404, 'Member not found.');
            }

            // Unauthorized
            if (!self::canViewMember($_SESSION['user']['member_id'], $member)) {
                abort(403);
            }
            return $member;
        }

        #################################################
        ## RETURNS TRUE OF FALSE IF USER CAN VIEW DATA ##
        #################################################

        // Check if user can access this player details
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

        // Check if user can access this member details
        public static function canViewMember($currentUser, $member)
        {
            // If is higher admin
            if (isAdmin()) {
                return true;
            }

            // User access own details
            if (isOwner($currentUser, $member['member_id'])) {
                return true;
            }

            // User is parent 
            if (hasPlayerAccess($member['member_id'])) {
                return true;
            }
            return false;
        }


        // Validate single match access
        public static function canViewMatch($pdo, $match_id)
        {
            $match_ids = self::getAuthorizedMatchIds($pdo);

            // Check if each ids in array is equal to the param match_id
            foreach ($match_ids as $mi) {
                if ((int)$mi === (int)$match_id) {
                    return true;
                }
            }
            return false;
        }
        

        // Check ff user can view squad
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
    }
?>