<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Squad;
    use Test\Models\Address;
    use Test\Models\AccessControl;
    use Test\Models\Player;
    use Test\Database;
    
    class MemberController extends Controller
    {
        
        public function __construct()
        {
            parent::__construct();
        }
        
        // List of all members
        public function index()
        {
            // Role filter
            $role = $_GET['role'] ?? null;

            // Select all members
            $members = Member::selectAll($role);
            
            if($member_id = $_GET['member_id'] ?? null){

            }
            $this->render('members/index', [
                'members' => $members,
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
                    $data['squad'] = (int)$_POST['selectedSquad'];

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

         // View each member 
        public function view()
        {
            // PDO connection
            $pdo = $this->pdo;

            // If this member has children get children player details
            $childrenDetails = [];

            $member = $this->getMemberId($pdo);

            // Render to this page if updating 
            $updateUrl = '/members/update?member_id=' . $member['member_id'];

            if(!empty($member['address_id'] )){
                $address = Address::getAddressDetails($pdo, $member['address_id']);
            }

            // If this member has children get children player details
            if (str_contains($member['roles'], 'Parent')) {
                $children = AccessControl::getAccessPlayers($pdo, $member['member_id']);

                // Loop children IDs
                foreach ($children as $player_id) {
                    // Get full junior player details
                    $childrenDetails[] = Player::playerProfile($pdo, $player_id);
                }
            }
            

            try{
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // If delete button is pressed
                    $this->delete($pdo, $member->member_id);
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert(
                    'error', $e->getMessage(), 
                    '/members/update?member_id='.$member->member_id);
            }
           
            $this->render('/members/view', [
                'member' => $member ?? '',
                'address' => $address ?? [],
                'children' => $childrenDetails,
                'updateUrl' => $updateUrl,
            ]);
        }
         // Update member details
        public function update()
        {
            // PDO connection
            $pdo = $this->pdo;

            // Permission access
            $data = $this->getMemberId($pdo);
            $countries = Address::getCountries();

            // Set default array
            $address = [];
            $error = [];
            $player = [];
            $positions = MatchController::listAllPositions();

            // new member object
            $member = new Member();
            $member->member_id = $data['member_id'];
            $member->first_name = $data['first_name'];
            $member->last_name = $data['last_name'];
            $member->dob = $data['dob'] ?? '';
            $member->email = $data['email'] ?? '';
            $member->membership_status = $data['membership_status'] ?? 'active';
            $member->mobile_num = $data['mobile_num'] ?? '';
            $member->address_id = $data['address_id'] ?? null;
            $originalEmail = $member->email;

            // Get player profile if member is a player
            $player = Player::playerProfile($pdo, $member->member_id);


            // Get address
            if(!empty($member->address_id )){
                $address = Address::getAddressDetails($pdo, $member->address_id);
            }

            try{
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Begin Transaction
                    $pdo->beginTransaction();

                    $edit = trimPost('edit');

                    if($edit === 'personal_details'){
                        $member->first_name = trimPost('first_name');
                        $member->last_name = trimPost('last_name');
                        $member->dob = trimPost('dob');

                        // Validate no input
                        $error['first_name'] = ifEmpty($member->first_name, 'First name is required');
                        $error['last_name'] = ifEmpty($member->last_name, 'Last name is required');
                        $error['dob'] = ifEmpty($member->dob, 'Date of birth is required');
                        
                        // Valid age
                        $age = calcAge($member->dob);
                        if ($age < 18) {
                            $error['dob'] = 'Member must be at least 18 years old';
                        }

                        // Filter array for empty/null
                        $error = array_filter($error);

                        // No error then update
                        if(empty($error)){
                            $updated = $member->update($pdo);
                            if(!$updated){
                                throw new \ErrorException('Failed to update details');
                            }
                            // Commmit and success message
                            $pdo->commit();
                            
                            alert('success', 'Details Updated Successfully!', '/');
                            exit;
                        }
                    }

                    // edit player_details
                    if($edit === 'player_details'){
                        $data = [];
                        
                        $member_id = trimPost('member_id');
                        $height = trimPost('height');
                        $weight = trimPost('weight');
                        $position = trimPost('position');
                        $player_availability_status = trimPost('player_availability_status');

                        // Validate no input
                        $error['height'] = 
                            ifEmpty($height, 'Height is required');
                        $error['weight'] = 
                            ifEmpty($weight, 'Weight is required');
                        $error['position'] = 
                            ifEmpty($position, 'Position is required');
                        $error['player_availability_status'] = 
                            ifEmpty($player_availability_status, 'Availability status is required');

                        // Filter array for empty/null
                        $error = array_filter($error);

                        // No error then update
                        if(empty($error)){
                            // Set data to pass
                            $data = [
                                'height' => $height,
                                'weight' => $weight,
                                'position' => $position ?? '',
                                'player_availability_status' => $player_availability_status,
                                'member_id' => $member_id
                            ];


                            $updated = Player::updatePlayerProfile($pdo, $data);
                            if(!$updated){
                                throw new \ErrorException('Failed to update player profile');
                            }

                            // Commmit and success message
                            $pdo->commit();
                            
                            alert('success', 'Player Details Updated Successfully!', '/');
                            exit;
                        }

                    }
                    if($edit === 'contact'){
                        $member->email = trimPost('email');
                        $member->mobile_num = trimPost('mobile_num');


                        // Validate no input
                        $error['email'] = ifEmpty($member->email, 'Email is required');
                        $error['mobile_num'] = ifEmpty($member->mobile_num, 'Mobile number is required');
                        
                        // If invalid email format
                        if (!filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
                            $error['email'] = "Invalid email format";
                        }
                        else{
                            // Only check if email changed
                            if ($member->email !== $originalEmail) {
                                // Check if email already exists
                                $result = User::checkEmailExists($member->email ,'Email already registered. Please select different email.');

                                // If email already exists, display error message
                                $error['email'] = $result['emailError'] ?? '';
                            }
                        }

                        // Filter array for empty/null
                        $error = array_filter($error);

                        // No error then update
                        if(empty($error)){
                            $updated = $member->update($pdo);
                            if(!$updated){
                                throw new \ErrorException('Failed to update contact details');
                            }
                            // Commmit and success message
                            $pdo->commit();
                            alert('success', 'Contct Details Updated Successfully!', '/');
                        }
                    }
                    if($edit === 'address'){
                        $address['line1'] = trimPost('line1');
                        $address['line2'] = trimPost('line2');
                        $address['city'] = trimPost('city');
                        $address['postcode'] = trimPost('postcode');
                        $address['country'] = trimPost('country');

                        // Validate empty input
                        $error['line1'] = ifEmpty($address['line_1'], 'Address line 1 is required');
                        $error['city'] = ifEmpty($address['city'], 'City is required');
                        $error['postcode'] = ifEmpty($address['postcode'], 'Postcode is required');
                        $error['country'] = ifEmpty($address['country'], 'Country is required');

                        // Filter array for empty/null
                        $error = array_filter($error);

                        // No error then update
                        if(empty($error)){
                            // Returns address_id
                            $address_id = Address::insert($pdo,$address);
                            if(!$address_id){
                                throw new \ErrorException('Failed to update address details');
                            }

                            // Save new address_id to member
                            $member->address_id = $address_id;

                            // Update member record
                            $updated = $member->update($pdo);

                            if (!$updated) {
                                throw new \ErrorException('Failed to update member address');
                            }

                            // Commmit and success message
                            $pdo->commit();
                            
                            alert('success', 'Address Details Updated Successfully!', '/');
                        }
                        
                        // If delete button is pressed
                        $this->delete($pdo, $member->member_id);
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert(
                    'error', $e->getMessage(), 
                    '/members/update?member_id='.$member->member_id);
            }
           
            
            $this->render('/members/update', [
                'member' => $member ?? '',
                'player' => $player ?? '',
                'error' => $error,
                'data' => $data,
                'address' => $address,
                'countries' => $countries,
                'positions' => $positions ?? [],
            ]);
        }


        // Renewal
        public function renewal()
        {
            // PDO connection
            $pdo = $this->pdo;
            $error = [];

            // Get member_id with permission check
            $member = $this->getMemberId($pdo);

            $player = Player::playerProfile($pdo,$member['member_id']);

            try{
                if($_POST){
                    $member_id = trimPost('member_id');
                    $renewal = trimPost('renewal');

                    if(!$member_id){
                        abort(500, 'Unable to update membership');
                    }


                    // If is a junior player
                    if($player['section_id'] !== 3){
                        $consent = (int)$_POST['consent'];
                        if($consent !== 1){
                            $error['renewal'] = "Please tick the box to give consent.";
                        }
                    }

                    // No error
                    if(!array_filter($error)){
                        $pdo->beginTransaction();
                        // Update player membership status to inactive
                        if($renewal === 'decline'){
                            $update = Member::updateMembershipStatus($pdo, $member_id, 'inactive');
                            if(!$update){
                                throw new \Exception("Unable to update membership status.");
                            }
                            // Update player squad status to inactive
                            $update = Squad::updateSquadStatus($pdo, $member_id, 'Inactive');
                            if(!$update){
                                throw new \Exception("Unable to update squad status.");
                            }
                            // Commmit and success message
                            $pdo->commit();
                            alert('success', 'Membership has been updated successfully!', '/');
                            exit;
                        }
                    }
                }
            }
            catch (\Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error',$e->getMessage(), '/');
                exit;
            }

       
            $this->render('members/renewal', [
                'player' => $player,
            ]);
        }

        // delete a member 
        public static function delete($pdo, $member_id)
        {
            // Begin Transaction
            $pdo->beginTransaction();

            $action = trimPost('action');
            if($action === 'delete'){
                $delete = Member::delete($pdo,$member_id);
                if(!$delete){
                    abort(500,'Failed to delete member details');
                }
            }

            // Commmit and success message
            $pdo->commit();
            alert('success', 'Member has been removed successfully!', '/');
            exit;
        }


         // Protected function if member_id is mandatory
        // view and update
        protected function getMemberId($pdo){
            // IF member_id is requested by GET or POST
            if(isset($_GET['member_id']) || isset($_POST['member_id'])){

                // Set member_id
                $member_id = $_GET['member_id']?? ($_POST['member_id']);

                // Get member Details 
                $member = AccessControl::validateMemberAccess($pdo, $member_id);

                // Return as member Object
                return $member;
            }
            else{
                alert("error","Please select a member", '/members');
            }
        }
    }
?>