<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    
    class MemberController extends Controller
    {
        
        private $roles;
        public $email;
        public $password;
        public $confirmPassword;

        public function __construct()
        {
            
        }
        public function index()
        {
            $members = Member::selectAll();

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