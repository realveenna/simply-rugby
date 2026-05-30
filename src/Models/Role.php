<?php
    namespace Test\Models;

    use Test\Database;
    use PDO;
    
    class Role
    {
        protected $permissions = [];

        protected function __construct() 
        {
            $this->permissions = array();
        }
        
         // return a role object with associated permissions
        public static function getRolePerms($role_id)
        {
            $pdo = Database::getInstance()->getConnection();
            $role = new Role();
            $sql = "SELECT p.permission_name
                    FROM permission_role AS pr
                    JOIN permission AS p 
                        ON pr.permission_id = p.permission_id
                    WHERE pr.role_id = :role_id";

            $statement = $pdo->prepare($sql);
            $statement->execute(array(":role_id" => $role_id));

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $role->permissions[$row["permission_name"]] = true;
            }
            return $role;
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

        // Fetch all role name and id from the database and return as an array
        public static function getRole(){
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare("SELECT * FROM role");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        // Get member role id
        public static function getRoleIdByMemberId($pdo, $member_id)
        {
            $statement = $pdo->prepare(
                "SELECT role_id FROM member_role WHERE member_id = :member_id"
            );
            $statement->execute([':member_id' => $member_id]);
            $result = $statement->fetchColumn();
            
            if(!$result){
                return null;
            }
            return $result;
        }

        public static function getRoleIdByName($pdo, $role_name)
        {
            $statement = $pdo->prepare(
                "SELECT role_id FROM role WHERE role_name = :role_name"
            );
            $statement->execute([':role_name' => $role_name]);
            $result = $statement->fetchColumn();
            
            if(!$result){
                return null;
            }
            return $result;
        }
    }
?>
