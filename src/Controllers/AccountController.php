<?php
    namespace Test\Controllers;

    use Test\Models\User;
    use Test\Models\Address;
    use Test\Models\AccessControl;
    use Test\Models\Player;
    use Test\Models\Member;


    class AccountController extends MemberController
    {

        public function __construct()
        {
            parent::__construct();
        }

        // Reset Password
        public function resetPassword()
        {
            $pdo = $this->pdo;

            // Set Default variables
            $member_id = "";
            $resetEmail = "";

            $passwords = [
                'old' => '',
                'new' => ''
            ];

            $errors = [
                'emailErr' => '',
                'oldPassword' => '',
                'newPassword' => ''
            ];

        
            try{
                // If member_id is set
                if(isset($_GET['member_id'])){
                    $member_id = $_GET['member_id'];

                    // Find Email
                    $resetEmail = Member::selectEmail($member_id);
                    if(!$resetEmail){
                        throw new \ErrorException('Email Not Found!');
                    }
                }

                if($_POST){
                    $pdo->beginTransaction();
                    $resetEmail = trimPost('resetEmail');
                    $passwords['old'] = trimPost('oldPassword');
                    $passwords['new'] = trimPost('newPassword');

                     // Validate Empty Email and Password Input
                    if (empty($passwords['old'])) {
                        $errors['oldPassword'] = "Password is required";
                    }
                    if (empty($passwords['new'])) {
                        $errors['newPassword'] = "Please enter new password";
                    }

                    // Get member_id
                    $member_id = Member::selectIdByEmail($resetEmail);
                    if(!$member_id){
                        throw new \ErrorException('Email Not Found!');
                    }

                    // Get login details
                    $login = User::findMemberLogin($pdo, $member_id);
                    if(!$login){
                        throw new \ErrorException('Login Details Not Found');
                    }
                
                    $member = new User;
                    $member = $member->getCredentials($member_id);
                    
                    // Hash old password 
                    $oldPasswordHash = hashPassword($passwords['old']);

                    // Hash new password 
                    $newPasswordHash = hashPassword($passwords['new']);

                    // Correct Password
                    if($member['pass'] === $oldPasswordHash){
                        // Check password length
                        if(strlen($passwords['new']) < 8){
                            $errors['newPassword'] = 'Password must be atleast 8 characters<br>';
                        }
                        
                        // More password strength validation
                        if(!preg_match("#[0-9]+#", $passwords['new'])) {
                            $errors['newPassword'] .= "Your Password Must Contain At Least 1 Number!<br>";
                        }
                        if(!preg_match("#[A-Z]+#", $passwords['new'])) {
                            $errors['newPassword'] .= "Your Password Must Contain At Least 1 Capital Letter!<br>";
                        }
                        if(!preg_match("#[a-z]+#", $passwords['new'])) {
                            $errors['newPassword'] .= "Your Password Must Contain At Least 1 Lowercase Letter!<br>";
                        }

                        // Check if same password
                        if($passwords['new'] === $passwords['old']){
                            $errors['newPassword'] = "Please a different password.";
                        }
                        
                    }
                    // Incorrect Password
                    else{
                        $errors['oldPassword'] = 'Incorrect Password.';
                    }

                    // No error update password
                    if(!array_filter($errors)){
                        $updated = User::updatePassword($pdo, $member_id, $newPasswordHash);
                        if(!$updated){
                            throw new \Exception('Password update failed');
                        }
                        $pdo->commit();
                        alert('success', 'Your password has been changed!', '/');
                    }
                }
            }
            catch (\Exception $e){
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    alert('error', $e->getMessage(), '/account/reset-password');
            }
            
            $this->render('account/reset-password', [
                'resetEmail' => $resetEmail,
                'errors' => $errors,
                'passwords' => $passwords
            ]);
        }

        // Index render with permission own account
        public function index()
        {
            // PDO connection
            $pdo = $this->pdo;

            // If this member has children get children player details
            $childrenDetails = [];

            // Redirect to account
            $updateUrl = '/account';

            $member = Member::getMemberDetails($pdo, $this->member_id);

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
                    if($_POST['action']){
                        $action = $_POST['action'];
                        // If delete button is pressed
                        if($action === 'delete'){
                            Member::delete($pdo, $member->member_id);
                        }
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
           
            $this->render('/members/view', [
                'member' => $member ?? '',
                'address' => $address ?? [],
                'children' => $childrenDetails,
                'updateUrl' => $updateUrl
            ]);
        }

        // render Update member details
        public function update()
        {
            // PDO connection
            $pdo = $this->pdo;

            $data = Member::getMemberDetails($pdo, $this->member_id);
            $address = [];
            $countries = Address::getCountries();

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

            if(!empty($member->address_id )){
                $address = Address::getAddressDetails($pdo, $member->address_id);
            }
            
            $error = [];
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
                'error' => $error,
                'data' => $data,
                'address' => $address,
                'countries' => $countries
            ]);
        }
    }
?>