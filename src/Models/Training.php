<?php
    namespace Test\Models;

    use PDO;
    
    class Training
    {
        public $training_session_id;
        public $squad_id;
        public $coach_member_id;
        public $skills_activities;
        public $start_time;
        public $end_time;
        public $date;

        public function __construct($data = [])
        {
            $this->training_session_id = $data['training_session_id'] ?? null;
            $this->squad_id = $data['squad_id'] ?? null;
            $this->coach_member_id = $data['coach_member_id'] ?? null;
            $this->skills_activities = $data['skills_activities'] ?? '';
            $this->start_time = $data['start_time'] ?? '';
            $this->end_time = $data['end_time'] ?? '';
            $this->date = $data['date'] ?? '';
        }

        // Create training session 
        public function insert($pdo)
        {
            $statement = $pdo->prepare
            (
                "INSERT INTO training_session 
                    (squad_id, coach_member_id, date, start_time, end_time, skills_activities) 
                VALUES (:squad_id, :coach_member_id, :date, :start_time, :end_time, :skills_activities)"
            );

            $statement->execute([
                ':squad_id' => $this->squad_id,
                ':coach_member_id' => $this->coach_member_id,
                ':date' => $this->date,
                ':start_time' => $this->start_time,
                ':end_time' => $this->end_time,
                ':skills_activities' => $this->skills_activities
            ]);
            
            return $pdo->lastInsertId();
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


        // Select Trainings allow all or by squad_id
        public static function getTraining($pdo, $squad_id = null){
            $sql =
                "SELECT 
                    ts.*,
                    s.squad_name,
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
                LEFT JOIN member coach ON ts.coach_member_id = coach.member_id";
              
            $params = [];
            
            // If selecting by squad_id
            if($squad_id !== null){
                $sql .= " WHERE ts.squad_id = :squad_id";
                $params[':squad_id'] = $squad_id;
            }

            // Order result group by id and desc date
            $sql .= " GROUP BY ts.training_session_id ORDER BY date DESC";

            $statement = $pdo->prepare($sql);
            $statement->execute($params);
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

        public static function hasSkillRatings($pdo, $training_session_id)
        {
            $statement = $pdo->prepare
            (
                "SELECT COUNT(*)
                FROM player_skill
                WHERE training_session_id = :training_session_id

            ");

            $statement->execute([
                ':training_session_id' => $training_session_id
            ]);

            // Returns true of false if training_session_id exists in player_skills table
            return ((int)$statement->fetchColumn() > 0);
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