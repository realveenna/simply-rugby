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

        public function __construct($data = [])
        {
            $this->match_id = $data['match_id'] ?? null;
            $this->squad_id = $data['squad_id'] ?? null;
            $this->match_venue = $data['match_venue'] ?? '';
            $this->match_date = $data['match_date'] ?? '';
            $this->opposition_team_name = $data['opposition_team_name'] ?? '';
            $this->kick_off_time = $data['kick_off_time'] ?? '';
            $this->result = $data['result'] ?? '';
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
                ':result' => $this->result
            ]);

            // Get Id
            $id = $pdo->lastInsertId();

            if (!$id) {
                throw new \Exception('Failed to create match');
            }
            
            return $id;
        }

        // Insert Lineup
        public static function insertLineup($pdo, $match_id, $player_id, $position)
        {
            $statement = $pdo->prepare
            (
                "INSERT INTO match_lineup
                    (match_id, player_id, position)
                VALUES
                    (:match_id, :player_id, :position)"
            );

            return $statement->execute([
                ':match_id' => $match_id,
                ':player_id' => $player_id,
                ':position' => $position ?? ''
            ]);
        }

        // Update training session
        public function update($pdo)
        {
            $statement = $pdo->prepare
            (
                "UPDATE training_session 
                SET 
                    coach_member_id = :coach_member_id, 
                    date = :date, 
                    start_time = :start_time, 
                    end_time = :end_time, 
                    skills_activities = :skills_activities
                WHERE training_session_id = :training_session_id"
            );

            return $statement->execute([
                ':coach_member_id' => $this->coach_member_id,
                ':date' => $this->date,
                ':start_time' => $this->start_time,
                ':end_time' => $this->end_time,
                ':skills_activities' => $this->skills_activities,
                ':training_session_id' => $this->training_session_id
            ]);
            
        }

        // Select Matches allow all or by squad_id
        public static function getMatches($pdo, $squad_id = null){
           $sql = "
                SELECT *
                FROM matches
            ";

            // If squad_id is not null
            if ($squad_id !== null) {
                $sql .= " WHERE squad_id = :squad_id";
            }

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

        // Select Training by id
        public static function getTrainingById($pdo, $training_session_id){
            $statement = $pdo->prepare
            (
                "SELECT 
                    ts.*,
                    s.squad_name,
                    s.squad_id,
                    sec.section_name,
                    CONCAT(coach.first_name, ' ', coach.last_name) AS coach_name,
                    SUM(ta.attendance_status = 'Pending') AS pending_count

                FROM training_session ts

                -- Squad Name
                LEFT JOIN squad s ON ts.squad_id = s.squad_id
                -- Section (Junior/Senior)
                LEFT JOIN section sec ON s.section_id = sec.section_id
                -- Attendance in Pending
                LEFT JOIN training_attendance ta ON ts.training_session_id = ta.training_session_id
                -- Coach
                LEFT JOIN member coach ON ts.coach_member_id = coach.member_id
                WHERE ts.training_session_id = :training_session_id
                ORDER BY date DESC"
            );
                
            $statement->execute([
                ':training_session_id' => $training_session_id
            ]);

            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        public static function getTrainingAttendance($pdo, $training_session_id = null){
            // SQL query
            $sql = 
                "SELECT 
                    ta.attendance_status,
                    CONCAT(player.first_name, ' ', player.last_name) AS player_name,
                    pp.player_availability_status

                FROM training_attendance ta
                -- Player Name
                LEFT JOIN member player ON ta.member_id = player.member_id
                -- Player Availability Status
                LEFT JOIN player_profile pp ON player.member_id = pp.member_id";


            // If selecting by training_session_id
            if($training_session_id !== null){
                $sql .= " WHERE ta.training_session_id = :training_session_id";
            }

            // Order result
            $sql .= " ORDER BY date DESC";

            $statement = $pdo->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function deleteTraining($pdo, $training_session_id){
            $statement = $pdo->prepare
            (
                "DELETE FROM training_session 
                WHERE training_session_id = :training_session_id"
            );
            
            $result = $statement->execute([
                ':training_session_id' => $training_session_id
            ]);

            return $result;
        }
    }
?>