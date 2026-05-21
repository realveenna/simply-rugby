<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Squad;
    use Test\Models\Address;
    use Test\Database;
    
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

        public function registerMember()
        {
            $data =[
                'fname' => '',
                'lname' => '',
                'email' => '',
                'mobileNum' => '',
                'dob' => ''
            ]; 

            $errors = [
                'fname' => '',
                'lname' => '',
                'email' => '',
                'mobileNum' => '',
                'dob' => ''
            ];
            
            if ($_POST) {
                $data['fname'] = trimPost('fname');
                $data['lname'] = trimPost('lname');
                $data['email'] = trimPost('email');
                $data['mobileNum'] = trimPost('mobileNum');
                $data['dob'] = trimPost('dob');

                // Validate Empty Email and Password Input
                if (empty($data['fname'])) {
                    $errors['fname'] = "First name is required";
                }
                if (empty($data['lname'])) {
                    $errors['lname'] = "Last name is required";
                }
                if (empty($data['mobileNum'])) {
                    $errors['mobileNum'] = "Mobile number is required";
                }
                if (empty($data['dob'])) {
                    $errors['dob'] = "Date of birth is required";
                }

                // Check empty email
                if (empty($data['email'])) {
                    $errors['email'] = "Email is required";
                } else {
                    // If invalid email format
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = "Invalid email format";
                    }
                    else{
                        // Check if email already exists
                        $result = User::checkEmailExists($data['email'],'Email already registered. Please log in to continue.');

                        // If email already exists, display error message
                        $errors['email'] = $result['emailError'] ?? '';
                    }
                }
                
                
                // Check for member details errors
                if (!array_filter($errors))
                {
                    $pdo = Database::getInstance()->getConnection();
                    try{
                        $pdo->beginTransaction();
                    
                        // Create member object to be inserted to db
                        $member = new Member();
                        $member->first_name = ucwords($data['fname']);
                        $member->last_name = ucwords($data['lname']);
                        $member->dob = $data['dob'];
                        $member->mobile_num = $data['mobileNum'];
                        $member->membership_status = $data['membership_status'] ?? 'active';
                        $member->email = strtolower($data['email']);

                        // Check if member already exist before inserting
                        $memberExists = $member->validateInsert($pdo);
                        if ($memberExists) {
                            throw new \Exception("Member already exists.");
                        }
                        
                        // Insert member to db and store id for creating login
                        $insert = $member->insert($pdo);
                        if (!$insert) {
                            throw new \Exception("Failed to add to member.");
                        }

                        // Commit transaction
                        $pdo->commit();
                        
                        // Clear Errors
                        unset($errors);

                        // Success Message and redirect to create login page with member id
                        alert('success',
                            'Member created successfully.', 
                            '/members/create-login?member_id=' . $member->member_id);
                    }
                    catch (\Exception $e) {
                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                        }
                        alert('error',$e->getMessage(), '/register/member');
                        exit;
                    }
                }
            }
            $this->render('/register/member', [
                    'member' => $member ?? null,
                    'data' => $data,
                    'errors' => $errors
                ]);
        }

         // Create a login for a member
        public function createLogin()
        {
             // PDO connection
            $pdo = Database::getInstance()->getConnection();

            // Get member id from query
            $member_id = $_GET['member_id'] ?? null;

            // If no member id is provided, display members without login
            if($_GET['member_id'] === null){
                alert('error', 'No member selected. Please <a href="/members-no-login">select a member</a> to create login.', 'members-no-login');
                exit();
            }

            // Get all roles for dropdown
            $roles = Role::getRole();

             // Get all sqauds for dropdown
            $squads = Squad::getAllSquads($pdo);

            // Create login object to be inserted to db
            $member_login = new User();

            // Set member id for creating login
            $member_login->member_id = $member_id; 
            $member_login->email =  Member::selectEmail($member_id) ?? '';
            $email = $member_login->email ?? '';
            
            $data = [
                'member_id' => $member_id ?? null,
                'email' => $email ?? null,
                'password' => '',
                'selectedRole' => '',
                'selectedSquad' => '',
                'section' => null
            ];


            $errors = [
                'email' => '',
                'selectedRole' => '',
                'selectedSquad' => ''
            ];

            if ($_POST) {
                // Generate and hash password
                $password = 'Password123!';
                $hashedPassword = hashPassword($password);
                
                $data['password'] = trim($hashedPassword);
                $data['email'] = trimPost('email');
                $data['selectedRole'] = (int)($_POST['selectedRole'] ?? '');
                $data['selectedSquad'] = (int)($_POST['selectedSquad'] ?? '');


                // Validate Empty Email and Other Inputs
                if ($data['selectedRole'] <= 0) {
                    $errors['selectedRole'] = "Please select a role";
                }

                // Check if email already exists
                $result = User::checkEmailExists($data['email'],'Email is not yet registered.');

                if(!$result['emailError']){
                    $errors['email'] = 'Email is not yet registered. Please register member first before creating login.';
                }

                // If selected role is a fixture or section secretary get assigned section
                if($data['selectedRole'] === 3 || $data['selectedRole'] === 4){
                    $data['section'] = (int)$_POST['selectSection'] ?: 1;

                    if ($data['selectedRole'] <= 0) {
                        $errors['selectedRole'] = "Please select a section";
                    }
                }

                // If selected role is a coach get assigned squad_id
                if($data['selectedRole'] === 5){
                    $data['squad'] = (int)$_POST['selectedSquad'] ?: 1;

                    if ($data['selectedSquad'] <= 0) {
                        $errors['selectedSquad'] = "Please select a squad";
                    }
                }

                // No errors, add to database
                if(!array_filter($errors)){
                    try{
                        $pdo->beginTransaction();

                        $user = new User();
                        $user->email = $data['email'];
                        $user->password = $data['password'];
                        $user->member_id = $data['member_id'];

                        $exists = User::findMemberLogin($pdo, $user->member_id);
                        
                        if($exists){
                            throw new \Exception("This user has an existing login details.");
                        }

                        //Insert login details to db
                        $insert_login = User::insertNewMemberLogin($pdo, $user->member_id , $user->password);
                        if(!$insert_login){
                            throw new \Exception("Failed to insert member login.");
                        }

                        //Insert role for member
                        $insert_role = Role::insertMemberRoles($pdo, $user->member_id, $data['selectedRole']);
                        if($insert_role === 0){
                            throw new \Exception("Failed to insert member role.");
                        }

                        // Get Role ID
                        $role_id = Role::getRoleIdByMemberId($pdo, $user->member_id);

                        // If selected role is a fixture or section secretary set to assigned section
                        if($data['selectedRole'] === 3 || $data['selectedRole'] === 4){
                            $insert_secretary = Squad::insertSectionAdmin($pdo, $user->member_id, $data['section']);
                            if(!$insert_secretary){
                                throw new \Exception("Failed to insert secretary to section");
                            }  
                        }

                        // If selected role is a coach add to assigned squad
                        if($data['selectedRole'] === 5){
                            $insert_coach = Squad::insertSquadMember($pdo, $user->member_id, $data['squad'], $role_id);
                            if(!$insert_coach){
                                throw new \Exception("Failed to insert coach to squad");
                            }    
                        }
    
                        //Commit transaction
                        $pdo->commit();

                        // Send email to reset password
                        // MailController::newResetPassword($data['first_name'], $user->email, $user->member_id, $user->password);
                        alert('success', 'Login Account has been Successfully Created!', '/members?id='.$user->member_id);
                    }
                    catch (\Exception $e) {
                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                        }
                        alert('error', $e->getMessage(), '/members');
                    }
                }
            }
            
            $this->render('/members/create-login', [
                'data' => $data,    
                'errors' => $errors,
                'roles' => $roles,
                'squads' => $squads
            ]);
        }
    }
?>