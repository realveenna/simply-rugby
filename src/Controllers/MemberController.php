<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    
    class MemberController extends Controller
    {
        
        private $roles;
        public $email;
        public $password;
        public $confirmPassword;

        public function __construct()
        {
            
        }
        // List of all members
        public function index()
        {
            $members = Member::selectAll();

            foreach ($members as &$member ){
                $member['roles'] = Role::getMemberRoleName($member['member_id']);
            }

            $this->render('members/index', [
                'members' => $members
            ]);
        }

        public function membersNoLogin()
        {
            $members = Member::selectAllNoLogin();

            $this->render('members/no-login', [
                'members' => $members
            ]);
        }
    }
?>