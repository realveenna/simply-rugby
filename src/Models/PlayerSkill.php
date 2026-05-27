<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class PlayerSkill
    {    
        public int $player_skill_id;
        public int $training_session_id;
        public int $member_id;
        public int $skill_id;
        public string $skill_name;
        public int $skill_category_id;
        public string $skill_category_name;
        public int $coach_member_id;
        public int $rating;
        public string $date_recorded;

        public function __construct($data = [])
        {
            $this->player_skill_id = $data['player_skill_id'] ?? 0;
            $this->training_session_id = $data['training_session_id'] ?? 0;
            $this->member_id = $data['member_id'] ?? 0;
            $this->skill_id = $data['skill_id'] ?? 0;
            $this->coach_member_id = $_SESSION['user']['member_id'] ?? 0;
            $this->skill_name = $data['skill_name'] ?? '';
            $this->skill_category_id = $data['skill_category_id'] ?? 0;
            $this->skill_category_name = $data['skill_category_name'] ?? '';
            $this->rating = $data['rating'] ?? 0;
            $this->date_recorded = $data['date_recorded'] ?? '';
        }

        // Insert player skill rating
        public static function insertSkillRating($pdo,
            $training_session_id, $player_id, $skill_id, $coach_member_id, $rating)
        {
            $statement = $pdo->prepare
            (
                "INSERT INTO player_skill 
                    (training_session_id, member_id, skill_id, coach_member_id, rating)
                VALUES (:training_session_id, :member_id, :skill_id, :coach_member_id, :rating)"
            );

            return $statement->execute([
                ':training_session_id' => $training_session_id,
                ':member_id' => $player_id,
                ':skill_id' => $skill_id,
                ':coach_member_id' => $coach_member_id,
                ':rating' => $rating
            ]);
        }

         // Calculate average rating of player skills
        public function calculateAverageRatingByCategory($pdo)
        {
            $statement = $pdo->prepare
            (
                "SELECT 
                    sc.skill_category_id,
                    sc.skill_category_name,

                    -- Round average
                    ROUND(COALESCE(AVG(ps.rating), 0),1) AS average_rating

                FROM skill_category sc
                LEFT JOIN skill s ON sc.skill_category_id = s.skill_category_id
                LEFT JOIN player_skill ps ON s.skill_id = ps.skill_id AND ps.member_id = :member_id

                GROUP BY 
                    sc.skill_category_id,
                    sc.skill_category_name
                ORDER BY sc.skill_category_name"
            );

            $statement->execute([
                ':member_id' => $this->member_id
            ]);

            $ave = $statement->fetchAll(PDO::FETCH_ASSOC);

            // No Skills to calculate
            if (!$ave) {
                return false;
            }

            return $ave;
        }

         // Get skill of player
        public function getPlayerSkill($pdo){
            $statement = $pdo->prepare(
                "SELECT
                    ps.*,
                    s.skill_name,
                    sc.skill_category_name

                FROM player_skill ps

                INNER JOIN skill s
                    ON ps.skill_id = s.skill_id

                INNER JOIN skill_category sc
                    ON s.skill_category_id = sc.skill_category_id

                INNER JOIN member m
                    ON ps.member_id = m.member_id

                WHERE ps.member_id = :member_id

                ORDER BY
                    sc.skill_category_name ASC,
                    s.skill_name ASC"
            );

               $statement->execute([
                    ':member_id' => $this->member_id
                ]);


            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>