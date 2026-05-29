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
        public function record()
        {
            $pdo = $this->pdo;
            $presentPlayers = [];
            $injuries = Injury::getAllInjuries($pdo);
            $params = self::getParams();

            
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
                    
                    $error['injury_id'] = ifEmpty($_POST['injury_id'] ?? '', 'Please select an injury.');
                    $error['injury_status'] = ifEmpty($_POST['injury_status'] ?? '', 'Please select an injury status.');
                    $error['member_id'] = ifEmpty($_POST['member_id'] ?? '', 'Please select a player.');

                    if (empty($_POST['training_session_id']) && empty($_POST['match_id'])) {
                        $error['event'] = 'Please select either a training session or a match.';
                    }
                    $event_id = $_POST['training_session_id'] ?? $_POST['match_id'];

                    // No error
                    if(!array_filter($error)){
                         $inserted = $injury->insert($pdo);
                        if (!$inserted) {
                            throw new \ErrorException('Failed to record injury.');
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success', 'Player Injury Successfully Added!', '/');
                    }
                }
            }
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/injury?'.$params);
            }
            $this->render('injury/index', [
                'match' => $match ?? '',
                'training' => $training ?? '',
                'presentPlayers' => $presentPlayers ?? [],
                'injuries' => $injuries ?? [],
                'error' => $error ?? [],
            ]);
        }

        // Update injury with permission check
        public function update()
        {
            $pdo = $this->pdo;
            $params = self::getParams();

            try{
                // If player_injury_id is requested by GET or POST
                $player_injury_id =  $_GET['player_injury_id'] ?? $_POST['player_injury_id'] ?? '';
                if(!empty($player_injury_id)){
                    $data = Injury::getPlayerInjuryId($pdo, $player_injury_id);
                    if(!$data){
                        abort('404', 'Player injury not found');
                    } 
                    // Validation of Squad 
                    AccessControl::validateSquadAccess($pdo, $data['squad_id']);
                }
                // GET/POST request is a must so abort
                else{
                    abort('404', 'Please select a player with injury to continue.');
                }
                

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Begin Transaction
                    $pdo->beginTransaction();

                    $injury = new Injury($_POST);

                    $updated = $injury->update($pdo);

                     if (!$updated) {
                        throw new \ErrorException('Failed to update injury.');
                    }

                    // Commmit and success message
                    $pdo->commit();

                    alert('success', 'Player Injury Successfully updated!', '/');
                }
            }
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/injury/update?'.$params);
                die();
            }
            $this->render('injury/update', [
                'data' => $data ?? []
            ]);
        }

         // Render all injury with permission check
        public function all()
        {
            $pdo = $this->pdo;

            // Get authorized injury access
            $injuries = AccessControl::getAuthorizedInjury($pdo, $this->member_id);

            // Forbidden Access
            if(!$injuries ){
                abort(403);
            }
            
            $this->render('injury/all', [
                'data' => $injuries ?? [],
            ]);
        }


        private function getParams(){
            // GET event type and id
            $event_type = null;
            $event_id = null;

            // Training
            if (isset($_GET['training_session_id']) || isset($_POST['training_session_id'])) {
                $event_type = 'training_session_id';
                $event_id = $_GET['training_session_id'] ?? $_POST['training_session_id'];
            }
            // Match
            elseif (isset($_GET['match_id']) || isset($_POST['match_id'])) {
                $event_type = 'match_id';
                $event_id = $_GET['match_id'] ?? $_POST['match_id'];
            }
            return $event_type && $event_id ? "$event_type = $event_id": null;
        }
    }
?>
