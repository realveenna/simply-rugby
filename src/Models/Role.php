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

###############
        public function hasPermission($permission)
        {
            return isset($this->permissions[$permission]) &&
                $this->permissions[$permission] === true;
        }
    ######################
        
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

        // check if a permission is set
        public function hasPerm($permission)
        {
            return isset($this->permissions[$permission]);
        }

        // check if role already exist
        public static function hasRole($role_name)
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

        // Fetch member role name
        public static function getMemberRoleName($member_id){
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare
            (
                "SELECT r.role_name FROM member_role mr
                JOIN role r ON mr.role_id = r.role_id
                WHERE mr.member_id = :member_id
            ");

            $statement->execute([
                ':member_id' => $member_id
            ]);
            return $statement->fetchAll(PDO::FETCH_COLUMN);
        }
        



        /////
        //// mine below

        public static function insertRole($pdo, $data)
        {
            $pdo = Database::getInstance()->getConnection();

            $statement = $pdo->prepare("INSERT INTO member_role(member_id, role_id)
                VALUES (:member_id, :role_id)");

            $statement->bindValue(':member_id', $data['member_id'], PDO::PARAM_INT);
            $statement->bindValue(':role_id', $data['selectedRole'], PDO::PARAM_INT);

            return $statement->execute();
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

        // Get all Permissions
        public static function getAllPermissions($pdo)
        {
            $pdo = Database::getInstance()->getConnection();

            try{
                $statement = $pdo->prepare("SELECT * FROM permission");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }
?>
