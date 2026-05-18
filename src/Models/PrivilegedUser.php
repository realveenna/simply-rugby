<?php
    namespace Test\Models;

    use Test\Database;
    use Test\Models\Role;
    use PDO;

    class PrivilegedUser extends User
    {
        private $roles = [];

        public function __construct()
        {
            parent::__construct();
        }

        // override User method
        public static function getPrivilegedMember($member_id)
        {
            $pdo = Database::getInstance()->getConnection();

            $sql = "SELECT 
                        *,
                        CONCAT (first_name, ' ', last_name) AS full_name
                    FROM member WHERE member_id = :member_id";
            $sth = $pdo->prepare($sql);
            $sth->execute(array(":member_id" => $member_id));
            $result = $sth->fetchAll();

            if (!empty($result)) {
                $privUser = new PrivilegedUser();
                $privUser->member_id = $result[0]["member_id"];
                $privUser->member_name = $result[0]["full_name"];
                $privUser->email = $result[0]["email"];
                $privUser->initRoles();
                return $privUser;
            }
            return false;
        }

        // populate roles with their associated permissions
        protected function initRoles()
        {
            $pdo = Database::getInstance()->getConnection();

            $this->roles = array();
            $sql = "SELECT t1.role_id, t2.role_name FROM member_role AS t1
                    JOIN role AS t2 ON t1.role_id = t2.role_id
                    WHERE t1.member_id = :member_id";
            $sth = $pdo->prepare($sql);
            $sth->execute(array(":member_id" => $this->member_id));

            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $this->roles[$row["role_name"]] = Role::getRolePerms($row["role_id"]);
            }
        }

        // check if user has a specific privilege
        public function hasPrivilege($perm)
        {
            foreach ($this->roles as $role) {

                if ($role->hasPerm($perm)) {
                    return true;
                }
            }

            return false;
        }


        ############################
        public function hasPermission($permission)
        {
            foreach ($this->roles as $role) {
                if ($role->hasPermission($permission)) {
                    return true;
                }
            }
            return false;
        }
        #################################

        // check if a user has a specific role
        public function hasRole($role_name)
        {
            return isset($this->roles[$role_name]);
        }

        // insert a new role permission association
        public static function insertPerm($role_id, $perm_id)
        {
            $pdo = Database::getInstance()->getConnection();
            

            $sql = "INSERT INTO role_perm (role_id, perm_id) VALUES (:role_id, :perm_id)";
            $sth = $pdo->prepare($sql);
            return $sth->execute(array(":role_id" => $role_id, ":perm_id" => $perm_id));
        }

        // delete ALL role permissions
        public static function deletePerms()
        {
            $pdo = Database::getInstance()->getConnection();
            

            $sql = "TRUNCATE role_perm";
            $sth = $pdo->prepare($sql);
            return $sth->execute();
        }
    }
?>