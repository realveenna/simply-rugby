<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Injury
    {
        public $player_injury_id;
        public $injury_id;
        public $member_id;
        public $injury_date;
        public $recovery_date;
        public $training_session_id;
        public $match_id;
        public $injury_status;
   

        public function __construct($data = [])
        {
            $this->player_injury_id = $data['player_injury_id'] ?? null;
            $this->injury_id = $data['injury_id'] ?? null;
            $this->member_id = $data['member_id'] ?? null;
            $this->injury_date = $data['injury_date'] ?? null;
            $this->training_session_id = $data['training_session_id'] ?: null;
            $this->match_id = $data['match_id'] ?: null;
            $this->injury_status = $data['injury_status'] ?? 'Active';

            // If injury is recovered, set recovery date to today's date else null
            if ($this->injury_status === 'Recovered') {
                $this->recovery_date = date('Y-m-d');
            } else {
                $this->recovery_date = null;
            }
        }

        // Insert player injury
        public function insert($pdo)
        {
            $statement = $pdo->prepare
            (
                "INSERT INTO player_injury
                SET
                    injury_id = :injury_id,
                    member_id = :member_id,
                    training_session_id = :training_session_id,
                    match_id = :match_id,
                    injury_status = :injury_status,
                    recovery_date = :recovery_date"
            );

            return $statement->execute([
                ':injury_id' =>$this->injury_id,
                ':member_id' =>$this->member_id,
                ':training_session_id' => $this->training_session_id ?: null,
                ':match_id' => $this->match_id ?: null,
                ':injury_status' =>$this->injury_status ?? 'Active',
                ':recovery_date' => $this->recovery_date
            ]);
        }

        // Update injury
        public function update($pdo)
        {
            $statement = $pdo->prepare
            (
                "UPDATE player_injury
                SET
                    injury_id = :injury_id,
                    member_id = :member_id,
                    recovery_date = :recovery_date,
                    training_session_id = :training_session_id,
                    match_id = :match_id,
                    injury_status = :injury_status

                WHERE player_injury_id = :player_injury_id"
            );

            return $statement->execute([
                ':player_injury_id' => $this->player_injury_id,
                ':injury_id' => $this->injury_id,
                ':member_id' => $this->member_id,
                ':recovery_date' => $this->recovery_date,
                ':training_session_id' => $this->training_session_id,
                ':match_id' => $this->match_id,
                ':injury_status' => $this->injury_status 
            ]);
        }

        // Get all injuries
        public static function getAllInjuries($pdo)
        {
            $statement = $pdo->prepare(
                "SELECT injury_id, injury_name
                FROM injury"
            );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get injury details by player_injury_id
        public static function getPlayerInjuryId($pdo, $player_injury_id)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    pi.*,
                    CONCAT(m.first_name, ' ', m.last_name) AS player_name,
                    i.injury_name,
                    sq.squad_id

                FROM player_injury pi
                LEFT JOIN injury i ON pi.injury_id = i.injury_id
                LEFT JOIN member m ON pi.member_id = m.member_id
                LEFT JOIN squad_member sm ON m.member_id = sm.member_id
                LEFT JOIN squad sq ON sm.squad_id = sq.squad_id
                
                WHERE pi.player_injury_id = :player_injury_id"

            );
            $statement->execute([
                ':player_injury_id' => $player_injury_id
            ]);
            
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        // Get all player injuries
        public static function getPlayerInjuries($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    i.injury_name,
                    pi.*,
                    COALESCE(ts.date, m.match_date) AS injury_date

                FROM player_injury pi
                LEFT JOIN injury i ON i.injury_id = pi.injury_id
                LEFT JOIN training_session ts ON ts.training_session_id = pi.training_session_id
                LEFT JOIN matches m ON m.match_id = pi.match_id

                WHERE pi.member_id = :member_id
                ORDER BY injury_status DESC"
            );

            $statement->execute([
                ':member_id' => $member_id
            ]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

          // Get all injuries
        public static function getAllPlayerInjuries($pdo)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    i.injury_name,
                    pi.*,
                    CONCAT(m.first_name, ' ', m.last_name) AS player_name,
                    sq.squad_id,
                    sq.squad_name,
                    COALESCE(ts.date, mt.match_date) AS injury_date
                    
                FROM player_injury pi

                LEFT JOIN injury i ON i.injury_id = pi.injury_id
                LEFT JOIN member m ON m.member_id = pi.member_id
                LEFT JOIN squad_member sm ON sm.member_id = pi.member_id
                LEFT JOIN squad sq ON sq.squad_id = sm.squad_id
                LEFT JOIN training_session ts ON ts.training_session_id = pi.training_session_id
                LEFT JOIN matches mt ON mt.match_id = pi.match_id
                
                ORDER BY injury_status DESC"
            );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

          // Get all injuries by squad
        public static function getAllPlayerInjuriesBySquad($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    i.injury_name,
                    pi.*,
                    CONCAT(m.first_name, ' ', m.last_name) AS player_name,
                    sq.squad_id,
                    sq.squad_name,  
                    COALESCE(ts.date, mt.match_date) AS injury_date
                    
                FROM player_injury pi

                LEFT JOIN injury i ON i.injury_id = pi.injury_id
                LEFT JOIN member m ON m.member_id = pi.member_id
                LEFT JOIN squad_member sm ON sm.member_id = pi.member_id
                LEFT JOIN squad sq ON sq.squad_id = sm.squad_id
                LEFT JOIN training_session ts ON ts.training_session_id = pi.training_session_id
                LEFT JOIN matches mt ON mt.match_id = pi.match_id

                WHERE sq.squad_id IN (
                    SELECT squad_id
                    FROM squad_member
                    WHERE member_id = :member_id
                )

                ORDER BY pi.injury_status DESC"
            );

            $statement->execute([
                ':member_id' => $member_id
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>