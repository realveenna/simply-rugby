<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\AccessControl;
    use Test\Models\Matches;
    use Test\Models\Training;
    use Test\Models\Attendance;
    use Test\Models\Injury;

    class InjuryController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        // Record injury with permission check
        public function index()
        {
            $pdo = $this->pdo;
            $presentPlayers = [];
            $injuries = Injury::getAllInjuries($pdo);
            
            try{
                // IF match_id is requested by GET or POST
                if(isset($_GET['match_id']) || !empty($_POST['match_id'])){

                    // Set match_id
                    $match_id = $_GET['match_id'] ?? ($_POST['match_id']);

                    // Get Match Details 
                    $match = AccessControl::validateMatchAccess($pdo, $match_id);
                    
                    if(isFutureDate($match['match_date'])){
                        abort(500, 'Unable to select upcoming match');
                    }

                    // Get players
                    $players = Matches::getLineup($pdo, $match['match_id']);
                    if(!$players){
                        abort(500, 'No players in this match');
                    }

                    foreach ($players as $player) {
                        $presentPlayers[] = [
                            'member_id' => $player['player_id'],
                            'player_name' => $player['player_name']
                        ];
                    }
                }
                // IF training_session_id is requested by GET or POST
                if(isset($_GET['training_session_id']) || !empty($_POST['training_session_id']))
                {
                    // Default training_session_id value
                    $training_session_id = $_GET['training_session_id'] ?? ($_POST['training_session_id']);

                    // Get Training Session
                    $training = Training::getTrainingById($pdo, $training_session_id);

                    // Training session does not exist
                    if(!$training){
                        abort(404, 'Training Session Not Found.');
                    }

                    // Validation of Squad 
                    AccessControl::validateSquadAccess($pdo, $training['squad_id']);
                    $players = Attendance::getPlayersById($pdo, $training_session_id );

                    // Append present player to array 
                    foreach ($players as $player) {
                        if ($player['attendance_status'] === 'Present') 
                        {
                            $presentPlayers[] = [
                                'member_id' => $player['member_id'],
                                'player_name' => $player['player_name']
                            ];
                        }
                    }
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Begin Transaction
                    $pdo->beginTransaction();

                    $injury = new Injury($_POST);

                    $inserted = $injury->insert($pdo);
                    if (!$inserted) {
                        throw new \ErrorException('Failed to record injury.');
                    }

                     // Commmit and success message
                    $pdo->commit();
                    alert('success', 'Player Injury Successfully Added!', '/');

                }
            }
            catch (\Exception $e){
                alert('errors', $e->getMessage(), '/injury');
            }
            $this->render('injury/index', [
                'match' => $match ?? '',
                'training' => $training ?? '',
                'presentPlayers' => $presentPlayers ?? [],
                'injuries' => $injuries ?? [],
            ]);
        }
    }
?>