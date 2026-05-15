<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Player
    {
        private $application_id;
        private $address_id;
        private $doctor_id;
        private $first_name;  
        private $last_name;
        private $dob;
        private $nickname;
        private $playerHeight;
        private $playerWeight;
        private $email;
        private $mobile_num;
        private $sru_number;

     
        public function __construct()
        {

        }

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
                    s.squad_name,
                    s.squad_type,
                    sph.start_date,
                    sph.end_date
                FROM member m
                JOIN player_profile pp
                    ON m.member_id = pp.member_id
                LEFT JOIN squad_player_history sph 
                    ON m.member_id = sph.member_id
                    AND sph.end_date IS NULL
                LEFT JOIN squad s
                    ON sph.squad_id = s.squad_id
                WHERE m.member_id = :member_id
            ");

            $statement->execute([':member_id' => $member_id]);
            
            $result =  $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }
?>