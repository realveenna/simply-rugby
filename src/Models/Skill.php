<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Skill
    {    
        public int $skill_id;
        public string $skill_name;
        public int $skill_category_id;
        public string $skill_category_name;

        public function __construct($data = [])
        {
            $this->skill_id = $data['skill_id'] ?? 0;
            $this->skill_name = $data['skill_name'] ?? '';
            $this->skill_category_id = $data['skill_category_id'] ?? 0;
            $this->skill_category_name = $data['skill_category_name'] ?? '';
        }

        // Get category of a skill
        public function getCategoryBySkillId($pdo)
        {
            $statement = $pdo->prepare
            (
                "SELECT 
                    sc.*
                FROM skill s
                JOIN skill_category sc
                    ON sc.skill_category_id = s.skill_category_id
                WHERE s.skill_id = :skill_id"
            );

            return $statement->execute([
                ':skill_id' => $this->skill_id,
            ]);
        }

        // Get all categories
        public static function getCategoriesAndSkills($pdo)
        {
            $statement = $pdo->prepare
            (
                "SELECT
                    sc.skill_category_id,
                    sc.skill_category_name,
                    s.skill_id,
                    s.skill_name

                FROM skill_category sc
                LEFT JOIN skill s ON sc.skill_category_id = s.skill_category_id

                ORDER BY
                    sc.skill_category_name ASC"
            );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get skills categories
        public static function getSkillCategories($pdo)
        {
            $statement = $pdo->prepare(
                "SELECT
                    skill_category_id,
                    skill_category_name

                FROM skill_category

                ORDER BY skill_category_name ASC"
            );

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get all skills and their category
        public function selectAll($pdo)
        {
            $statement = $pdo->prepare
            (
                "SELECT 
                    s.skill_id,
                    s.skill_name,
                    sc.skill_category_id,
                    sc.skill_category_name

                FROM skill s

                JOIN skill_category sc
                    ON sc.skill_category_id = s.skill_category_id

                ORDER BY 
                    sc.skill_category_name,
                    s.skill_name"
            );

            $statement->execute([
                ':skill_id' => $this->skill_id,
            ]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

         // Get all skills and their category
        public function getSkillById($pdo)
        {
            $statement = $pdo->prepare
            (
                "SELECT 
                    s.skill_id,
                    s.skill_name,
                    sc.skill_category_id,
                    sc.skill_category_name

                FROM skill s

                JOIN skill_category sc
                    ON sc.skill_category_id = s.skill_category_id

                WHERE s.skill_id = :skill_id"
            );

            $statement->execute([
                ':skill_id' => $this->skill_id,
            ]);

            return $statement->fetch(PDO::FETCH_ASSOC);
        }


       
    }
?>