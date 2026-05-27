<?php
    namespace Test;

    class Auth
    {
        // Check if Club Chairperson or Membership Secretary
        public static function isAdmin()
        {
            return isset($_SESSION['rbac']) && ($_SESSION['rbac']->hasRole('Club Chairperson') ||
                $_SESSION['rbac']->hasRole('Membership Secretary'));
        }
        
        // Own profile
        public static function isOwner($currentUser, $member_id)
        {
            return $currentUser === $member_id;
        }

        // Parent/guardian access
        public static function hasPlayerAccess($member_id)
        {
            return in_array($member_id, $_SESSION['player_access'], true);
        }

        // Section Access
        public static function hasSectionAccess($section_id)
        {
            if (isset($_SESSION['rbac']) && hasRole('Club Chairperson')) {
                return true;
            }
            return in_array($section_id, $_SESSION['section_access'], true);
        }

        // Squad Access
        public static function hasSquadAccess($squad_id)
        {
            if (isset($_SESSION['rbac']) && hasRole('Club Chairperson')) {
                return true;
            }
            return in_array($squad_id, $_SESSION['squad_access'], true);
        }

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
                abort(403);
            }
        }
    }
?>