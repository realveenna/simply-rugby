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
    use Test\Models\Player;
    use Test\Models\Squad;
    use Test\Database;


    class ApplicationController extends Controller
    {
        public function __construct()
        {
            
        }


        // Display all player applications
        // /player-applications/index 
        public function playerApplications()
        {
            $pdo = Database::getInstance()->getConnection();

            // Admin Access Only
            if(isset($_GET['id']) && isset($_GET['action'])){
                $id = $_GET['id'];
                $action = $_GET['action'];

                if($action === 'approve'){
                    Application::updateStatus($pdo, $id, 'approved');
                    header("Location: /player-applications/application-details?id=" . $id. "&action=approve");
                    exit();
                }
                else if($action === 'reject'){
                    Application::updateStatus($pdo, $id, 'rejected');
                    header("Location: /player-applications/application-details?id=" . $id. "&action=reject");
                    exit();
                }
                // Default Action is View
                else{
                    // Redirect to application details page
                    header("Location: /player-applications/application-details?id=" . $id);
                    exit();
                }
            }

            $applications = Application::getPlayerApplications();

            $this->render('player-applications/index', [
                'applications' => $applications
            ]);
        }

        // Display full application details of a player
        // /player-applications/application-details with id
        public function applicationDetails(){
            $pdo = Database::getInstance()->getConnection();

            $data = [];
            $pGuardian = [];
            $sGuardian = [] ?? null;
            $allergies = [];
            $currentCondition = [];
            $pastCondition = [];
            $doctor = [];

            if(isset($_GET['id'])){
                $data['application_id'] = $_GET['id'];
              
            }
            else{
                alert('error', 'Application ID is required.', '/player-applications');
            }

            // Check if application exists
            $applicationExists = Application::getApplicationById($pdo, $data['application_id']); 
            if (!$applicationExists) {
                alert('error', 'Application not found.', '/player-applications');
            }
            
            // Get application details
            $data = Application::getPlayerApplicationDetails($pdo, $data['application_id']);
           
            // Get primary and secondary guardians
            $pGuardian = Guardian::getPrimaryApplicationGuardians
                ($pdo, $data['primary_guardian_id']);
            $hasParentLogin = false;

            $sGuardian = [];
            if(!empty($data['secondary_guardian_id'])){
                $sGuardian = Guardian::getSecondaryApplicationGuardians
                ($pdo, $data['secondary_guardian_id']);
                $sGuardian['address'] = Address::getAddressDetails($pdo, $sGuardian['address_id']);
            }
            
            // Get address details for player application, primary and secondary guardian
            $data['address'] = Address::getAddressDetails($pdo, $data['address_id']);
            $pGuardian['address'] = Address::getAddressDetails($pdo, $pGuardian['address_id']);
            
            // Get application allergies
            $allergies = MedicalInformation::getPlayerAllergies
                ($pdo, 'application_allergy', $data['application_id'], 
                'application_allergy_id', 'player-applications/application-details');
                
            // Current conditions
            $currentCondition = MedicalInformation::getPlayerConditions
                ($pdo, 'application_condition', $data['application_id'], 
                'application_id', 'current', 'player-applications/application-details');

            // Past conditions
            $pastCondition = MedicalInformation::getPlayerConditions
                ($pdo, 'application_condition', $data['application_id'], 
                'application_id', 'past', 'player-applications/application-details');    

            // Doctor details
            $doctor = [];
            if (!empty($data['doctor_id'])) {
                $doctor = MedicalInformation::getPlayerDoctor($pdo, 'player_application', $data['application_id'], 'application_id');
                $doctor['address'] = MedicalInformation::getDoctorAddressId($pdo, $doctor['doctor_id']);
            }

            // Identify if player is junior or senior and display correct nok title
            $isJunior = $data['recommended_squad'] === 'senior' ? false : true;
            $nok = $isJunior === true ? 'Primary Guardian' : 'Next of Kin';

            $playerData = [
                'application' => $data,
                'primary_guardian' => $pGuardian,   
                'secondary_guardian' => $sGuardian ?? null,
                'allergies' => $allergies,
                'current_conditions' => $currentCondition,
                'past_conditions' => $pastCondition,
                'doctor' => $doctor ?? null,
                'isJunior' => $isJunior,
                'nok' => $nok
            ];

            // If action is set from GET request, set it to POST
            if (isset($_GET['action'])) {
                $_POST['action'] = $_GET['action'];
            }
            
            // If form is submitted from application details page
            if ($_POST) {
                $action = $_POST['action'] ?? $_GET['action'] ?? '';

                $pdo = Database::getInstance()->getConnection();

                 // Create member object for player
                $member = new Member();
                $member->first_name = $data['applicant_first_name'];
                $member->last_name = $data['applicant_last_name'];
                $member->dob = $data['applicant_dob'];
                $member->mobile_num = $data['mobile_num'];
                $member->address_id = $data['address_id'];
                $member->membership_status = $data['membership_status'] ?? 'active';
                $member->email = $data['email'] ?: null;

                // Check if member already exist before inserting
                $memberExists = $member->validateInsert($pdo);

                try{
                    $pdo->beginTransaction();
                    
                    // If approved applications
                    if ($action === 'approve') {
                        if (!$memberExists) {
                            // Insert primary guardian to member table
                            $memberId = $member->insert($pdo);
                            if (!$memberId) {
                                throw new \Exception('Failed to add member details.');
                            }
                            // Store last indsert member_id
                            $member->member_id = $memberId;
                        }
                        else{
                            // Set member_id
                            alert('error', 'This member already exists.', '/player-applications');
                        }

                        // Insert to player_profile table
                        $player = Player::insert($pdo, $playerData, $memberId);
                        if (!$player) {
                            throw new \Exception('Failed to add details to player profile.');
                        }

                        // Insert player to squad
                        $squadId = Squad::getSquadIdByType($pdo, $data['recommended_squad']);
                        if (!$squadId) {
                            throw new \Exception('Recommended squad does not exist.');
                        }
                        
                        // Insert player to squad member table
                        $insertSquadMember = Squad::insertSquadMember($pdo, $squadId, $memberId);
                        if (!$insertSquadMember) {
                            throw new \Exception('Failed to add player to squad.');
                        }

                        // Insert player to squad_player_history table
                        $insertSquadHistory = Squad::insertSquadHistory($pdo, $squadId, $memberId);
                        if (!$insertSquadHistory) {
                            throw new \Exception('Failed to add player to squad history.');
                        }

                        // Create primary guardian object
                        $pContactMember = new Member();
                        $pContactMember->first_name = $pGuardian['first_name'];
                        $pContactMember->last_name = $pGuardian['last_name'];
                        $pContactMember->dob = '';
                        $pContactMember->mobile_num = $pGuardian['mobile_number'];
                        $pContactMember->address_id = $pGuardian['address_id'] ?? null;
                        $pContactMember->membership_status = $pGuardian['membership_status'] ?? 'active';
                        $pContactMember->email = $pGuardian['email'] ?: null;

                        // Check if primargy guardian member already exist before inserting
                        $memberExists = $pContactMember->validateInsert($pdo);
                        if (!$memberExists) {
                            // Insert primary guardian to member table
                            $pContactMember->member_id = $pContactMember->insert($pdo);
                            if (!$pContactMember->member_id) {
                                throw new \Exception('Failed to add primary guardian/nok details.');
                            }
                        }
                        else{
                            // Set member_id
                            $pContactMember->member_id = $memberExists;
                            $hasParentLogin = true;
                        }
                     
                        // Set primary guardian member id and access level
                        $pGuardian['contact_member_id'] = $pContactMember->member_id;

                        // If player is a junior 
                        if($isJunior){
                            // Set guardian access level to full access
                            $pGuardian['access_level'] = 'Full Access';

                            // Get role id for parent role
                            $roleId = Role::getRoleIdByName($pdo, 'Parent');

                            // Insert role of guardian to member role table
                            Role::insertMemberRoles($pdo, $pContactMember->member_id, $roleId, null);

                            // If apply coach is selected, insert coach role to member role table
                            if($pGuardian['apply_coach']){
                                $coachRoleId = Role::getRoleIdByName($pdo, 'Coach');
                                Role::insertMemberRoles($pdo, $pContactMember->member_id, $coachRoleId, $squadId);
                            }
                        }
                        // Else set to none
                        else{
                            $pGuardian['access_level'] = 'None';

                            // Get role id for senior player role
                            $roleId = Role::getRoleIdByName($pdo, 'Senior Player');

                            // Insert role of senior player to member role table
                            Role::insertMemberRoles($pdo, $member->member_id, $roleId, $squadId);
                        }

                        // Insert primary guardian to player contact table
                        $primaryContact = new Guardian($pGuardian, $memberId);
                        $inserted = $primaryContact->insertPlayerContact($pdo);
 
                        if (!$inserted) {
                            throw new \Exception('Failed to add primary guardian/nok to contact table.');
                        }


                        // If secondary guardian details exist, insert to member table and player contact table
                        if(!empty($sGuardian[0])){
                            // Create second guardian object
                            $sContactMember = new Member();
                            $sContactMember->first_name = $sGuardian['first_name'];
                            $sContactMember->last_name = $sGuardian['last_name'];
                            $sContactMember->dob = '';
                            $sContactMember->mobile_num = $sGuardian['mobile_number'];
                            $sContactMember->address_id = $sGuardian['address_id'] ?? null;
                            $sContactMember->membership_status = $sGuardian['membership_status'] ?? 'active';
                            $sContactMember->email = $sGuardian['email'] ?: null;

                            // Check if member already exist before inserting
                            $memberExists = $sContactMember->validateInsert($pdo);
                            if (!$memberExists) {
                                // Insert second guardian to member table
                                $sContactMember->member_id = $sContactMember->insert($pdo);

                                if (!$sContactMember->member_id) {
                                    throw new \Exception('Failed to add second guardian/nok details.');
                                }
                            }
                            else{
                                // Set member_id
                                $sContactMember->member_id = $memberExists;
                            }

                            if (!$sContactMember->member_id) {
                                throw new \Exception('Failed to add second guardian/nok details.');
                            }
                            


                            // Set secondary guardian member id and access level default as none
                            $sGuardian['contact_member_id'] = $sContactMember->member_id;
                            $sGuardian['access_level'] = 'None';

                            // Insert secondary guardian to player contact table
                            $secondaryContact = new Guardian($sGuardian, $memberId);
                            $inserted = $secondaryContact->insertPlayerContact($pdo);

                            if (!$inserted) {
                                throw new \Exception('Failed to add secondary guardian/nok to contact table.');
                            }
                        }
                        
                        // Insert player allergy to table
                        MedicalInformation::copyToPlayerAllergy($pdo, $data['application_id'], $memberId);

                        // Insert player condition to table
                        MedicalInformation::copyToPlayerCondition($pdo, $data['application_id'], $memberId);

                        // Update application status to approved from player_application table
                        Application::updateStatus($pdo, $data['application_id'], 'approved');

                        // Send Reset link email
                        if($data['recommended_squad'] === 'Senior'){
                            $recipient = $data['email'];
                        }
                        else{
                            $recipient = $pGuardian['email'];
                            $memberId = $pContactMember->member_id;
                        }
                        
                        $login = User::findMemberLogin($pdo, $memberId);
                        if($login){
                            $member_id = $login['member_id'];
                        }

                        $password = randomPassword();
                        $hashedPassword = hashPassword($password);

                        if(empty($recipient)){
                            throw new \Exception('Recipient email address is missing.');
                        }

                        // Email for successful registration only
                        if($hasParentLogin === true){
                            MailController::newMember($data['applicant_first_name'], $recipient);
                        }
                        else{
                            // Insert to login table
                            $member_id = User::insertNewMemberLogin($pdo, $data['member_id'] = $memberId, $hashedPassword);
                            MailController::newResetPassword($data['applicant_first_name'], $recipient, $member_id, $password);
                        }
                    }

                    else if ($action === 'reject') {
                        if ($memberExists) {
                            $deleted = Player::delete($pdo, $memberExists);
                            if (!$deleted) {
                                throw new \Exception('Failed to delete player details.');
                            }
                        }
                        else{
                            throw new \Exception('This player does not exist.');
                        }
                        Application::updateStatus($pdo, $data['application_id'], 'rejected');
                    }
                    else if ($action === 'back'){
                        header("Location: /player-applications");
                        exit();
                    }
                    else{
                        alert('error', 'Invalid action.', '/player-applications/application-details?id=' . $data['application_id']);
                        exit();
                    }

                    $pdo->commit();

                    // Success message
                    alert('success', 'Application ' .ucfirst($action) . ' Successfully!', '/player-applications');
                    exit();
                }
                catch (\Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    alert('error', $e->getMessage(), '/player-applications/application-details?id=' . $data['application_id']);
                    die($e->getMessage());
                }
            }

            $this->render('player-applications/application-details', [
                'data' => $data,
                'primaryGuardian' => $pGuardian,
                'secondaryGuardian' => $sGuardian ?? null,
                'allergies' => $allergies['applicationAllergies'] ?? null,
                'currentConditions' => $currentCondition ?? null,
                'pastConditions' => $pastCondition ?? null,
                'doctor' => $doctor ?? null,
                'doctorAddress' => $doctorAddress ?? null,
                'isJunior' => $isJunior ?? true,
                'nok' => $nok ?? 'Guardian',
                'hasParentLogin' => $hasParentLogin ?? false
            ]);
        }

         // Register a player account
         // /register/index
        public function index()
        {
            $relationships = [
                'Mother',
                'Father',
                'Guardian',
                'Step Parent',
                'Grandparent',
                'Aunt',
                'Uncle',
                'Sibling',
                'Partner',
                'Spouse',
                'Carer',
                'Other'
            ];

            // Get condition array
            $medicalInformations = MedicalInformation::viewAllCondition();

             // Get allergy array
            $allergies = MedicalInformation::viewAllAllergy();

            $formNum = 1;
            $age = '';
            $isJunior = '';
            $nok = 'Next of Kin';
            $sameAddress = false;
            $apply_coach = '';
           
            // Personal Details
            $fName  = '';
            $lName  = '';
            $dob = '';
            $playerNickname = '';
            $playerHeight = '';
            $playerWeight = '';
                        
            $fNameErr  = '';
            $lNameErr  = '';
            $dobErr = '';
            $playerHeightErr = '';
            $playerWeightErr = '';

            $nokFName = '';
            $nokLName = '';
            $nokFNameErr = '';
            $nokLNameErr = '';

            $nokFNameSecondary = '';
            $nokLNameSecondary = '';
            $nokFNameSecondaryErr = '';
            $nokLNameSecondaryErr = '';

            $nokRelationship = '';
            $nokRelationshipErr = '';
            $nokRelationshipSecondary = '';
            $nokRelationshipSecondaryErr = '';

            $email = '';
            $emailErr = '';

            $mobileNum = '';
            $mobileNumErr = '';
            $mobileNumSecondary = '';
            $mobileNumSecondaryErr = '';
            
            $line1 = $line2 = $city = $postcode = $country = "";
            $line1Err = $line2Err = $cityErr =  $postcodeErr = $countryErr = "";

            $line1Secondary = $line2Secondary = $citySecondary = $postcodeSecondary = $countrySecondary = "";
            $line1SecondaryErr = $line2SecondaryErr = $citySecondaryErr =  $postcodeSecondaryErr  = $countrySecondaryErr = "";

            // Medical Information Array
            $medicalInformationData = [];
            $allergyData = [];

            $currentCondition = [];
            $pastCondition = [];

            // Doctor Information
            $doctor  = '';
            $doctorErr  = '';
            $doctorNum  = '';
            $doctorNumErr = '';
            $line1Doctor  = '';
            $line1DoctorErr  = '';
            $line2Doctor  = '';
            $line2DoctorErr  = '';
            $cityDoctor  = '';
            $cityDoctorErr  = '';
            $postcodeDoctor  = '';
            $postcodeDoctorErr  = '';
            $countryDoctor = '';
            $countryDoctorErr  = '';

            $isJunior = false;

            if ($_POST) {
                $fName = trimPost('fName') ?? '';
                $lName = trimPost('lName') ?? '';
                $dob = trimPost('dob') ?? '';
                $playerNickname = trimPost('playerNickname') ?? '';
                $playerHeight = trimPost('playerHeight') ?? '';
                $playerWeight = trimPost('playerWeight') ?? '';

                $nokFName = trimPost('nokFName') ?? '';
                $nokLName = trimPost('nokLName') ?? '';
                $nokRelationship = trimPost('nokRelationship') ?? '';         
                $apply_coach = isset($_POST['apply_coach']) ?? 0 ;         
                
                $nokFNameSecondary = trimPost('nokFNameSecondary') ?? '';
                $nokLNameSecondary = trimPost('nokLNameSecondary') ?? '';
                $nokRelationshipSecondary = trimPost('nokRelationshipSecondary') ?? '';
                $email = trimPost('email') ?? '';
                $mobileNum = trimPost('mobileNum') ?? '';
                $mobileNumSecondary  = trimPost('mobileNumSecondary') ?? '';

                $line1 = trimPost('line1') ?? '';
                $line2 = trimPost('line2') ?? '';
                $city = trimPost('city') ?? '';
                $postcode = strtoupper(trimPost('postcode')) ?? '';
                $country = trimPost('country') ?? '';

                $line1Secondary = trimPost('line1Secondary') ?? '';
                $line2Secondary = trimPost('line2Secondary') ?? '';
                $citySecondary  = trimPost('citySecondary') ?? '';
                $postcodeSecondary = strtoupper(trimPost('postcodeSecondary') )?? '';
                $countrySecondary = trimPost('countrySecondary') ?? '';

                $sameAddress = isset($_POST['sameAddress']);
                $isJunior = isset($_POST['isJunior']) && $_POST['isJunior'] === '1';
                // Medical Data
                $currentCondition = $_POST['currentCondition'] ?? [];
                $pastCondition = $_POST['pastCondition'] ?? [];

                // Allergies
                $allergyData = $_POST['allergies'] ?? [];

                // Doctor Data
                $doctor = trimPost('doctor');
                $doctorNum = trimPost('doctorNum');
                $line1Doctor = trimPost('line1Doctor');
                $line2Doctor = trimPost('line2Doctor');
                $cityDoctor = trimPost('cityDoctor');
                $postcodeDoctor = strtoupper(trimPost('postcodeDoctor')); 
                $countryDoctor = trimPost('countryDoctor'); 

                
// Fake populated values for testing
// Personal Details
// Player Information
// Player Information
$fName = 'Allister';
$lName = 'Byrne';
$dob = '2020-04-17';
$playerNickname = 'Danny';
$playerHeight = '140';
$playerWeight = '40';


// NOK Primary
$nokFName = 'Emma';
$nokLName = 'Byrne';
$nokRelationship = 'Mother';

// NOK Secondary
$nokFNameSecondary = 'Ryan';
$nokLNameSecondary = 'Byrne';
$nokRelationshipSecondary = 'Uncle';

// Contact Details
$email = 'Emma.byrne95@gmail.com';
$mobileNum = '07591826473';
$mobileNumSecondary = '07462819357';


// Primary Address
$line1 = '42 Cedar Avenue';
$line2 = '';
$city = 'Glasgow';
$postcode = 'GA12 2TR';
$country = 'United Kingdom';


// Secondary Address
$line1Secondary = '42 Cedar Avenue';
$line2Secondary = '';
$citySecondary = 'Glasgow';
$postcodeSecondary = 'GA12 2TR';
$countrySecondary = 'United Kingdom';


// Medical Information Array
$medicalInformationData = [];

$allergyData = [];

$currentCondition = [];
$pastCondition = []; 


// Doctor Information
$doctor = 'Dr Fiona MacKenzie';
$doctorNum = '01412847635';

// Doctor Address
$line1Doctor = '12 Woodside Health Centre';
$line2Doctor = 'Maryhill Road';
$cityDoctor = 'Glasgow';
$postcodeDoctor = 'G20 7LR';
$countryDoctor = 'United Kingdom';

$isJunior = true;
$apply_coach = 1;

// // Senior Player
// $apply_coach = 0;
// $sameAddress = true;

                // Default data to be passed
                $data = [
                    // Player Data
                    'fName' => $fName,
                    'lName' => $lName,
                    'dob' => $dob,
                    'playerNickname' => $playerNickname,
                    'playerHeight' => $playerHeight,
                    'playerWeight' => $playerWeight,
                    'email' => $isJunior ? null : $email,

                    // Primary/Foreign Key
                    'application_id' => null, 
                    'address_id' => null, 
                    'doctor_id' => null,
                    'primary_guardian_id' => null,
                    'secondary_guardian_id' => null,
                    
                    // Mobile numbers
                    'mobileNum' => $mobileNum, 
                    'mobileNumSecondary' => $mobileNumSecondary,

                    // Address
                    'line1' => $line1,
                    'line2' => $line2,
                    'city' => $city,
                    'postcode' => $postcode,
                    'country' => $country
                ];

                // Doctor Address to be passed
                $doctorAddress = [
                    'doctor_address_id' => null,
                    'line1' => $line1Doctor,
                    'line2' => $line2Doctor,
                    'city' => $cityDoctor,
                    'postcode' => $postcodeDoctor,
                    'country' => $countryDoctor
                ];
       
                $doctorData = [
                    'doctor_name' => $doctor,
                    'doctor_tel' => $doctorNum,
                    'address_id' => null
                ];

                $primaryGuardianData = [
                    'address_id' => null,
                    'first_name' => $nokFName,
                    'last_name' => $nokLName,
                    'relationship' => $nokRelationship,

                    // Senior player nok inserts mobileNumSecondary
                    'mobile_number' => $isJunior ? $mobileNum : $mobileNumSecondary, 

                    // Junior player nok insert email 
                    'email' => $isJunior ? $email : null, 
                    'is_primary' => 1,

                    // If guardian applies coach
                    'apply_coach' => $isJunior ? $apply_coach : 0
                ];
    
                // Default form number
                $action = $_POST['action'] ?? '';
                
                // Back to form one 
                if ($action == 'back') {
                    $formNum = 1;
                }
                // Default form one
                else if ($action == 'next') {
                    // Validate Empty Inputs of Form One
                    $fNameErr = ifEmpty($fName, "First Name is required");
                    $lNameErr = ifEmpty($lName, "Last Name is required");
                    $dobErr = ifEmpty($dob, "Please enter date of birth");
                    $playerHeightErr = ifEmpty($playerHeight, "Height is required");
                    $playerWeightErr = ifEmpty($playerWeight, "Weight is required");
                   
                    // If there are no error message
                    if (empty($fNameErr) && empty($lNameErr) && empty($dobErr) &&
                        empty($playerHeightErr) && empty($playerWeightErr)){
                        $age  = calcAge($dob);

                        // Player age is less than 5 return to form 1
                        if ($age < 5){
                            $dobErr = "Player age must be atleast 5 years old to register.";
                            $formNum = 1; 
                        }
                        else{
                            if($age >= 5 && $age <= 12){
                                $isJunior = true;
                                $nok = 'Guardian 1';
                            }
                            else{
                               $isJunior = false;
                            }
                            $formNum = 2; 
                        }
                        
                    }
                    // Else stay in first form to display error message
                    else{
                        $formNum = 1; 
                    }
                }
                else if ($action == 'submit'){
                    $formNum = 2;
                    // If invalid email format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                    // Check empty email
                    $emailErr = ifEmpty($email, "Email is required");
                    
                    // Validate Required Empty Inputs
                    $playerHeightErr = ifEmpty($playerHeight, "Height is required");
                    $playerWeightErr = ifEmpty($playerWeight, "Weight is required");
                    
                    $line1Err = ifEmpty($line1, "Address line 1 is required");
                    $cityErr = ifEmpty($city, "City is required");
                    $postcodeErr= ifEmpty($postcode, "Postcode is required");
                    $countryErr = ifEmpty($country, "Country is required");
                    
                    $nokFNameErr = ifEmpty($nokFName, "First name is required");
                    $nokLNameErr = ifEmpty($nokLName, "Last name is required");
                    $nokRelationshipErr = ifEmpty($nokRelationship, "Relationship is required");

                    $mobileNumErr = ifEmpty($mobileNum, "Mobile number is required");
                    if (!preg_match('/^07\d{9}$/', $mobileNum)) {
                        $mobileNumErr = "Enter a valid UK mobile number in this format eg. 07123456789";
                    }
                    $mobileNumSecondaryErr = ifEmpty($mobileNumSecondary, "Mobile number is required");

                    $doctorErr = ifEmpty($doctor, "Doctor name is required");
                    $doctorNumErr = ifEmpty($doctorNum, "Doctor number is required");
                    $line1DoctorErr = ifEmpty($line1Doctor, "Address line 1 is required");
                    $cityDoctorErr = ifEmpty($cityDoctor, "City is required");
                    $postcodeDoctorErr = ifEmpty($postcodeDoctor, "Postcode is required");
                    $countryDoctorErr = ifEmpty($countryDoctor, "Country is required");

                    // Validate Guardian 2 Details
                    if ($isJunior){
                        $nokFNameSecondaryErr = ifEmpty($nokFNameSecondary, "First name is required");
                        $nokLNameSecondaryErr = ifEmpty($nokLNameSecondary, "Last name is required");
                        $nokRelationshipSecondaryErr = ifEmpty($nokRelationshipSecondary, "Relationship is required");
                        $line1SecondaryErr = ifEmpty($line1Secondary, "Address line 1 is required");
                        $citySecondaryErr = ifEmpty($citySecondary, "City is required");
                        $postcodeSecondaryErr= ifEmpty($postcodeSecondary, "Postcode is required");
                    }

                    // Check for errors
                    if (empty($playerHeightErr) &&
                        empty($playerWeightErr) &&
                        empty($line1Err) &&
                        empty($cityErr) &&
                        empty($postcodeErr) &&
                        empty($countryErr) &&
                        empty($nokFNameErr) &&
                        empty($nokLNameErr) &&
                        empty($nokRelationshipErr) &&
                        empty($mobileNumErr) &&
                        empty($mobileNumSecondaryErr))
                    {
                        // Insert all information to database                        
                    $pdo = Database::getInstance()->getConnection();
                        try{
                            $pdo->beginTransaction();

                            // Check if email already exists in members table
                            $result = User::checkEmailExists($email,'Email already registered. Please log in to continue.');
                            if(isset($result['emailError'])){
                                $emailErr = $result['emailError'];
                                throw new \ErrorException('This email is already registered.');
                            }

                            // Check if parent email already exists in members and player_contact table
                            $existingParentEmail = $_GET['parentEmail'] ?? '';
                            if($existingParentEmail !== ''){
                                $existingParent  = PlayerParent::getParent($pdo, $existingParentEmail);
                                if($existingParent ){
                                    $primaryGuardianData['first_name'] = $existingParent ->getFirstName();
                                    $primaryGuardianData['last_name'] = $existingParent ->getLastName();
                                    $primaryGuardianData['relationship'] = $existingParent ->getRelationship();
                                    $primaryGuardianData['mobile_number'] = $existingParent ->getMobileNum();
                                    $primaryGuardianData['email'] = $existingParent ->getEmail();
                                    $primaryGuardianData['is_primary'] = $existingParent ->getIsPrimary();
                                }
                            }
                            // Convert date to Y-m-d format
                            $data['dob'] = \DateTime::createFromFormat('Y-m-d', $dob);
                            $data['dob'] = date_format($data['dob'],"Y/m/d H:i:s");

                            $age  = calcAge($data['dob']);
                            
                             //  Determine recommended squad 
                            if($age >= 5 && $age <= 12){
                                $data['recommended_squad'] = 'Mini';
                            }
                            elseif ($age > 12 && $age < 18){
                                $data['recommended_squad'] = 'Midi';
                            }
                            else{
                                $data['recommended_squad'] = 'Senior';
                            }

                            // Insert address id to db and store id
                            $addressId  = Address::insert($pdo, $data);
                            $data['address_id'] = (int)$addressId;

                            // Insert doctor address to db and store id
                            $doctorAddressId  = Address::insert($pdo, $doctorAddress);
                            $data['doctor_address_id'] = $doctorAddressId;
                            $doctorData['address_id'] = $doctorAddressId;

                            // Insert doctor to db and store id
                            $doctor = new Doctor($doctorData);
                            $doctor->insert($pdo);
                            $data['doctor_id'] = $doctor->getDoctorId();

                            $primaryGuardianData = [
                                'address_id' =>  !$isJunior ? null : $data['address_id'],
                                'first_name' => $nokFName,
                                'last_name' => $nokLName,
                                'relationship' => $nokRelationship,
                                // Senior nok inserts mobileNumSecondary
                                'mobile_number' => $isJunior ? $mobileNum : $mobileNumSecondary, 
                                'is_primary' => 1,
                                'apply_coach' => $apply_coach,
                                'email' => $isJunior ? $email : null
                            ];
                            
                            // Insert Primary Guardian/NOK and get id
                            $primaryGuardian = new Guardian($primaryGuardianData , null);

                            // Check if primary guardian application already exists in db 
                            $data['primary_guardian_id'] = $primaryGuardian->checkGuardianApplicationExists($pdo);
                            
                            if(!$data['primary_guardian_id']){
                                $data['primary_guardian_id'] = $primaryGuardian->insertGuardianApplication($pdo);
                            }

                            // Check Junior error
                            if($isJunior){
                                //  Guardian 2 personal info is valid
                                if (empty($nokFNameSecondaryErr) &&
                                    empty($nokLNameSecondaryErr) &&
                                    empty($nokRelationshipSecondaryErr)){
    
                                    // Junior Player Guardian Data
                                    $secondaryGuardianData = [
                                        'address_id' => null, 
                                        'first_name' => $nokFNameSecondary,
                                        'last_name' => $nokLNameSecondary,
                                        'relationship' => $nokRelationshipSecondary,
                                        'mobile_number' => $mobileNumSecondary,
                                        'is_primary' => 0,
                                        'email' => ''
                                    ];

                                    // If same address insert same address_id as primary guardian
                                    if ($sameAddress){
                                        $secondaryGuardianData['address_id'] = $data['address_id'];
                                    }
                                     // Check address error
                                    else if(
                                        empty($line1SecondaryErr) &&
                                        empty($citySecondaryErr) &&
                                        empty($postcodeSecondaryErr) &&
                                        empty($countrySecondaryErr)){
                                        
                                        $secondaryGuardianAddress = [
                                            'line1' => $line1Secondary,
                                            'line2' => $line2Secondary,
                                            'city' => $citySecondary,
                                            'postcode' => $postcodeSecondary,
                                            'country' => $countrySecondary
                                        ];

                                       
                                        // Insert second guardian address to db and get id
                                        $secondaryGuardianAddressId = Address::insert($pdo, $secondaryGuardianAddress);
                                        $secondaryGuardianData['address_id'] = $secondaryGuardianAddressId;
                                        }
                                    // No guardian address is added go back to form
                                    else{
                                        $formNum = 2;
                                        throw new \Exception("Guardian 2 address details are invalid.");
                                    }
                                    
                                    // Insert Secondary Guardian/NOK
                                    $secondaryGuardian = new Guardian($secondaryGuardianData, null);
                                    $data['secondary_guardian_id'] = $secondaryGuardian->insertGuardianApplication($pdo);
                                }  
                                else{
                                    $formNum = 2;
                                    throw new \Exception("Guardian 2 details are invalid.");
                                }      
                            } 

                            // Insert player application to db and store id
                            $playerApplicationId = Application::insert($pdo, $data);
                            $data['application_id'] = $playerApplicationId;
                            $application_id = $data['application_id'];

                            // Medical Condition Data
                            // Ensure arrays are numeric
                            $currentCondition = array_filter($currentCondition, 'is_numeric');
                            $pastCondition = array_filter($pastCondition, 'is_numeric');
                            $allergyData = array_filter($allergyData, 'is_numeric');

                            // Current Condition
                            foreach($currentCondition as $c){
                                $medicalInformationData[] = [
                                    'application_id' => $data['application_id'],
                                    'condition_id' => $c,
                                    'condition_status' => 'current'
                                ];
                            }

                            // Past Condition
                            foreach($pastCondition as $c){
                                $medicalInformationData[] = [
                                    'application_id' => $data['application_id'],
                                    'condition_id' => $c,
                                    'condition_status' => 'past'
                                ];
                            }
                                            
                            // Insert Medical Condition
                            MedicalInformation::insertConditionApplication($pdo, $medicalInformationData);

                            // Insert Allergies
                            MedicalInformation::insertAllergyApplication($pdo, $allergyData, $data['application_id']);

                            // Add all transaction to database
                            $pdo->commit();

                            
                            unset($data);
                            unset($primaryGuardian);
                            unset($primaryGuardianData);
                            unset($secondaryGuardian);
                            unset($secondaryGuardianData);
                            unset($secondaryGuardianAddress);
                            unset($doctorData);
                            unset($doctorAddress);
                            // Form successfully submitted
                            alert('success', 'Form Submitted Successfully!','/register');
                            exit();
                        }
                        catch (\Exception $e) {
                            if ($pdo->inTransaction()) {
                                $pdo->rollBack();
                            }
                            alert('error',$e->getMessage(), '/register');
                            die($e->getMessage());
                        }
                    }
                } 
            }

            $this->render('register/index', [
                // Identifiers
                'formNum' => $formNum,
                'nok' => $nok,
                'isJunior' => $isJunior,
                'age' => $age,
                'sameAddress' => $sameAddress,

                // Player Details
                'fName' => $fName,
                'lName' => $lName,
                'dob' => $dob,
                'playerNickname' => $playerNickname,
                'playerHeight' => $playerHeight,
                'playerWeight' => $playerWeight,

                // Player Details Error Message
                'fNameErr' => $fNameErr,
                'lNameErr' => $lNameErr,
                'dobErr' => $dobErr,
                'playerHeightErr' => $playerHeightErr,
                'playerWeightErr' => $playerWeightErr,

                // NOK Primary
                'nokFName' => $nokFName,
                'nokLName' => $nokLName,
                'nokFNameErr' => $nokFNameErr,
                'nokLNameErr' => $nokLNameErr,
                'nokRelationship' => $nokRelationship,
                'nokRelationshipErr' => $nokRelationshipErr,

                 // NOK Secondary
                'nokFNameSecondary' => $nokFNameSecondary,
                'nokLNameSecondary' => $nokLNameSecondary,
                'nokFNameSecondaryErr' => $nokFNameSecondaryErr,
                'nokLNameSecondaryErr' => $nokLNameSecondaryErr,
                'nokRelationshipSecondary' => $nokRelationshipSecondary,
                'nokRelationshipSecondaryErr' => $nokRelationshipSecondaryErr,

                // Address Primary
                'line1' => $line1,
                'line2' => $line2,
                'city' => $city,
                'postcode' => $postcode,
                'country' => $country,
                'line1Err' => $line1Err,
                'line2Err' => $line2Err,
                'cityErr' => $cityErr,
                'postcodeErr' => $postcodeErr,
                'countryErr' => $countryErr,

                // Address Secondary
                'line1Secondary' => $line1Secondary,
                'line2Secondary' => $line2Secondary,
                'citySecondary' => $citySecondary,
                'postcodeSecondary' => $postcodeSecondary,
                'countrySecondary' => $countrySecondary,
                'line1SecondaryErr' => $line1SecondaryErr,
                'line2SecondaryErr' => $line2SecondaryErr,
                'citySecondaryErr' => $citySecondaryErr,
                'postcodeSecondaryErr' => $postcodeSecondaryErr,
                'countrySecondaryErr' => $countrySecondaryErr,

                // Contact Details 
                'email' => $email,
                'emailErr' => $emailErr,
                'mobileNum' => $mobileNum,
                'mobileNumErr' => $mobileNumErr,
                'mobileNumSecondary' => $mobileNumSecondary,
                'mobileNumSecondaryErr' => $mobileNumSecondaryErr,

                // Medical Details
                'medicalInformations' => $medicalInformations,
                'currentCondition' => $currentCondition,
                'pastCondition' => $pastCondition,
                'allergies' => $allergies,
                'allergy' => $allergyData,

                // Doctor Details
                'doctor' => $doctor,
                'doctorErr' => $doctorErr,
                'doctorNum' => $doctorNum,
                'doctorNumErr' => $doctorNumErr,
                'line1Doctor' => $line1Doctor,
                'line1DoctorErr' => $line1DoctorErr,
                'line2Doctor' => $line2Doctor,
                'line2DoctorErr' => $line2DoctorErr,
                'cityDoctor' => $cityDoctor,
                'cityDoctorErr' => $cityDoctorErr,
                'postcodeDoctor' => $postcodeDoctor,
                'postcodeDoctorErr' => $postcodeDoctorErr,
                'countryDoctor' => $countryDoctor,
                'countryDoctorErr' => $countryDoctorErr,
                'apply_coach' => $apply_coach,


                'relationships' => [
                    'Mother',
                    'Father',
                    'Guardian',
                    'Step Parent',
                    'Grandparent',
                    'Aunt',
                    'Uncle',
                    'Sibling',
                    'Partner',
                    'Spouse',
                    'Carer',
                    'Other'
                ],
            ]);
        }

        public function registerMember()
        {
            $data =[
                'fname' => '',
                'lname' => '',
                'email' => '',
                'mobileNum' => '',
                'dob' => '',
                'address_id' => null,
                'recommended_squad' => ''
            ]; 
            
            $address = [
                'line1' => '',
                'line2' => '',
                'city' => '',
                'postcode' => '',
                'country' => ''
            ];

            $errors = [
                'fname' => '',
                'lname' => '',
                'email' => '',
                'mobileNum' => '',
                'dob' => '',
                'address_id' => ''
            ];
            $addressErrors = [
                'line1' => '',
                'line2' => '',
                'city' => '',
                'postcode' => '',
                'country' => ''
            ];

            
           
            
            if ($_POST) {
                $data['fname'] = trimPost($_POST['fname'] ?? '');
                $data['lname'] = trimPost($_POST['lname'] ?? '');
                $data['email'] = trimPost($_POST['email'] ?? '');
                $data['mobileNum'] = trimPost($_POST['mobileNum'] ?? '');
                $data['dob'] = trimPost($_POST['dob'] ?? '');

                $address['address_id'] = trimPost($_POST['address_id'] ?? '');
                $address['line1'] = trimPost($_POST['line1'] ?? '');
                $address['line2'] = trimPost($_POST['line2'] ?? '');
                $address['city'] = trimPost($_POST['city'] ?? ''); 
                $address['postcode'] = trimPost($_POST['postcode'] ?? '');
                $address['country'] = trimPost($_POST['country'] ?? '');

                $data = [
                    'fname' => 'Violet',
                    'lname' => 'McLean',
                    'email' => 'violet.mclean@example.com',
                    'mobileNum' => '07456123987',
                    'dob' => '1990-02-22',
                    'address_id' => null,
                ];

                $address = [
                    'line1' => '34 Queen Street',
                    'line2' => '',
                    'city' => 'Manchester',
                    'postcode' => 'M1 4AB',
                    'country' => 'United Kingdom'
                ];


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

                // Check empty address input
                if (empty($address['line1'])) {
                    $addressErrors['line1'] = "Address line 1 is required";
                }
                if (empty($address['city'])) {
                    $addressErrors['city'] = "City is required";
                }
                if (empty($address['postcode'])) {
                    $addressErrors['postcode'] = "Postcode is required";
                }
                if (empty($address['country'])) {
                    $addressErrors['country'] = "Country is required";
                }

                $pdo = Database::getInstance()->getConnection();
                try{
                    // Check for address errors
                    if (!array_filter($addressErrors)){
                        $pdo->beginTransaction();

                        // Insert address id to db and store id
                        $addressId  = Address::insert($pdo, $address);
                        $data['address_id'] = (int)$addressId;
                    }
                    else{
                        throw new \Exception("Address details are invalid.");
                    }
                    // Check for member details errors
                    if (!array_filter($errors))
                    {
                        // Create member object to be inserted to db
                        $member = new Member();
                        $member->first_name = $data['fname'];
                        $member->last_name = $data['lname'];
                        $member->dob = $data['dob'];
                        $member->mobile_num = $data['mobileNum'];
                        $member->address_id = $data['address_id'];
                        $member->membership_status = $data['membership_status'] ?? 'active';


                        // Check if member already exist before inserting
                        $memberExists = $member->validateInsert($pdo);
                        
                        if ($memberExists) {
                            throw new \Exception("Member already exists.");
                        }
                        
                        // Insert member to db and store id for creating login
                        $result = $member->insert($pdo);

                        // Commit transaction
                        $pdo->commit();
                        
                        // Clear Errors
                        unset($errors);
                        unset($addressErrors);

                        // Success Message and redirect to create login page with member id
                        alert('success',
                            'Member created successfully.', 
                            'create-login?member_id=' . $member->member_id);
                        exit();
                    }
                    else{
                        throw new \Exception("Member details are invalid.");
                    }
                }
                catch (\Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    alert('error',$e->getMessage(), 'create-member');
                    exit;
                }
            }
            $this->render('/register/member', [
                    'member' => $member ?? null,
                    'data' => $data,
                    'errors' => $errors,
                    'address' => $address,
                    'addressErrors' => $addressErrors
                ]);
        }

         // Create a login for a member
        public function createLogin()
        {
            // Get member id from query
            $member_id = $_GET['member_id'] ?? null;

            // If no member id is provided, display members without login
            if($member_id === null){
                alert('error', 'No member selected. Please <a href="/members-no-login">select a member</a> to create login.', 'members-no-login');
                exit();
            }

            // Get all roles for dropdown
            $roles = Role::getRole();

            // Create login object to be inserted to db
            $member_login = new User();

            // Set member id for creating login
            $member_login->member_id = $member_id; 
            $member_login->email =  Member::selectEmail($member_id) ?? '';
            $email = $member_login->email ?? '';
            
            $data = [
                'member_id' => $member_id ?? null,
                'email' => $email ?? null,
                'rawPassword' => '',
                'rawConfirmPassword' => '',
                'selectedRole' => ''
            ];


            $errors = [
                'email' => '',
                'rawPassword' => '',
                'rawConfirmPassword' => '',
                'selectedRole' => ''
            ];

            if ($_POST) {
                $data['rawPassword'] = trim($_POST['rawPassword'] ?? '');
                $data['rawConfirmPassword'] = trim($_POST['rawConfirmPassword'] ?? '');
                $data['selectedRole'] = (int)($_POST['selectedRole'] ?? '');

                // Validate Empty Email and Password Input
                if (empty($data['rawPassword'])) {
                    $errors['rawPassword'] = "Password is required";
                }
                if (empty($data['rawConfirmPassword'])) {
                    $errors['rawConfirmPassword'] = "Please confirm password";
                }
                if ($data['selectedRole'] <= 0) {
                    $errors['selectedRole'] = "Please select a role";
                }

                
                // Check if email already exists
                $result = User::checkEmailExists($data['email'],'Email is not yet registered.');

                if(!$result['emailError']){
                    $errors['email'] = 'Email is not yet registered. Please register member first before creating login.';
                }
                
                // Check password length
                if(strlen($data['rawPassword']) < 8){
                    $errors['rawPassword'] = 'Password must be atleast 8 characters<br>';
                }
                // More password strength validation
                else
                {
                    if(!preg_match("#[0-9]+#", $data['rawPassword'])) {
                        $errors['rawPassword'] .= "Your Password Must Contain At Least 1 Number!<br>";
                    }
                    if(!preg_match("#[A-Z]+#", $data['rawPassword'])) {
                        $errors['rawPassword'] .= "Your Password Must Contain At Least 1 Capital Letter!<br>";
                    }
                    if(!preg_match("#[a-z]+#", $data['rawPassword'])) {
                        $errors['rawPassword'] .= "Your Password Must Contain At Least 1 Lowercase Letter!<br>";
                    }

                    // Valid strength, confirm password
                    // Password not match
                    if($data['rawPassword'] !== $data['rawConfirmPassword']){
                        $errors['rawConfirmPassword'] = 'Password does not match';
                    }
                    // Password confirmed
                    else{
                        //Hash password
                        $salt ="4g£yc7!L(";
                        $data['password'] = md5($data['rawPassword'].$salt);

                        // No errors, add to database
                        if(!array_filter($errors)){
                                $pdo = Database::getInstance()->getConnection();
                            try{
                                $pdo->beginTransaction();

                                $user = new User();

                                $user->email = $data['email'];
                                $user->password = $data['rawPassword'];
                                $user->member_id = $data['member_id'];
                                $user->role = $data['selectedRole'];

                                $exists = User::findMemberLogin($pdo, $user->member_id);
                                
                                if($exists){
                                    throw new \Exception("This user has an existing login details.");
                                }

                                //Insert login details to db
                                User::insertLogin($pdo, $data);

                                //Insert role for member
                                Role::insertMemberRoles($pdo, $user->member_id, $user->role, $squad_id = null);

                                //Commit transaction
                                $pdo->commit();
                                alert('success', 'Login Account has been Successfully Created', 'create-login?member_id=' . $data['member_id']);
                            }
                            catch (\Exception $e) {
                                if ($pdo->inTransaction()) {
                                    $pdo->rollBack();
                                }
                                alert('error', $e->getMessage(), '/create-login?member_id=' . $data['member_id']);
                            }
                        }
                    }
                }
            }

            
            $this->render('/register/create-login', [
                'data' => $data,    
                'errors' => $errors,
                'roles' => $roles
            ]);
        }
    }
?>