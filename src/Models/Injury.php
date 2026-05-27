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
            $this->recovery_date = $data['recovery_date'] ?? null;
            $this->training_session_id = $data['training_session_id'] ?? null;
            $this->match_id = $data['match_id'] ?? null;
            $this->injury_status = $data['injury_status'] ?? 'Active';
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
                    injury_status = :injury_status"
            );

            return $statement->execute([
                ':injury_id' =>$this->injury_id,
                ':member_id' =>$this->member_id,
                ':training_session_id' => $this->training_session_id ?: null,
                ':match_id' => $this->match_id ?: null,
                ':injury_status' =>$this->injury_status ?? 'Active'
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

        // Get all player injuries
        public static function getPlayerInjuries($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT 
                    i.injury_name,
                    pi.player_injury_id,
                    pi.injury_status,
                    COALESCE(ts.date, m.match_date) AS injury_date

                FROM player_injury pi
                JOIN injury i ON i.injury_id = pi.injury_id
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
    }
?>