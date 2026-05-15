<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\MedicalInformation;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Player;
    use Test\Models\Guardian;
    use Test\Models\Address;
    use Test\Database;

    
    class PlayerController extends Controller
    {
        public function __construct()
        {
            
        }

        // List of all members
         public function index()
        {
            // $squad = Member::selectAll();

            // foreach ($squad as &$member ){
            //     $member['roles'] = Role::getMemberRoleName($member['member_id']);
            // }

            // $this->render('squad/index', [
            //     'squad' => $squad
            // ]);
        }

        public function displayPlayer()
        {
            $pdo = Database::getInstance()->getConnection();

            $player = [];
            $guardian = [];

            try{
                // Find player 
                if(isset($_GET['id'])){
                    $member_id = $_GET['id'];
                }
                else{
                    alert('error', 'Player ID is not found.', '/');
                }

                // Get player profile
                $player = Player::playerProfile($pdo, $member_id);
                if(!$player){
                    throw new \ErrorException('Player Not Found');
                }

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
                $isJunior = $player['squad_type'] === 'Senior' ? false : true;
                $nok = $isJunior === true ? 'Primary Guardian' : 'Next of Kin';
            }
            catch (\Exception $e){

            }

            
            $this->render('player/index', [
                'player' => $player,
                'pGuardian' => $pGuardian,
                'sGuardian' => $sGuardian,
                'allergies' => $allergies,
                'current' => $current,
                'past' => $past,
                'doctor' => $doctor,
                'nok' => $nok
            ]);
        }
    }
?>