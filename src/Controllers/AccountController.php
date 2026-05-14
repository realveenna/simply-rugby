<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Role;
    use Test\Models\Address;
    use Test\Models\MedicalInformation;
    use Test\Models\Application;
    use Test\Models\Doctor;
    use Test\Models\Guardian;
    use Test\Models\Member;
    use Test\Models\PlayerParent;

    use Test\Database;
    use PDO;


    class AccountController extends Controller
    {

        private $roles;
        public $email;
        public $password;
        public $confirmPassword;


        public function __construct()
        {
            
        }

        // Reset Password
        public function resetPassword()
        {
            $pdo = Database::getInstance()->getConnection();

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
                    
                    // Check password       
                    $salt ="4g£yc7!L(";

                    // Hash old password 
                    $oldPasswordHash = md5($passwords['old'] . $salt);

                    // Hash new password 
                    $newPasswordHash = md5($passwords['new'] . $salt);

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
                    die($e->getMessage());
            }
            
            $this->render('account/reset-password', [
                'resetEmail' => $resetEmail,
                'errors' => $errors,
                'passwords' => $passwords
            ]);
        }

//         public function register()
//         {
//             $relationships = [
//                 'Mother',
//                 'Father',
//                 'Guardian',
//                 'Step Parent',
//                 'Grandparent',
//                 'Aunt',
//                 'Uncle',
//                 'Sibling',
//                 'Partner',
//                 'Spouse',
//                 'Carer',
//                 'Other'
//             ];

//             // Get condition array
//             $medicalInformation = MedicalInformation::viewAllCondition();

//              // Get allergy array
//             $allergies = MedicalInformation::viewAllAllergy();

//             $formNum = 1;
//             $age = '';
//             $isJunior = '';
//             $nok = 'Next of Kin';
//             $sameAddress = false;
//             $apply_coach = '';
           
//             // Personal Details
//             $fName  = '';
//             $lName  = '';
//             $dob = '';
//             $playerNickname = '';
//             $playerHeight = '';
//             $playerWeight = '';
                        
//             $fNameErr  = '';
//             $lNameErr  = '';
//             $dobErr = '';
//             $playerHeightErr = '';
//             $playerWeightErr = '';

//             $nokFName = '';
//             $nokLName = '';
//             $nokFNameErr = '';
//             $nokLNameErr = '';

//             $nokFNameSecondary = '';
//             $nokLNameSecondary = '';
//             $nokFNameSecondaryErr = '';
//             $nokLNameSecondaryErr = '';

//             $nokRelationship = '';
//             $nokRelationshipErr = '';
//             $nokRelationshipSecondary = '';
//             $nokRelationshipSecondaryErr = '';

//             $email = '';
//             $emailErr = '';

//             $mobileNum = '';
//             $mobileNumErr = '';
//             $mobileNumSecondary = '';
//             $mobileNumSecondaryErr = '';
            
//             $line1 = $line2 = $city = $postcode = $country = "";
//             $line1Err = $line2Err = $cityErr =  $postcodeErr = $countryErr = "";

//             $line1Secondary = $line2Secondary = $citySecondary = $postcodeSecondary = $countrySecondary = "";
//             $line1SecondaryErr = $line2SecondaryErr = $citySecondaryErr =  $postcodeSecondaryErr  = $countrySecondaryErr = "";

//             // Medical Information Array
//             $medicalInformationData = [];
//             $allergyData = [];

//             $currentCondition = [];
//             $pastCondition = [];

//             // Doctor Information
//             $doctor  = '';
//             $doctorErr  = '';
//             $doctorNum  = '';
//             $doctorNumErr = '';
//             $line1Doctor  = '';
//             $line1DoctorErr  = '';
//             $line2Doctor  = '';
//             $line2DoctorErr  = '';
//             $cityDoctor  = '';
//             $cityDoctorErr  = '';
//             $postcodeDoctor  = '';
//             $postcodeDoctorErr  = '';
//             $countryDoctor = '';
//             $countryDoctorErr  = '';

//             if ($_POST) {
//                 // $fName = trimPost('fName') ?? '';
//                 // $lName = trimPost('lName') ?? '';
//                 // $dob = trimPost('dob') ?? '';
//                 // $playerNickname = trimPost('playerNickname') ?? '';
//                 // $playerHeight = trimPost('playerHeight') ?? '';
//                 // $playerWeight = trimPost('playerWeight') ?? '';

//                 // $nokFName = trimPost('nokFName') ?? '';
//                 // $nokLName = trimPost('nokLName') ?? '';
//                 // $nokRelationship = trimPost('nokRelationship') ?? '';         
//                 $apply_coach = isset($_POST['apply_coach']) ?? 0 ;         
                
//                 // $nokFNameSecondary = trimPost('nokFNameSecondary') ?? '';
//                 // $nokLNameSecondary = trimPost('nokLNameSecondary') ?? '';
//                 // $nokRelationshipSecondary = trimPost('nokRelationshipSecondary') ?? '';
//                 // $email = trimPost('email') ?? '';
//                 // $mobileNum = trimPost('mobileNum') ?? '';
//                 // $mobileNumSecondary  = trimPost('mobileNumSecondary') ?? '';

//                 // $line1 = trimPost('line1') ?? '';
//                 // $line2 = trimPost('line2') ?? '';
//                 // $city = trimPost('city') ?? '';
//                 // $postcode = strtoupper(trimPost('postcode')) ?? '';
//                 // $country = trimPost('country') ?? '';

//                 // $line1Secondary = trimPost('line1Secondary') ?? '';
//                 // $line2Secondary = trimPost('line2Secondary') ?? '';
//                 // $citySecondary  = trimPost('citySecondary') ?? '';
//                 // $postcodeSecondary = strtoupper(trimPost('postcodeSecondary') )?? '';
//                 // $countrySecondary = trimPost('countrySecondary') ?? '';

//                 // $sameAddress = isset($_POST['sameAddress']);
//                 // $isJunior = isset($_POST['isJunior']) && $_POST['isJunior'] === '1';
//                 // // Medical Data
//                 // $currentCondition = $_POST['currentCondition'] ?? [];
//                 // $pastCondition = $_POST['pastCondition'] ?? [];

//                 // // Allergies
//                 // $allergyData = $_POST['allergies'] ?? [];

//                 // // Doctor Data
//                 // $doctor = trimPost('doctor');
//                 // $doctorNum = trimPost('doctorNum');
//                 // $line1Doctor = trimPost('line1Doctor');
//                 // $line2Doctor = trimPost('line2Doctor');
//                 // $cityDoctor = trimPost('cityDoctor');
//                 // $postcodeDoctor = strtoupper(trimPost('postcodeDoctor')); 
//                 // $countryDoctor = trimPost('countryDoctor'); 
//  $fName = 'Jay';
//  $lName = 'McGregor';
//  $dob = '2020-03-22';
//  $playerNickname = 'Dan';
//  $playerHeight = '180';
//  $playerWeight = '82';
//  $isJunior = true;

//  $nokFName = 'Julia';
//  $nokLName = 'McGregor';
//  $nokRelationship = 'Mother';

//  $nokFNameSecondary = 'Peter';
//  $nokLNameSecondary = 'McGregor';
//  $nokRelationshipSecondary = 'Father';

//  $email = 'laura.mcgregor@example.com';
//  $mobileNum = '07777123456';
//  $mobileNumSecondary = '07888999888';  

//  $line1 = '12 King Street';
//  $line2 = '';
//  $city = 'Leeds';
//  $postcode = 'LS1 4AB';
//  $country = 'United Kingdom';

//  $line1Secondary = '';
//  $line2Secondary = '';
//  $citySecondary = '';
//  $postcodeSecondary = '';
//  $countrySecondary = '';

//  $sameAddress = true;
// //  

//  $currentCondition = [1];       
//  $pastCondition    = [];      
//  $allergyData      = [2];   

//  $doctor = "Dr Ahmed";
//  $doctorNum = "07900123456";
//  $line1Doctor = "22 Health Centre";
//  $line2Doctor = "";
//  $cityDoctor = "Leeds";
//  $postcodeDoctor = "LS2 9JT";
//  $countryDoctor = "United Kingdom";
             



//                 // Default data to be passed
//                 $data = [
//                     // Player Data
//                     'fName' => $fName,
//                     'lName' => $lName,
//                     'dob' => $dob,
//                     'playerNickname' => $playerNickname,
//                     'playerHeight' => $playerHeight,
//                     'playerWeight' => $playerWeight,
//                     'email' => $isJunior ? null : $email,

//                     // Primary/Foreign Key
//                     'application_id' => null, 
//                     'address_id' => null, 
//                     'doctor_id' => null,
//                     'mobileNum' => $mobileNum, 

//                     // Guardian 2
//                     'mobileNumSecondary' => $mobileNumSecondary,

//                     // Address
//                     'line1' => $line1,
//                     'line2' => $line2,
//                     'city' => $city,
//                     'postcode' => $postcode,
//                     'country' => $country
//                 ];

//                 // Doctor Address to be passed
//                 $doctorAddress = [
//                     'doctor_address_id' => null,
//                     'line1' => $line1Doctor,
//                     'line2' => $line2Doctor,
//                     'city' => $cityDoctor,
//                     'postcode' => $postcodeDoctor,
//                     'country' => $countryDoctor
//                 ];
       
//                 $doctorData = [
//                     'doctor_name' => $doctor,
//                     'doctor_tel' => $doctorNum,
//                     'address_id' => null
//                 ];

//                 $primaryGuardianData = [
//                     'application_id' => $data['application_id'],
//                     'address_id' => null,
//                     'first_name' => $nokFName,
//                     'last_name' => $nokLName,
//                     'relationship' => $nokRelationship,

//                     // Senior player nok inserts mobileNumSecondary
//                     'mobile_number' => $isJunior ? $mobileNum : $mobileNumSecondary, 

//                     // Junior player nok insert email 
//                     'email' => $isJunior ? $email : '', 
//                     'is_primary' => 1,

//                     // If guardian applies coach
//                     'apply_coach' => $isJunior ? $apply_coach : 0
//                 ];
    
//                 // Default form number
//                 $action = $_POST['action'] ?? '';
                
//                 // Back to form one 
//                 if ($action == 'back') {
//                     $formNum = 1;
//                 }
//                 // Default form one
//                 else if ($action == 'next') {
//                     // Validate Empty Inputs of Form One
//                     $fNameErr = ifEmpty($fName, "First Name is required");
//                     $lNameErr = ifEmpty($lName, "Last Name is required");
//                     $dobErr = ifEmpty($dob, "Please enter date of birth");
//                     $playerHeightErr = ifEmpty($playerHeight, "Height is required");
//                     $playerWeightErr = ifEmpty($playerWeight, "Weight is required");
                   
//                     // If there are no error message
//                     if (empty($fNameErr) && empty($lNameErr) && empty($dobErr) &&
//                         empty($playerHeightErr) && empty($playerWeightErr)){
//                         $age  = calcAge($dob);

//                         // Player age is less than 5 return to form 1
//                         if ($age < 5){
//                             $dobErr = "Player age must be atleast 5 years old to register.";
//                             $formNum = 1; 
//                         }
//                         else{
//                             if($age >= 5 && $age <= 12){
//                                 $isJunior = true;
//                                 $nok = 'Guardian 1';
//                             }
//                             else{
//                                $isJunior = false;
//                             }
//                             $formNum = 2; 
//                         }
//                     }
//                     // Else stay in first form to display error message
//                     else{
//                         $formNum = 1; 
//                     }
//                 }
//                 else if ($action == 'submit'){
//                     $formNum = 2;
                    
                    
//                     // If invalid email format
//                     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                         $emailErr = "Invalid email format";
//                     }
//                     // Check empty email
//                     $emailErr = ifEmpty($email, "Email is required");
                    
                                       
//                     // Validate Required Empty Inputs
//                     $playerHeightErr = ifEmpty($playerHeight, "Height is required");
//                     $playerWeightErr = ifEmpty($playerWeight, "Weight is required");
                    
//                     $line1Err = ifEmpty($line1, "Address line 1 is required");
//                     $cityErr = ifEmpty($city, "City is required");
//                     $postcodeErr= ifEmpty($postcode, "Postcode is required");
//                     $countryErr = ifEmpty($country, "Country is required");
                    
//                     $nokFNameErr = ifEmpty($nokFName, "First name is required");
//                     $nokLNameErr = ifEmpty($nokLName, "Last name is required");
//                     $nokRelationshipErr = ifEmpty($nokRelationship, "Relationship is required");

//                     $mobileNumErr = ifEmpty($mobileNum, "Mobile number is required");
//                     if (!preg_match('/^07\d{9}$/', $mobileNum)) {
//                         $mobileNumErr = "Enter a valid UK mobile number in this format eg. 07123456789";
//                     }
//                     $mobileNumSecondaryErr = ifEmpty($mobileNumSecondary, "Mobile number is required");

//                     $doctorErr = ifEmpty($doctor, "Doctor name is required");
//                     $doctorNumErr = ifEmpty($doctorNum, "Doctor number is required");
//                     $line1DoctorErr = ifEmpty($line1Doctor, "Address line 1 is required");
//                     $cityDoctorErr = ifEmpty($cityDoctor, "City is required");
//                     $postcodeDoctorErr = ifEmpty($postcodeDoctor, "Postcode is required");
//                     $countryDoctorErr = ifEmpty($countryDoctor, "Country is required");

//                     // Validate Guardian 2 Details
//                     if ($isJunior){
//                         $nokFNameSecondaryErr = ifEmpty($nokFNameSecondary, "First name is required");
//                         $nokLNameSecondaryErr = ifEmpty($nokLNameSecondary, "Last name is required");
//                         $nokRelationshipSecondaryErr = ifEmpty($nokRelationshipSecondary, "Relationship is required");
//                         $line1SecondaryErr = ifEmpty($line1Secondary, "Address line 1 is required");
//                         $citySecondaryErr = ifEmpty($citySecondary, "City is required");
//                         $postcodeSecondaryErr= ifEmpty($postcodeSecondary, "Postcode is required");
//                     }

//                     // Check for errors
//                     if (empty($playerHeightErr) &&
//                         empty($playerWeightErr) &&
//                         empty($line1Err) &&
//                         empty($cityErr) &&
//                         empty($postcodeErr) &&
//                         empty($countryErr) &&
//                         empty($nokFNameErr) &&
//                         empty($nokLNameErr) &&
//                         empty($nokRelationshipErr) &&
//                         empty($mobileNumErr) &&
//                         empty($mobileNumSecondaryErr))
//                     {
//                         // Insert all information to database                        
//                         $pdo = Database::getInstance()->getConnection();

//                         try{
//                             $pdo->beginTransaction();

//                             // Check if email already exists in members table
//                             $result = User::checkEmailExists($email,'Email already registered. Please log in to continue.');
//                             if(isset($result['emailError'])){
//                                 $emailErr = $result['emailError'];
//                                 throw new \ErrorException('This email is already registered.');
//                             }

//                             // Check if parent email already exists in members and player_contact table
//                             $existingParentEmail = $_GET['parentEmail'] ?? '';
//                             if($existingParentEmail !== ''){
//                                 $existingParent  = PlayerParent::getParent($pdo, $existingParentEmail);
//                                 if($existingParent ){
//                                     $primaryGuardianData['first_name'] = $existingParent ->getFirstName();
//                                     $primaryGuardianData['last_name'] = $existingParent ->getLastName();
//                                     $primaryGuardianData['relationship'] = $existingParent ->getRelationship();
//                                     $primaryGuardianData['mobile_number'] = $existingParent ->getMobileNum();
//                                     $primaryGuardianData['email'] = $existingParent ->getEmail();
//                                     $primaryGuardianData['is_primary'] = $existingParent ->getIsPrimary();
//                                 }
//                             }

//                             // Insert address id to db and store id
//                             $addressId  = Address::insert($pdo, $data);
//                             $data['address_id'] = (int)$addressId;

//                             // Insert doctor address to db and store id
//                             $doctorAddressId  = Address::insert($pdo, $doctorAddress);
//                             $data['doctor_address_id'] = $doctorAddressId;
//                             $doctorData['address_id'] = $doctorAddressId;

//                             // Insert doctor to db and store id
//                             $doctor = new Doctor($doctorData);
//                             $doctor->insert($pdo);
//                             $data['doctor_id'] = $doctor->getDoctorId();

//                             // Insert player application to db and store id
//                             $playerApplicationId = Application::insert($pdo, $data);
//                             $data['application_id'] = $playerApplicationId;
//                             $application_id = $data['application_id'];

//                                 // Medical Condition Data
//                             // Current Condition
//                             foreach($currentCondition as $c){
//                                 $medicalInformationData[] = [
//                                     'application_id' => $data['application_id'],
//                                     'condition_id' => $c,
//                                     'condition_status' => 'current'
//                                 ];
//                             }

//                             // Past Condition
//                             foreach($pastCondition as $c){
//                                 $medicalInformationData[] = [
//                                     'application_id' => $data['application_id'],
//                                     'condition_id' => $c,
//                                     'condition_status' => 'past'
//                                 ];
//                             }
                                            
//                             // Insert Medical Condition
//                             MedicalInformation::insertConditionApplication($pdo, $medicalInformationData);

//                             // Insert Allergies
//                             MedicalInformation::insertAllergyApplication($pdo, $allergyData, $data['application_id']);

//                             $primaryGuardianData = [
//                                 'application_id' => $data['application_id'],
//                                 'address_id' => null,
//                                 'first_name' => $nokFName,
//                                 'last_name' => $nokLName,
//                                 'relationship' => $nokRelationship,
//                                 // Senior nok inserts mobileNumSecondary
//                                 'mobile_number' => $isJunior ? $mobileNum : $mobileNumSecondary, 
//                                 'is_primary' => 1,
//                                 'apply_coach' => $apply_coach,
//                                 'email' => $isJunior ? $email : ''
//                             ];
                            
//                             // Insert Primary Guardian/NOK
//                             $primaryGuardian = new Guardian($primaryGuardianData);
//                             $primaryGuardian->insertGuardianApplication($pdo);

//                             // Check Junior error
//                             if($isJunior){
//                                 //  Guardian 2 personal info is valid
//                                 if (empty($nokFNameSecondaryErr) &&
//                                     empty($nokLNameSecondaryErr) &&
//                                     empty($nokRelationshipSecondaryErr)){
    
//                                     // Junior Player Guardian Data
//                                     $secondaryGuardianData = [
//                                         'application_id' => $data['application_id'],
//                                         'address_id' => $primaryGuardian->getGuardianId(), 
//                                         'first_name' => $nokFNameSecondary,
//                                         'last_name' => $nokLNameSecondary,
//                                         'relationship' => $nokRelationshipSecondary,
//                                         'mobile_number' => $mobileNumSecondary,
//                                         'is_primary' => 0,
//                                         'email' => ''
//                                     ];

//                                     // If same address insert same address_id as primary guardian
//                                     if ($sameAddress){
//                                         $secondaryGuardianData['address_id'] = $data['address_id'];
//                                     }
//                                      // Check address error
//                                     else if(
//                                         empty($line1SecondaryErr) &&
//                                         empty($citySecondaryErr) &&
//                                         empty($postcodeSecondaryErr) &&
//                                         empty($countrySecondaryErr)){
                                        
//                                         $secondaryGuardianAddress = [
//                                             'line1' => $line1Secondary,
//                                             'line2' => $line2Secondary,
//                                             'city' => $citySecondary,
//                                             'postcode' => $postcodeSecondary,
//                                             'country' => $countrySecondary
//                                         ];

//                                         // Insert second guardian address to db and get id
//                                         $secondaryGuardianAddressId = Address::insert($pdo, $secondaryGuardianAddress);
//                                         $secondaryGuardianData['address_id'] = $secondaryGuardianAddressId;

//                                     }
//                                     // No guardian address is added go back to form
//                                     else{
//                                         $formNum = 2;
//                                         throw new \Exception("Guardian 2 address details are invalid.");
//                                     }
                                    
//                                     // Insert Primary Guardian/NOK
//                                     $secondaryGuardian = new Guardian($secondaryGuardianData);
//                                     $secondaryGuardian->insertGuardianApplication($pdo);
//                                 }  
//                                 else{
//                                     $formNum = 2;
//                                     throw new \Exception("Guardian 2 details are invalid.");
//                                 }                              
//                             }

//                             var_dump($data);
//                             var_dump($primaryGuardianData);
//                             // Add all transaction to database
//                             $pdo->commit();

//                             // Form successfully submitted
//                             alert('success', 'Form Submitted Successfully!','register');
//                             unset($data);
//                             unset($primaryGuardian);
//                             unset($primaryGuardianData);
//                             unset($secondaryGuardian);
//                             unset($secondaryGuardianData);
//                             unset($secondaryGuardianAddress);
//                             unset($doctorData);
//                             unset($doctorAddress);
//                             exit();
//                         }
//                         catch (\Exception $e) {
//                             if ($pdo->inTransaction()) {
//                                 $pdo->rollBack();
//                             }
//                             alert('error',$e->getMessage(), 'register');
//                             die($e->getMessage());
//                         }
//                     }else{
//                         alert('error',$e->getMessage(), 'register');
//                     }
//                 } 
//             }

//             $this->render('register', [
//                 // Identifiers
//                 'formNum' => $formNum,
//                 'nok' => $nok,
//                 'isJunior' => $isJunior,
//                 'age' => $age,
//                 'sameAddress' => $sameAddress,

//                 // Player Details
//                 'fName' => $fName,
//                 'lName' => $lName,
//                 'dob' => $dob,
//                 'playerNickname' => $playerNickname,
//                 'playerHeight' => $playerHeight,
//                 'playerWeight' => $playerWeight,

//                 // Player Details Error Message
//                 'fNameErr' => $fNameErr,
//                 'lNameErr' => $lNameErr,
//                 'dobErr' => $dobErr,
//                 'playerHeightErr' => $playerHeightErr,
//                 'playerWeightErr' => $playerWeightErr,

//                 // NOK Primary
//                 'nokFName' => $nokFName,
//                 'nokLName' => $nokLName,
//                 'nokFNameErr' => $nokFNameErr,
//                 'nokLNameErr' => $nokLNameErr,
//                 'nokRelationship' => $nokRelationship,
//                 'nokRelationshipErr' => $nokRelationshipErr,

//                  // NOK Secondary
//                 'nokFNameSecondary' => $nokFNameSecondary,
//                 'nokLNameSecondary' => $nokLNameSecondary,
//                 'nokFNameSecondaryErr' => $nokFNameSecondaryErr,
//                 'nokLNameSecondaryErr' => $nokLNameSecondaryErr,
//                 'nokRelationshipSecondary' => $nokRelationshipSecondary,
//                 'nokRelationshipSecondaryErr' => $nokRelationshipSecondaryErr,

//                 // Address Primary
//                 'line1' => $line1,
//                 'line2' => $line2,
//                 'city' => $city,
//                 'postcode' => $postcode,
//                 'country' => $country,
//                 'line1Err' => $line1Err,
//                 'line2Err' => $line2Err,
//                 'cityErr' => $cityErr,
//                 'postcodeErr' => $postcodeErr,
//                 'countryErr' => $countryErr,

//                 // Address Secondary
//                 'line1Secondary' => $line1Secondary,
//                 'line2Secondary' => $line2Secondary,
//                 'citySecondary' => $citySecondary,
//                 'postcodeSecondary' => $postcodeSecondary,
//                 'countrySecondary' => $countrySecondary,
//                 'line1SecondaryErr' => $line1SecondaryErr,
//                 'line2SecondaryErr' => $line2SecondaryErr,
//                 'citySecondaryErr' => $citySecondaryErr,
//                 'postcodeSecondaryErr' => $postcodeSecondaryErr,
//                 'countrySecondaryErr' => $countrySecondaryErr,

//                 // Contact Details 
//                 'email' => $email,
//                 'emailErr' => $emailErr,
//                 'mobileNum' => $mobileNum,
//                 'mobileNumErr' => $mobileNumErr,
//                 'mobileNumSecondary' => $mobileNumSecondary,
//                 'mobileNumSecondaryErr' => $mobileNumSecondaryErr,

//                 // Medical Details
//                 'medicalInformation' => $medicalInformation,
//                 'currentCondition' => $currentCondition,
//                 'pastCondition' => $pastCondition,
//                 'allergies' => $allergies,
//                 'allergy' => $allergyData,

//                 // Doctor Details
//                 'doctor' => $doctor,
//                 'doctorErr' => $doctorErr,
//                 'doctorNum' => $doctorNum,
//                 'doctorNumErr' => $doctorNumErr,
//                 'line1Doctor' => $line1Doctor,
//                 'line1DoctorErr' => $line1DoctorErr,
//                 'line2Doctor' => $line2Doctor,
//                 'line2DoctorErr' => $line2DoctorErr,
//                 'cityDoctor' => $cityDoctor,
//                 'cityDoctorErr' => $cityDoctorErr,
//                 'postcodeDoctor' => $postcodeDoctor,
//                 'postcodeDoctorErr' => $postcodeDoctorErr,
//                 'countryDoctor' => $countryDoctor,
//                 'countryDoctorErr' => $countryDoctorErr,
//                 'apply_coach' => $apply_coach,


//                 'relationships' => [
//                     'Mother',
//                     'Father',
//                     'Guardian',
//                     'Step Parent',
//                     'Grandparent',
//                     'Aunt',
//                     'Uncle',
//                     'Sibling',
//                     'Partner',
//                     'Spouse',
//                     'Carer',
//                     'Other'
//                 ],
//             ]);
//         }

        // public function createMember()
        // {
        //     $data =[
        //         'fname' => '',
        //         'lname' => '',
        //         'email' => '',
        //         'mobileNum' => '',
        //         'dob' => '',
        //         'address_id' => null,
        //     ]; 
            
        //     $address = [
        //         'line1' => '',
        //         'line2' => '',
        //         'city' => '',
        //         'postcode' => '',
        //         'country' => ''
        //     ];

        //     $errors = [
        //         'fname' => '',
        //         'lname' => '',
        //         'email' => '',
        //         'mobileNum' => '',
        //         'dob' => '',
        //         'address_id' => ''
        //     ];
        //     $addressErrors = [
        //         'line1' => '',
        //         'line2' => '',
        //         'city' => '',
        //         'postcode' => '',
        //         'country' => ''
        //     ];

            
           
            
        //     if ($_POST) {
        //         $data['fname'] = trimPost($_POST['fname'] ?? '');
        //         $data['lname'] = trimPost($_POST['lname'] ?? '');
        //         $data['email'] = trimPost($_POST['email'] ?? '');
        //         $data['mobileNum'] = trimPost($_POST['mobileNum'] ?? '');
        //         $data['dob'] = trimPost($_POST['dob'] ?? '');

        //         $address['address_id'] = trimPost($_POST['address_id'] ?? '');
        //         $address['line1'] = trimPost($_POST['line1'] ?? '');
        //         $address['line2'] = trimPost($_POST['line2'] ?? '');
        //         $address['city'] = trimPost($_POST['city'] ?? ''); 
        //         $address['postcode'] = trimPost($_POST['postcode'] ?? '');
        //         $address['country'] = trimPost($_POST['country'] ?? '');

        //         $data = [
        //             'fname' => 'Violet',
        //             'lname' => 'McLean',
        //             'email' => 'violet.mclean@example.com',
        //             'mobileNum' => '07456123987',
        //             'dob' => '1990-02-22',
        //             'address_id' => null,
        //         ];

        //         $address = [
        //             'line1' => '34 Queen Street',
        //             'line2' => '',
        //             'city' => 'Manchester',
        //             'postcode' => 'M1 4AB',
        //             'country' => 'United Kingdom'
        //         ];


        //         // Validate Empty Email and Password Input
        //         if (empty($data['fname'])) {
        //             $errors['fname'] = "First name is required";
        //         }
        //         if (empty($data['lname'])) {
        //             $errors['lname'] = "Last name is required";
        //         }
        //         if (empty($data['mobileNum'])) {
        //             $errors['mobileNum'] = "Mobile number is required";
        //         }
        //         if (empty($data['dob'])) {
        //             $errors['dob'] = "Date of birth is required";
        //         }

        //         // Check empty email
        //         if (empty($data['email'])) {
        //             $errors['email'] = "Email is required";
        //         } else {
        //             // If invalid email format
        //             if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        //                 $errors['email'] = "Invalid email format";
        //             }
        //             else{
        //                 // Check if email already exists
        //                 $result = User::checkEmailExists($data['email'],'Email already registered. Please log in to continue.');

        //                 // If email already exists, display error message
        //                 $errors['email'] = $result['emailError'] ?? '';
        //             }
        //         }

        //         // Check empty address input
        //         if (empty($address['line1'])) {
        //             $addressErrors['line1'] = "Address line 1 is required";
        //         }
        //         if (empty($address['city'])) {
        //             $addressErrors['city'] = "City is required";
        //         }
        //         if (empty($address['postcode'])) {
        //             $addressErrors['postcode'] = "Postcode is required";
        //         }
        //         if (empty($address['country'])) {
        //             $addressErrors['country'] = "Country is required";
        //         }

        //         $pdo = Database::getInstance()->getConnection();
        //         try{
        //             // Check for address errors
        //             if (!array_filter($addressErrors)){
        //                 $pdo->beginTransaction();

        //                 // Insert address id to db and store id
        //                 $addressId  = Address::insert($pdo, $address);
        //                 $data['address_id'] = (int)$addressId;
        //             }
        //             else{
        //                 throw new \Exception("Address details are invalid.");
        //             }
        //             // Check for member details errors
        //             if (!array_filter($errors))
        //             {
        //                 // Create member object to be inserted to db
        //                 $member = new Member();
        //                 $member->first_name = $data['fname'];
        //                 $member->last_name = $data['lname'];
        //                 $member->dob = $data['dob'];
        //                 $member->mobile_num = $data['mobileNum'];
        //                 $member->address_id = $data['address_id'];
        //                 $member->membership_status = $data['membership_status'] ?? 'active';

        //                 // Insert member to db and store id for creating login
        //                 $result = $member->insert($pdo);

        //                 // Commit transaction
        //                 $pdo->commit();
                        
        //                 // Clear Errors
        //                 unset($errors);
        //                 unset($addressErrors);

        //                 // Success Message and redirect to create login page with member id
        //                 alert('success',
        //                     'Member created successfully.', 
        //                     'create-login?member_id=' . $member->member_id);
        //                 exit();
        //             }
        //             else{
        //                 throw new \Exception("Member details are invalid.");
        //             }
        //         }
        //         catch (\Exception $e) {
        //             if ($pdo->inTransaction()) {
        //                 $pdo->rollBack();
        //             }
        //             alert('error',$e->getMessage(), 'create-member');
        //             exit;
        //         }
        //     }
        //     $this->render('create-member', [
        //             'member' => $member ?? null,
        //             'data' => $data,
        //             'errors' => $errors,
        //             'address' => $address,
        //             'addressErrors' => $addressErrors
        //         ]);
        // }

        public function displayMembersNoLogin($view)
        {
            $member = new Member();
            $members = $member->selectAllNoLogin();

            $this->render($view, [
                'members' => $members
            ]);
        }
        
        // // Create a login for a member
        // public function createLogin()
        // {
        //     // Get member id from query
        //     $member_id = $_GET['member_id'] ?? null;

        //     // If no member id is provided, display members without login
        //     if($member_id === null){
        //         alert('error', 'No member selected. Please <a href="/members-no-login">select a member</a> to create login.', 'members-no-login');
        //         exit();
        //     }

        //     // Get all roles for dropdown
        //     $roles = Role::getRole();

        //     // Create login object to be inserted to db
        //     $member_login = new User();

        //     // Set member id for creating login
        //     $member_login->member_id = $member_id; 
        //     $member_login->email =  Member::selectEmail($member_id) ?? '';
        //     $email = $member_login->email ?? '';
            
        //     $data = [
        //         'member_id' => $member_id ?? null,
        //         'email' => $email ?? null,
        //         'rawPassword' => '',
        //         'rawConfirmPassword' => '',
        //         'selectedRole' => ''
        //     ];


        //     $errors = [
        //         'email' => '',
        //         'rawPassword' => '',
        //         'rawConfirmPassword' => '',
        //         'selectedRole' => ''
        //     ];

        //     if ($_POST) {
        //         $data['rawPassword'] = trim($_POST['rawPassword'] ?? '');
        //         $data['rawConfirmPassword'] = trim($_POST['rawConfirmPassword'] ?? '');
        //         $data['selectedRole'] = (int)($_POST['selectedRole'] ?? '');

        //         // Validate Empty Email and Password Input
        //         if (empty($data['rawPassword'])) {
        //             $errors['rawPassword'] = "Password is required";
        //         }
        //         if (empty($data['rawConfirmPassword'])) {
        //             $errors['rawConfirmPassword'] = "Please confirm password";
        //         }
        //         if ($data['selectedRole'] <= 0) {
        //             $errors['selectedRole'] = "Please select a role";
        //         }

                
        //         // Check if email already exists
        //         $result = User::checkEmailExists($data['email'],'Email is not yet registered.');

        //         if(!$result['emailError']){
        //             $errors['email'] = 'Email is not yet registered. Please register member first before creating login.';
        //         }
                
        //         var_dump($data['rawPassword']);
        //         var_dump($data['rawConfirmPassword']);
                
        //         // Check password length
        //         if(strlen($data['rawPassword']) < 8){
        //             $errors['rawPassword'] = 'Password must be atleast 8 characters<br>';
        //         }
        //         // More password strength validation
        //         else
        //         {
        //             if(!preg_match("#[0-9]+#", $data['rawPassword'])) {
        //                 $errors['rawPassword'] .= "Your Password Must Contain At Least 1 Number!<br>";
        //             }
        //             if(!preg_match("#[A-Z]+#", $data['rawPassword'])) {
        //                 $errors['rawPassword'] .= "Your Password Must Contain At Least 1 Capital Letter!<br>";
        //             }
        //             if(!preg_match("#[a-z]+#", $data['rawPassword'])) {
        //                 $errors['rawPassword'] .= "Your Password Must Contain At Least 1 Lowercase Letter!<br>";
        //             }

        //             // Valid strength, confirm password
        //             // Password not match
        //             if($data['rawPassword'] !== $data['rawConfirmPassword']){
        //                 $errors['rawConfirmPassword'] = 'Password does not match';
        //             }
        //             // Password confirmed
        //             else{
        //                 //Hash password
        //                 $salt ="4g£yc7!L(";
        //                 $data['password'] = md5($data['rawPassword'].$salt);

        //                 // No errors, add to database
        //                 if(!array_filter($errors)){
        //                         $pdo = Database::getInstance()->getConnection();
        //                     try{
        //                         $pdo->beginTransaction();

        //                         $user = new User();

        //                         $user->email = $data['email'];
        //                         $user->password = $data['rawPassword'];
        //                         $user->member_id = $data['member_id'];
        //                         $user->role = $data['selectedRole'];

        //                         $exists = User::findMemberLogin($pdo, $user->member_id);
                                
        //                         if($exists){
        //                             throw new \Exception("This user has an existing login details.");
        //                         }

        //                         //Insert login details to db
        //                         User::insertLogin($pdo, $data);

        //                         //Insert role for member
        //                         Role::insertMemberRoles($pdo, $user->member_id, $user->role);

        //                         //Commit transaction
        //                         $pdo->commit();
        //                         alert('success', 'Login Account has been Successfully Created', 'create-login?member_id=' . $data['member_id']);
        //                     }
        //                     catch (\Exception $e) {
        //                         if ($pdo->inTransaction()) {
        //                             $pdo->rollBack();
        //                         }
        //                         alert('error', $e->getMessage(), 'create-login?member_id=' . $data['member_id']);
        //                     }
        //                 }
        //             }
        //         }
        //     }

            
        //     $this->render('create-login', [
        //         'data' => $data,
        //         'errors' => $errors,
        //         'roles' => $roles
        //     ]);
        // }
    }
?>