<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Address;
    use Test\Models\MedicalCondition;
    use Test\Models\Application;
    use Test\Models\Doctor;
    use Test\Models\Guardian;

    use Test\Database;
    use PDO;


    class AccountController extends Controller
    {
        // Register an account
        public function register()
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
            $medicalConditions = MedicalCondition::viewAllCondition();

             // Get allergy array
            $allergies = MedicalCondition::viewAllAllergy();

            $formNum = 1;
            $age = '';
            $isJunior = '';
            $nok = 'Next of Kin';
            $sameAddress = false;
           
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
            $medicalConditionData = [];
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

            if ($_POST) {
                // $fName = trimPost('fName') ?? '';
                // $lName = trimPost('lName') ?? '';
                // $dob = trimPost('dob') ?? '';
                // $playerNickname = trimPost('playerNickname') ?? '';
                // $playerHeight = trimPost('playerHeight') ?? '';
                // $playerWeight = trimPost('playerWeight') ?? '';

                // $nokFName = trimPost('nokFName') ?? '';
                // $nokLName = trimPost('nokLName') ?? '';
                // $nokRelationship = trimPost('nokRelationship') ?? '';         
                
                // $nokFNameSecondary = trimPost('nokFNameSecondary') ?? '';
                // $nokLNameSecondary = trimPost('nokLNameSecondary') ?? '';
                // $nokRelationshipSecondary = trimPost('nokRelationshipSecondary') ?? '';
                // $email = trimPost('email') ?? '';
                // $mobileNum = trimPost('mobileNum') ?? '';
                // $mobileNumSecondary  = trimPost('mobileNumSecondary') ?? '';

                // $line1 = trimPost('line1') ?? '';
                // $line2 = trimPost('line2') ?? '';
                // $city = trimPost('city') ?? '';
                // $postcode = strtoupper(trimPost('postcode')) ?? '';
                // $country = trimPost('country') ?? '';

                // $line1Secondary = trimPost('line1Secondary') ?? '';
                // $line2Secondary = trimPost('line2Secondary') ?? '';
                // $citySecondary  = trimPost('citySecondary') ?? '';
                // $postcodeSecondary = strtoupper(trimPost('postcodeSecondary') )?? '';
                // $countrySecondary = trimPost('countrySecondary') ?? '';

                // $sameAddress = isset($_POST['sameAddress']);
                // $isJunior = isset($_POST['isJunior']) && $_POST['isJunior'] === '1';
                //    // Medical Data
                // $currentCondition = $_POST['currentCondition'] ?? [];
                // $pastCondition = $_POST['pastCondition'] ?? [];

                // // Allergies
                // $allergyData = $_POST['allergies'] ?? [];

                // // Doctor Data
                // $doctor = trimPost('doctor');
                // $doctorNum = trimPost('doctorNum');
                // $line1Doctor = trimPost('line1Doctor');
                // $line2Doctor = trimPost('line2Doctor');
                // $cityDoctor = trimPost('cityDoctor');
                // $postcodeDoctor = strtoupper(trimPost('postcodeDoctor')); 
                // $countryDoctor = trimPost('countryDoctor'); 
$fName = 'Jason';
$lName = 'McGregor';
$dob = '2020-03-22';
$playerNickname = 'Dan';
$playerHeight = '180';
$playerWeight = '82';
$isJunior = true;


// ✅ NOK still provided (emergency contact)
$nokFName = 'Laura';
$nokLName = 'McGregor';
$nokRelationship = 'Mother';

$nokFNameSecondary = 'Peter';
$nokLNameSecondary = 'McGregor';
$nokRelationshipSecondary = 'Father';

// ✅ Player contact
$email = 'laura.mcgregor@example.com';
$mobileNum = '07777123456';
$mobileNumSecondary = '07888999888'; // secondary phone present

// ✅ Address
$line1 = '12 King Street';
$line2 = '';
$city = 'Leeds';
$postcode = 'LS1 4AB';
$country = 'United Kingdom';

// No secondary address
$line1Secondary = '';
$line2Secondary = '';
$citySecondary = '';
$postcodeSecondary = '';
$countrySecondary = '';

$sameAddress = false;

// ✅ Medical
$currentCondition = [1];       
$pastCondition    = [];      
$allergyData      = [2];   

// ✅ Doctor
$doctor = "Dr Ahmed";
$doctorNum = "07900123456";
$line1Doctor = "22 Health Centre";
$line2Doctor = "";
$cityDoctor = "Leeds";
$postcodeDoctor = "LS2 9JT";
$countryDoctor = "United Kingdom";
             

                // Default data to be passed
                $data = [
                    // Player Data
                    'fName' => $fName,
                    'lName' => $lName,
                    'dob' => $dob,
                    'playerNickname' => $playerNickname,
                    'playerHeight' => $playerHeight,
                    'playerWeight' => $playerWeight,
                    'email' => $email,

                    // Primary/Foreign Key
                    'application_id' => null, 
                    'address_id' => null, 
                    'doctor_id' => null,
                    'mobileNum' => $mobileNum, 

                    // Guardian 2
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
                    'application_id' => $data['application_id'],
                    'address_id' => null,
                    'first_name' => $nokFName,
                    'last_name' => $nokLName,
                    'relationship' => $nokRelationship,
                    // Senior nok inserts mobileNumSecondary
                    'mobile_number' => $isJunior ? $mobileNum : $mobileNumSecondary, 
                    'is_primary' => 1
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
                    
                    // Check if email already exists
                    $result = User::checkEmailExists($email);
                    if(isset($result['emailError'])){
                        $emailErr = $result['emailError'] ;
                    }
                    
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

                            // Insert address id to db and store id
                            $addressId  = Address::insert($pdo, $data);
                            $data['address_id'] = (int)$addressId;

                            // Insert doctor address to db and store id
                            $doctorAddressId  = Address::insert($pdo, $doctorAddress);
                            $data['doctor_address_id'] = $doctorAddressId;
                            $doctorData['address_id'] = $doctorAddressId;

                            // Insert doctor to db and store id
                            $doctorId  = Doctor::insert($pdo, $doctorData);
                            $data['doctor_id'] = $doctorId;

                            
                            // Insert player application to db and store id
                            $playerApplicationId = Application::insert($pdo, $data);
                            $data['application_id'] = $playerApplicationId;
                            $application_id = $data['application_id'];

                                // Medical Condition Data
                            // Current Condition
                            foreach($currentCondition as $c){
                                $medicalConditionData[] = [
                                    'application_id' => $data['application_id'],
                                    'condition_id' => $c,
                                    'condition_status' => 'current'
                                ];
                            }

                            // Past Condition
                            foreach($pastCondition as $c){
                                $medicalConditionData[] = [
                                    'application_id' => $data['application_id'],
                                    'condition_id' => $c,
                                    'condition_status' => 'past'
                                ];
                            }
                                            
                            // Insert Medical Condition
                            MedicalCondition::insertConditionApplication($pdo, $medicalConditionData);

                            // Insert Allergies
                            MedicalCondition::insertAllergyApplication($pdo, $allergyData, $data['application_id']);

                            $primaryGuardianData = [
                                'application_id' => $data['application_id'],
                                'address_id' => null,
                                'first_name' => $nokFName,
                                'last_name' => $nokLName,
                                'relationship' => $nokRelationship,
                                // Senior nok inserts mobileNumSecondary
                                'mobile_number' => $isJunior ? $mobileNum : $mobileNumSecondary, 
                                'is_primary' => 1
                            ];
                            
                            // Insert Primary Guardian/NOK
                            $primaryGuardian = new Guardian($primaryGuardianData);
                            $primaryGuardian->insertGuardianApplication();

                            
                            // Check Junior error
                            if($isJunior){
                                //  Guardian 2 personal info is valid
                                if (empty($nokFNameSecondaryErr) &&
                                    empty($nokLNameSecondaryErr) &&
                                    empty($nokRelationshipSecondaryErr)){
    
                                    // Junior Player Guardian Data
                                    $secondaryGuardianData = [
                                        'application_id' => $data['application_id'],
                                        'address_id' => $data['address_id'], 
                                        'first_name' => $nokFNameSecondary,
                                        'last_name' => $nokLNameSecondary,
                                        'relationship' => $nokRelationshipSecondary,
                                        'mobile_number' => $mobileNumSecondary,
                                        'is_primary' => 0
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
                                        throw new \Exception("Guardian details failed");
                                    }
                                    
                                    // Insert Primary Guardian/NOK
                                    $primaryGuardian = new Guardian($primaryGuardianData);
                                    $primaryGuardian->insertGuardianApplication();
                                }                                
                            }
                            // Add all transaction to database
                            $pdo->commit();

                            // Form successfully submitted
                            $_SESSION['success'] = "Form Submitted Successfully!";
                            $formNum = 1;
                        }
                        catch (\Exception $e) {
                            if ($pdo->inTransaction()) {
                                $pdo->rollBack();
                            }
                            alert('error','Something went wrong.', 'register');
                            die($e->getMessage());
                        }
                    }else{
                        alert('error','Something went wrong.', 'register');
                    }
                } 
            }

            $this->render('register', [
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
                'medicalConditions' => $medicalConditions,
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
        public function createAccount()
        {
            $email = '';
            $rawPassword = '';
            $rawConfirmPassword = '';

            $emailErr = '';
            $rawPasswordErr = '';
            $rawConfirmPasswordErr = '';
            
            if ($_POST) {
                $email = trim($_POST['email'] ?? '');
                $rawPassword = $_POST['rawPassword'] ?? '';
                $rawConfirmPassword = $_POST['rawConfirmPassword'] ?? '';

                // Validate Empty Email and Password Input
                if (empty($rawPassword)) {
                    $rawPasswordErr = "Password is required";
                }
                if (empty($rawConfirmPassword)) {
                    $rawConfirmPasswordErr = "Please confirm password";
                }

                if (empty($email)) {
                    $emailErr = "Email is required";
                } else {
                    $email = trim($email);
                    // If invalid email format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                    else{
                        // Check if email already exists
                        $result = User::checkEmailExists($email);
                        if(isset($result['emailError'])){
                            $emailErr = $result['emailError'];
                        }
                        else{
                            // Check password length
                            if(strlen($rawPassword ) < 8){
                                $rawPasswordErr = 'Password must be atleast 8 characters';
                            }
                            elseif(!preg_match("#[0-9]+#",$rawPassword)) {
                                $rawPasswordErr = "Your Password Must Contain At Least 1 Number!";
                            }
                            elseif(!preg_match("#[A-Z]+#",$rawPassword)) {
                                $rawPasswordErr = "Your Password Must Contain At Least 1 Capital Letter!";
                            }
                            elseif(!preg_match("#[a-z]+#",$rawPassword)) {
                                $rawPasswordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
                            }
                            // Valid strength, confirm password
                            else{
                                // Password not match
                                if($rawPassword !== $rawConfirmPassword){
                                    $rawConfirmPasswordErr = 'Password does not match';
                                }
                                // Password confirmed
                                else{
                                     // Hash password
                                    $salt ="4g£yc7!L(";
                                    $pass = md5($rawPassword.$salt);

                                    // Add to database
                                    $success  = User::create($email, $pass);
                                    if ($success) {
                                        header("Location: /login");
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->render('createAccount', [
                'email' => $email,
                'emailErr' => $emailErr,
                'rawPasswordErr' => $rawPasswordErr,
                'rawConfirmPasswordErr' => $rawConfirmPasswordErr
            ]);
        }

        public function logout(){
            // remove and destroy session
            session_unset();
            session_destroy();

            header("Location: /login");
            exit();
        }
    }
?>