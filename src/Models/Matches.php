<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Matches
    {
        public $match_id;
        public $squad_id;
        public $match_venue;
        public $match_date;
        public $opposition_team_name;
        public $kick_off_time;
        public $result;
        public $squad_name;
        public $section_name;
        public $section_id;

        public function __construct($data = [])
        {
            $this->match_id = $data['match_id'] ?? null;
            $this->squad_id = $data['squad_id'] ?? null;
            $this->match_venue = $data['match_venue'] ?? '';
            $this->match_date = $data['match_date'] ?? '';
            $this->opposition_team_name = $data['opposition_team_name'] ?? '';
            $this->kick_off_time = $data['kick_off_time'] ?? '';
            $this->result = $data['result'] ?? '';

            $this->squad_name = $data['squad_name'] ?? null;
            $this->section_name = $data['section_name'] ?? null;
            $this->section_id = $data['section_id'] ?? null;
        }

        // Create match
        public function insert($pdo)
        {
            $statement = $pdo->prepare
            (
                "INSERT INTO matches
                    (squad_id, match_venue, match_date, opposition_team_name, 
                    kick_off_time, result)
                VALUES
                    (:squad_id, :match_venue, :match_date, :opposition_team_name, 
                    :kick_off_time, :result)"
            );

            $statement->execute([
                ':squad_id' => $this->squad_id,
                ':match_venue' => $this->match_venue,
                ':match_date' => $this->match_date,
                ':opposition_team_name' => $this->opposition_team_name,
                ':kick_off_time' => $this->kick_off_time,
                ':result' => $this->result ?? 'Pending'
            ]);

            // Get Id
            $id = $pdo->lastInsertId();

            if (!$id) {
                throw new \Exception('Failed to create match');
            }
            
            return $id;
        }

        // Update match details
        public function update($pdo)
        {
            $statement = $pdo->prepare
            (
                "UPDATE matches
                SET
                    opposition_team_name = :opposition_team_name,
                    match_venue = :match_venue,
                    match_date = :match_date,
                    kick_off_time = :kick_off_time
                WHERE match_id = :match_id
            ");

            return $statement->execute([
                ':opposition_team_name' => $this->opposition_team_name,
                ':match_venue' => $this->match_venue,
                ':match_date' => $this->match_date,
                ':kick_off_time' => $this->kick_off_time,
                ':match_id' => $this->match_id
            ]);
        }

        // Update match result (Win / Lose / Draw)
        public static function updateMatchResult($pdo,$result, $match_id)
        {
            $statement = $pdo->prepare
            (
                "UPDATE matches
                SET
                    result = :result
                WHERE match_id = :match_id
            ");

            return $statement->execute([
                ':result' => $result ?? 'Pending',
                ':match_id' => $match_id
            ]);
        }

        // Insert Lineup
        public static function updatePlayerPosition($pdo, $match_id, $member_id, $position = null)
        {
            $statement = $pdo->prepare
            (
                  "UPDATE match_lineup
                    SET position = :position
                    WHERE match_id = :match_id AND member_id = :member_id"
            );

            return $statement->execute([
                ':match_id' => $match_id,
                ':member_id' => $member_id,
                ':position' => $position
            ]);
        }

        // Create lineup using squad players
        public static function createLineupFromSquad($pdo, $match_id, $squad_id)
        {
            // Get all squad players
            $players = Squad::getSquadPlayers($pdo, $squad_id);

            // Prepare insert statement
            $statement = $pdo->prepare
            (
                "INSERT INTO match_lineup 
                    (match_id, member_id, position) 
                VALUES 
                    (:match_id, :member_id, :position)"
            );

            // Insert each player into lineup
            foreach ($players as $player) {

                $statement->execute([
                    ':match_id' => $match_id,
                    ':member_id' => $player['member_id'],
                    ':position' => ''
                ]);
            }
            return true;
        }

        // Get Player Match Lineup
        public static function getLineup($pdo, $match_id)
        {
            $statement = $pdo->prepare
            (
                "SELECT 
                    CONCAT(player.first_name, ' ', player.last_name) AS player_name,
                    player.member_id AS player_id,
                    ml.position,

                    pms.minutes_played,
                    pms.tries,
                    pms.conversions,
                    pms.penalties,
                    pms.drop_goals,
                    pms.yellow_cards,
                    pms.red_cards

                FROM match_lineup ml
                INNER JOIN member player ON player.member_id = ml.member_id
                LEFT JOIN player_match_stats pms ON ml.member_id = pms.member_id 
                    AND ml.match_id = pms.match_id

                WHERE ml.match_id = :match_id"
            );

            $statement->execute([':match_id' => $match_id]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // // Get Coach of a Squad
        // public static function getCoachLineup($pdo, $match_id)
        // {
        //     $statement = $pdo->prepare
        //     (
        //         "SELECT 
        //             CONCAT(player.first_name, ' ', player.last_name) AS player_name,
        //             ml.position

        //         FROM match_lineup ml
        //         INNER JOIN member player ON player.member_id = ml.member_id

        //         WHERE ml.match_id = :match_id"
        //     );

        //     $statement->execute([':match_id' => $match_id]);

        //     return $statement->fetchAll(PDO::FETCH_ASSOC);
        // }

        // Delete Match
        public static function delete($pdo, $match_id){
            $statement = $pdo->prepare
            (
                "DELETE FROM matches 
                WHERE match_id = :match_id"
            );
            
            $result = $statement->execute([
                ':match_id' => $match_id
            ]);

            return $result;
        }

        // Select Matches allow all or by squad_id
        public static function getMatches($pdo, $squad_id = null){
           $sql = "
                SELECT
                    m.*,
                    s.squad_name,
                    sec.section_id,
                    sec.section_name
                    
                FROM matches m

                LEFT JOIN squad s ON m.squad_id = s.squad_id
                LEFT JOIN squad_member sm ON m.squad_id = sm.squad_id
                LEFT JOIN section sec ON sec.section_id  = s.section_id 
            ";

            // If squad_id is not null
            if ($squad_id !== null) {
                $sql .= " WHERE m.squad_id = :squad_id";
            }

            // GROUP by
            $sql .= "  GROUP BY m.match_id";

            // Order by match date
            $sql .= " ORDER BY match_date ASC, kick_off_time ASC";

            $statement = $pdo->prepare($sql);

            // Execute with or without squad_id
            if ($squad_id !== null) {
                $statement->execute([
                    ':squad_id' => $squad_id
                ]);
            } else {
                $statement->execute();
            }

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get match details by id
        public static function getMatchById($pdo, $match_id){
            $statement = $pdo->prepare
            (
                "SELECT
                    m.*,
                    s.squad_name,
                    sec.section_id,
                    sec.section_name

                FROM matches m

                LEFT JOIN squad s ON m.squad_id = s.squad_id
                LEFT JOIN squad_member sm ON m.squad_id = sm.squad_id
                LEFT JOIN section sec ON sec.section_id = s.section_id
                
                WHERE match_id = :match_id"

            );
            $statement->execute([
                ':match_id' => $match_id
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        // Get all match halves by match_id
        public static function getMatchHalfById($pdo, $match_id)
        {
            $statement = $pdo->prepare(
            "SELECT 
                mh.*,
                (SELECT SUM(our_points)
                FROM match_half
                WHERE match_id = :match_id_1) AS our_total_points,

                (SELECT SUM(opponent_points)
                FROM match_half
                WHERE match_id = :match_id_2) AS opponent_total_points

                FROM match_half mh
                WHERE mh.match_id = :match_id
                ORDER BY mh.half_type ASC
            ");

            $statement->execute([
                ':match_id' => $match_id,
                ':match_id_1' => $match_id,
                ':match_id_2' => $match_id
            ]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Save match half
        public static function saveMatchHalf($pdo, $match_id, $half)
        {
            $statement = $pdo->prepare(
                "INSERT INTO match_half
                 SET
                    match_id = :match_id,
                    half_type = :half_type,
                    our_points = :our_points,
                    opponent_points = :opponent_points,
                    our_comments = :our_comments,
                    opponent_comments = :opponent_comments

                -- If duplicate match_id and half_type then update
                ON DUPLICATE KEY UPDATE
                    our_points = VALUES(our_points),
                    opponent_points = VALUES(opponent_points),
                    our_comments = VALUES(our_comments),
                    opponent_comments = VALUES(opponent_comments)"
            );

            return $statement->execute([
                ':match_id' => $match_id,
                ':half_type' => $half['half_type'],
                ':our_points' => $half['our_points'],
                ':opponent_points' => $half['opponent_points'],
                ':our_comments' => $half['our_comments'],
                ':opponent_comments' => $half['opponent_comments']
            ]);
        }

        // Get player match stats
        public static function getMatchStats($pdo,$member_id){
            $statement = $pdo->prepare(
                "SELECT
                    pms.*,
                    m.match_date,
                    m.opposition_team_name,
                    s.squad_name

                FROM player_match_stats pms

                INNER JOIN matches m
                    ON pms.match_id = m.match_id

                INNER JOIN squad s
                    ON m.squad_id = s.squad_id

                WHERE pms.member_id = :member_id

                ORDER BY
                    m.match_date DESC"
            );

            $statement->execute([
                ':member_id' => $member_id
            ]);

            return $statement->fetchAll(
                PDO::FETCH_ASSOC
            );
        }

        // Get player stats
        public static function getAllMatchStats($pdo,$member_id){
            $statement = $pdo->prepare(
                "SELECT
                    SUM(tries) AS tries,
                    SUM(conversions) AS conversions,
                    SUM(penalties) AS penalties,
                    SUM(drop_goals) AS drop_goals,
                    SUM(minutes_played) AS minutes_played,
                    SUM(yellow_cards) AS yellow_cards,
                    SUM(red_cards) AS red_cards,

                    ((SUM(tries) * 5) + (SUM(conversions) * 2) + 
                    (SUM(penalties) * 3) + (SUM(drop_goals) * 3)) AS total_points

                FROM player_match_stats
                WHERE member_id = :member_id"
            );

            $statement->execute([
                ':member_id' => $member_id
            ]);

            return $statement->fetch(PDO::FETCH_ASSOC);
        }
    }
?>