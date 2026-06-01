<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\MedicalInformation;
    use Test\Models\Guardian;
    use Test\Models\Address;
    use Test\Models\AccessControl;
    use Test\Database;
    use Test\Models\Matches;
    use Test\Models\PlayerSkill;
    use Test\Models\Injury;
    use Test\Models\Attendance;
    use Test\Models\Player;
    use Test\Models\Squad;

    class PlayerController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }


        // Render display player details
        public function displayPlayer()
        {
            $pdo = $this->pdo;

            $player = [];

            try{
                // Find player 
                if(isset($_GET['id'])){
                    $member_id = $_GET['id'];
                }
                else{
                    abort(404, 'Player ID is not found.');
                }

                // Get player profile
                $player = Player::playerProfile($pdo, $member_id);
                
                // Not a player no access
                if(!$player){
                    abort(404, 'Player Not Found.');
                }
                

                // Authorization 
                if (!AccessControl::canViewPlayer($player, (int)$_SESSION['user']['member_id'], 
                    (int)$member_id)) {
                    abort(403);
                }

                // Renewal Date
                $renewalReminder = Squad::getRenewalReminder($pdo, $player['squad_id']);

                // Get Player Training Stats
                $s = new PlayerSkill($player);
                $skills = $s->getPlayerSkill($pdo);
                $averages = $s->calculateAverageRatingByCategory($pdo);

                // get event attendance
                $matchAttendance = Attendance::getPlayerMatchAttendance($pdo, $player['member_id']);
                $trainingAttendance = Attendance::getPlayerTrainingAttendance($pdo, $player['member_id']);

                // get injuries
                $injuries = Injury::getPlayerInjuries($pdo, $player['member_id']);

                // Get All Match Stats
                $allStats = Matches::getAllMatchStats($pdo, $player['member_id']);

                // Get player Guardian/NOK details and addresses
                $pGuardian = Guardian::getPlayerGuardian($pdo, $member_id, 1);
                $sGuardian = Guardian::getPlayerGuardian($pdo, $member_id, 0);

                // Get address details for player application, primary and secondary guardian
                $player['address'] = Address::getAddressDetails($pdo, $player['address_id']);
                $pGuardian['address'] = Address::getAddressDetails($pdo, $pGuardian['address_id']);
                if($sGuardian && $sGuardian['address_id'] !== null){
                    $sGuardian['address'] = Address::getAddressDetails($pdo, $sGuardian['address_id']);
                }

                // Get player allergies
                $allergies = MedicalInformation::getPlayerAllergies
                    ($pdo, 'player_allergy', $member_id, 'member_id', '/player');

                // Get player current condition
                $current = MedicalInformation::getPlayerConditions
                    ($pdo, 'player_condition', $member_id, 'member_id', 'current', '/player');

                // Get player past condition
                $past = MedicalInformation::getPlayerConditions
                    ($pdo, 'player_condition', $member_id, 'member_id', 'past', '/player');

                // Get player doctor information
                $doctor = MedicalInformation::getPlayerDoctor
                    ($pdo, 'player_profile', $member_id, 'member_id');
                
                // Doctor address
                $doctor['address'] = MedicalInformation::getDoctorAddressId
                    ($pdo, $doctor['doctor_id']);

                // Identify if player is junior or senior and display correct nok title
                $isJunior = $player['squad_name'] === 'Senior' ? false : true;
                $nok = $isJunior === true ? 'Primary Guardian' : 'Next of Kin';

                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if(hasPermission('update_member')){  
                        // If delete button is pressed
                        MemberController::delete($pdo, $player['member_id']);
                    }
                }
            }
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/player/index');
            }
            
            $this->render('player/index', [
                'player' => $player,
                'pGuardian' => $pGuardian ?? [],
                'sGuardian' => $sGuardian ?? [],
                'allergies' => $allergies ?? [],
                'current' => $current ?? [],
                'past' => $past ?? [],
                'doctor' => $doctor ?? [],
                'nok' => $nok,
                'skills' => $skills ?? [],
                'averages' => $averages ?? [],
                'matchStats' => $matchStats ?? [],
                'matchAttendance' => $matchAttendance ?? [],
                'trainingAttendance' => $trainingAttendance ?? [],
                'injuries' => $injuries ?? [],
                'allStats' => $allStats ?? [],
                'renewalReminder' => $renewalReminder ?? '', 
            ]);
        }


        // Render update player details
        public function update()
        {
            $pdo = $this->pdo;

            $player = [];
            $error = [];
            $data = [];
            $positions = MatchController::listAllPositions();

            try{

                // IF member_id is requested by GET or POST
                if(isset($_GET['member_id']) || isset($_POST['member_id'])){

                    // Set member_id
                    $member_id = $_GET['member_id']?? ($_POST['member_id']);
                }
                else{
                    abort(404, 'Player ID is not found.');
                }

                // Get player profile
                $player = Player::playerProfile($pdo, $member_id);

     
                // Authorization 
                if (!AccessControl::canViewPlayer($player, (int)$_SESSION['user']['member_id'], 
                    (int)$member_id)) {
                    abort(403);
                }

                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Begin Transaction
                    $pdo->beginTransaction();

                    $edit = trimPost('edit');

                    $data = [
                        'member_id' => (int)$player['member_id'],
                        'height' => (int)$player['height'],
                        'weight' => (float)$player['weight'],
                        'position' => $player['position'],
                        'player_availability_status' => $player['player_availability_status']
                    ];


                    // edit player_profile
                    if($edit === 'player_profile'){
                        if(!hasPermission('update_player_details')){
                            abort(403);
                        }
                        $error['height'] =
                            ifEmpty($data['height'], 'Height is required');
                        $error['weight'] =
                            ifEmpty($data['weight'], 'Weight is required');
                        $error['position'] =
                            ifEmpty($data['position'], 'Position is required');

                        
                        // Override data
                        $data['position'] =  trimPost('position');
                        $data['height'] =  (int)trimPost('height');
                        $data['weight'] =  (float)trimPost('weight');

                    }
                    
                    // edit player_availability
                    if($edit === 'player_availability'){
                        // Permission Check
                        if(!hasPermission('update_player_availability')){
                            abort(403);
                        }
                        
                        // Validate no input
                        $error['player_availability_status'] =
                            ifEmpty($data['player_availability_status'], 'Availability status is required');
                        

                        // Override data
                        $data['player_availability_status'] =  trimPost('player_availability_status');

                    }

                    $p = new Player($data);

                    // Update player information
                    // Filter array for empty/null
                    $error = array_filter($error);

                    // No error then update
                    if(empty($error)){
                        $updated = $p->updatePlayerProfile($pdo);

                        if(!$updated){
                            throw new \ErrorException('Failed to update player profile');
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success', 'Player Details Updated Successfully!', '/');
                        exit;
                    }
                }
            }
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/player/update?member_id='.$player['member_id']);
            }
            
            $this->render('player/update', [
                'player' => $player,
                'positions' => $positions
            ]);
        }

    }
?>