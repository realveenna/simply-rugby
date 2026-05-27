<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class PlayerParent extends Guardian
    {
        public $member_id;

        public function __construct($data)
        {
            parent::__construct($data);
            $this->member_id = $data['member_id'] ?? null;
        }

        public function getMemberId()
        {
            return $this->member_id;
        }

        public static function getParent($pdo, $email)
        {
            $statement = $pdo->prepare(
                "SELECT m.*, pc.*
                FROM member m
                JOIN player_contact pc ON m.member_id = pc.member_id
                WHERE m.email = :email AND pc.is_primary = 1
                LIMIT 1"
            );

            $statement->execute([':email' => $email]);
            $data = $statement->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return $data;
            }
            return null;
        }
    }
?>