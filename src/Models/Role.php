<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Role
    {
        private $roles = [];
        
        protected $permissions;

        protected function __construct() 
        {
            $this->permissions = array();
        }

         // return a role object with associated permissions
        public static function getRolePerms($role_id)
        {
            $pdo = Database::getInstance()->getConnection();
            $role = new Role();
            $sql = "SELECT t2.perm_desc FROM role_perm AS t1
                    JOIN permissions AS t2 ON t1.perm_id = t2.perm_id
                    WHERE t1.role_id = :role_id";

            $statement = $pdo->prepare($sql);
            $statement->execute(array(":role_id" => $role_id));

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $role->permissions[$row["perm_desc"]] = true;
            }
            return $role;
        }

        // check if a permission is set
        public function hasPerm($permission)
        {
            return isset($this->permissions[$permission]);
        }
        // check if role already exist
        private static function hasRole($role_name)
        {
            $pdo = Database::getInstance()->getConnection();

            $sql = "SELECT count(role_id) AS role_count, role_id FROM roles WHERE role_name = :role_name";
            $statement = $pdo->prepare($sql);
            $statement->execute(array(":role_name" => $role_name));

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                if ($row["role_count"] > 0) {
                    return true;
                }
            }
            return false;
        }

        // insert array of roles for specified member id
        public static function insertMemberRoles($pdo, $member_id, $role_id)
        {
            $pdo = Database::getInstance()->getConnection();

            // Using INSERT IGNORE to avoid duplicate entry 
            $sql = "INSERT IGNORE INTO member_role (member_id, role_id) 
                VALUES (:member_id, :role_id)";
                
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':member_id' => $member_id,
                ':role_id' => $role_id
            ]);
            
            return $statement->rowCount();
        }

        // delete ALL roles for specified member id
        public static function deleteMemberRoles($member_id)
        {
            $pdo = Database::getInstance()->getConnection();

            $sql = "DELETE FROM member_role WHERE member_id = :member_id";
            $statement = $pdo->prepare($sql);
            return $statement->execute(array(":member_id" => $member_id));
        }

        // Fetch all role name and id from the database and return as an array
        public static function getRole(){
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare("SELECT * FROM role");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
