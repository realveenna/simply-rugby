<?php
    namespace Test\Models;

    use Exception;
    use Test\Database;
    use PDO;
    
    class Attendance
    {

        private $training_session_id;
        private $member_id;
        private $attendance_status;


        public function __construct($training_session_id, $member_id, $attendance_status)
        {
            $this->training_session_id = $training_session_id;
            $this->member_id = $member_id;
            $this->attendance_status = $attendance_status;
        }
        
        // Insert players to a to training_attendance
        public static function insert($pdo, $training_session_id, $member_id){
            $statement = $pdo->prepare
            ("INSERT IGNORE INTO training_attendance(training_session_id, member_id)
             VALUES (:training_session_id, :member_id)");

            $result = $statement->execute([
                ':training_session_id' => $training_session_id,
                ':member_id' => $member_id
            ]);

            if(!$result){
                return null;
            }
            return $result;
        }

         // Update player's status in training_attendance
        public function update($pdo){
            $statement = $pdo->prepare
            (
                "UPDATE training_attendance 
                SET attendance_status = :attendance_status
                WHERE training_session_id = :training_session_id 
                AND member_id = :member_id
            ");

            $result = $statement->execute([
                ':training_session_id' => $this->training_session_id,
                ':member_id' => $this->member_id,
                ':attendance_status' => $this->attendance_status
            ]);

            if(!$result){
                return null;
            }
            return $result;
        }

        // Get players from training_attendance table via training_session_id
        public static function getPlayersById($pdo, $training_session_id){
            $statement = $pdo->prepare
            (
                "SELECT 
                    ta.attendance_status,
                    ts.training_session_id,
                    CONCAT(player.first_name, ' ', player.last_name) AS player_name,
                    player.member_id,
                    pp.player_availability_status

                FROM training_session ts
                INNER JOIN training_attendance ta ON ta.training_session_id = ts.training_session_id
                INNER JOIN member player ON ta.member_id = player.member_id
                INNER JOIN player_profile pp ON pp.member_id = player.member_id
                WHERE ts.training_session_id = :training_session_id
            ");

            $statement->execute([
                ':training_session_id' => $training_session_id
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>