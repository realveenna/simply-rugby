<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Role;
    use Test\Database;
    
    class Auth extends Controller
    {
        public function __construct()
        {
            
        }
        
        ####
        // Check if Club Chairperson or Membership Secretary
        public static function isAdmin()
        {
            return
                $_SESSION['rbac']->hasRole('Club Chairperson') ||
                $_SESSION['rbac']->hasRole('Membership Secretary');
        }

        // // Check if Fixture or Section Secretary
        // public static function isSection()
        // {
        //     return
        //         $_SESSION['rbac']->hasRole('Fixture Secretary') ||
        //         $_SESSION['rbac']->hasRole('Section Secretary');
        // }
        
        // Own profile
        public static function isOwner($currentUser, $member_id)
        {
            return $currentUser == $member_id;
        }

        // Parent/guardian access
        public static function hasPlayerAccess($member_id)
        {
            return in_array($member_id, $_SESSION['player_access']);
        }

        // Squad Access
        public static function hasSectionAccess($section_id)
        {
            return in_array($section_id, $_SESSION['section_access']);
        }

        // Section Access
        public static function hasSquadAccess($squad_id)
        {
            return in_array($squad_id, $_SESSION['squad_access']);
        }

        #####

        // Check is user has permissions true of false
        public static function hasPermission($permission)
        {
            return isset($_SESSION['rbac']) && $_SESSION['rbac']->hasPermission($permission);
        }

        // Check role name from session
        public static function hasRole($roleName)
        {
            return isset($_SESSION['rbac']) && $_SESSION['rbac']->hasRole($roleName);
        }

        // Check if user is authorize for a permission else redirect 
        public static function authorize($permission)
        {
            if (!self::hasPermission($permission))
            {
                $error = new Error();
                $error->forbidden();
                exit;
            }
        }
    }
?>