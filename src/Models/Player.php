<?php
    namespace Test\Models;

    use Test\Database;
    use Test\Controllers\Auth;
    use Test\Controllers\Error;
    use PDO;
    
    class Player
    {
        // Update Application Status
        public static function insert($pdo, $data, $member_id)
        {
            $statement = $pdo->prepare(
                "INSERT INTO player_profile (member_id, sru_number, player_availability_status,position,nickname,doctor_id,height,weight)
                VALUES (:member_id, :sru_number, :player_availability_status, :position, :nickname, :doctor_id, :height, :weight)"
            );
            $sru_number = self::generateSRUNumber($pdo);

            $statement->execute([
                ':member_id' => $member_id,
                ':sru_number' => $sru_number ?? null,
                ':player_availability_status' => 'Unavailable',
                ':position' => $data['position'] ?? '',
                ':nickname' => $data['nickname'] ?? null,
                ':doctor_id' => $data['doctor']['doctor_id'],
                ':height' => $data['application']['playerHeight'],
                ':weight' => $data['application']['playerWeight']
            ]);
            return true;
        }

        private static function generateSRUNumber($pdo)
        {
            $sruPrefix = 'SRU-';
            $statement = $pdo->query(
                "SELECT member_id, first_name
                FROM member m
                ORDER BY member_id DESC
                LIMIT 1"
            );

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $fname = $result ? $result['first_name'] : '';
            $sruPrefix .= strtoupper(substr($fname, 0, 3)) .'-';

            $lastId = $result ? $result['member_id'] : 0;
            $nextId = $lastId + 1;
            return $sruPrefix . $nextId;
        }

        public static function delete($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "DELETE FROM member WHERE member_id = :member_id");
            $statement->execute([':member_id' => $member_id]);
            
            if ($statement->rowCount() === 0) {
                throw new \Exception('Player profile was not deleted.');
            }
            return true;
        }

        public static function playerProfile($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT
                    m.*,
                    pp.*,
                    s.*,
                    sph.start_date,
                    sph.end_date
                FROM member m
                JOIN player_profile pp ON m.member_id = pp.member_id
                LEFT JOIN squad_player_history sph  ON m.member_id = sph.member_id 
                    AND sph.end_date IS NULL
                LEFT JOIN squad s ON sph.squad_id = s.squad_id
                WHERE m.member_id = :member_id
            ");

            $statement->execute([':member_id' => $member_id]);
            
            $result =  $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        // History to end date
        public static function updateHistoryEndDate($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "UPDATE squad_player_history h

                INNER JOIN squad s ON s.squad_id = h.squad_id
                SET h.end_date = s.end_date
                WHERE h.member_id = :member_id"
            );

            return $statement->execute([
                ':member_id' => $member_id
            ]);
        }

        public static function insertPlayerMatchStats($pdo, $match_id, $member_id, $stats){
            $statement = $pdo->prepare(
                "INSERT INTO player_match_stats (
                    match_id, member_id, minutes_played, tries, conversions, 
                    penalties, drop_goals, yellow_cards, red_cards
                )
                VALUES (
                    :match_id, :member_id, :minutes_played, :tries, :conversions, 
                    :penalties, :drop_goals, :yellow_cards, :red_cards
                )

                ON DUPLICATE KEY UPDATE
                    minutes_played = VALUES(minutes_played),
                    tries = VALUES(tries),
                    conversions = VALUES(conversions),
                    penalties = VALUES(penalties),
                    drop_goals = VALUES(drop_goals),
                    yellow_cards = VALUES(yellow_cards),
                    red_cards = VALUES(red_cards)
                ");

            $result = $statement->execute([
                ':match_id' => $match_id,
                ':member_id' => $member_id,
                ':minutes_played' => (int)$stats['minutes_played'],
                ':tries' => (int)$stats['tries'],
                ':conversions' => (int)$stats['conversions'],
                ':penalties' => (int)$stats['penalties'],
                ':drop_goals' => (int)$stats['drop_goals'],
                ':yellow_cards' => (int)$stats['yellow_cards'],
                ':red_cards' => (int)$stats['red_cards'],
            ]);
        
            return $result;
        }

        // Update player profile
        public static function updatePlayerProfile($pdo, $data)
        {
            $statement = $pdo->prepare(
                "UPDATE player_profile
                SET
                    height = :height,
                    weight = :weight,
                    position = :position,
                    player_availability_status = :player_availability_status
                WHERE member_id = :member_id"
            );

            return $statement->execute([
                ':height' => $data['height'] ?? null,
                ':weight' => $data['weight'] ?? null,
                ':position' => $data['position'] ?? null,
                ':player_availability_status' => $data['player_availability_status'] ?? 'Available',
                ':member_id' => $data['member_id']
            ]);
        }
    }
?>