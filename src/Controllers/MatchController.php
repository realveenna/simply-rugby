<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Squad;
    use Test\Models\AccessControl;
    use Test\Database;
    use Test\Models\Training;
    use Test\Models\Attendance;
    use Test\Models\Matches;

    class MatchController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        // Render training index with permission check

        public function index()
        {
            $pdo = Database::getInstance()->getConnection();

            // Get All Match Details
            $matches = AccessControl::getAuthorizedMatches($pdo, $this->member_id);

            // Not Found 
            if(!$matches){
               abort(404, 'No Matches Available');
            }
            alert('error','Match created Successfully.','/');

            
            var_dump($matches);exit;

            // var_dump($matches);exit; ##########

            $this->render('/match/index',[
                'matches' => $matches
            ]);
        }

        // Render create match with permission check
        public function create()
        {
            // Set PDO Connection
            $pdo = Database::getInstance()->getConnection();

            try{
                // Set error array
                $error = [];

                // Store only valid squads
                $availableSquads = [];
                
                // Get Squad Access
                $squads = AccessControl::getAuthorizedSquads($pdo, $this->member_id);

                if($squads){
                    // If multiple squads available
                    foreach($squads as $squad){
                        // Get squad players
                        $players = Squad::getSquadPlayers($pdo, $squad['squad_id']);

                        // Only allow squads with at least 15 players
                        if (count($players) >= 15) {
                            $availableSquads[] = $squad;
                        }
                    }
                }

                //Override squads with 15+ players 
                $squads = $availableSquads;

                if(!$squads){
                    // Check minimum players
                    alert('error', 'There are no available squad to participate in a match.', '/');
                }

                // Default match object value
                $M = new Matches(['squad_id' => $_GET['squad_id'] ?? '']);

                // Only check if squad_id exists from GET request
                if (!empty($M->squad_id)) {
                    AccessControl::validateSquadAccess($pdo, $M->squad_id);
                }

                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Create Matches object, override the original
                    $M = new Matches([
                        'squad_id' => trimPost('squad_id'),
                        'match_venue' => trimPost('match_venue'),
                        'match_date' => trimPost('match_date'),
                        'opposition_team_name' => trimPost('opposition_team_name'),
                        'kick_off_time' => trimPost('kick_off_time'),
                        'result' => trimPost('result')
                    ]);

                 
                    // Validate inputs
                    if (empty($M->squad_id)) {
                        $error['squad_id'] = "Please select a squad.";
                    }

                    if (empty($M->match_venue)) {
                        $error['match_venue'] = "Please select match venue.";
                    }

                    if (empty($M->match_date)) {
                        $error['match_date'] = "Please enter match date.";
                    }
                    else{
                        // Match date must be future date
                        $M->match_date = formatDate($M->match_date);

                        if (!isFutureDate($M->match_date)) {
                            $error['match_date'] = 'Match date must be a future date.';
                        }
                    }

                    if (empty($M->opposition_team_name)) {
                        $error['opposition_team_name'] = "Please enter opposition team name.";
                    }

                    if (empty($M->kick_off_time)) {
                        $error['kick_off_time'] = "Please enter kick off time.";
                    }

                    // No validation errors add to database
                    if (empty($error)) {
                        // Begin Transaction
                        $pdo->beginTransaction();

                        AccessControl::validateSquadAccess($pdo, $M->squad_id);
                        
                        // Get all squad players
                        $players = Squad::getSquadPlayers($pdo, $M->squad_id);

                        if(!$players){
                            throw new \ErrorException('There is no players in this squad.');
                        }
                        
                        // Check if Squad has enough playing member
                        if (count($players) < 15) {
                            throw new \ErrorException('Squad must have at least 15 players to participate in a match.');
                        }

                        // Insert match details to db
                        $M->match_id = $M->insert($pdo);
                        if(!$M->match_id){
                            throw new \ErrorException('Failed to create a match');
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success','Match created Successfully.','/match');
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/match/create');
                die($e->getMessage());
            }

            // Render
            $this->render('match/create', [
                'squads' => $squads ?? '',
                'match' => $M ?? '',
                'error' => $error
            ]);
        }



        ############
        // Render update training with permission check
        public function update()
        {
            $pdo = Database::getInstance()->getConnection();

            // Permission Check
            authorize('update_training_session');

            // Get Squad Access
            $squads = AccessControl::getAuthorizedSquads($pdo, $this->member_id);
            
            // Set error array
            $error = [];
            
            try{
                // Mandatory training session id
                $training = self::checkTrainingSessionId($pdo);

                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Permission Check Again
                    authorize('update_training_session');
                    
                     $training = self::checkTrainingSessionId($pdo);

                    // Forbidden
                    if (!hasSquadAccess($training['squad_id']) || !hasRole('Club Chairperson')){
                        abort(403);
                    }

                    // Create training object
                    $training = new Training([
                        'training_session_id' => $training['training_session_id'],
                        'skills_activities' => trimPost('skills_activities'),
                        'start_time' => trimPost('start_time'),
                        'end_time' => trimPost('end_time'),
                        'date' => trimPost('date'),
                        'coach_member_id' => $this->member_id ?? null
                    ]);
                    
                    if(empty($training->skills_activities)){
                        $error['skills_activities'] = "Please enter skills and activities.";
                    }
                    if(empty($training->date)){
                        $error['date'] = "Please enter date of training session.";
                    }
                    if(empty($training->start_time)){
                        $error['start_time'] = "Please enter start time of training session.";
                    }
                    if(empty($training->end_time)){
                        $error['end_time'] = "Please enter end time of training session.";
                    }

                    // Check valid time
                    if ($training->end_time <= $training->start_time) {
                        $error['end_time'] = 'End time must be after start time.';
                    }
                    

                    //  Date must be in future
                    $training->date;
                    if (!isFutureDate($training->date)) {
                        $error['date'] = 'Training session date must be in future date.';
                    }

                    // Begin Transaction
                    $pdo->beginTransaction();

                    // Insert training session details to db
                    $updated = $training->update($pdo);

                    if(!$updated){
                        throw new \ErrorException('Failed to update training session');
                    }

                    // Commmit and success message
                    $pdo->commit();
                    alert('success','Training Session Succesfully Updated!','/');
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/training/update?training_session_id=' .$training->training_session_id);
                die($e->getMessage());
            }

            // Render
            $this->render('match/update', [
                'squads' => $squads ?? '',
                'training' => $training ?? '',
                'error' => $error
            ]);
        }

#########
        // Render record training attendance with permission check
        public function record()
        {
            $pdo = Database::getInstance()->getConnection();

            // Permission Check
            authorize('record_attendance');

            // Set error array
            $error = [];

            // Default training_session_id value
            $training_session_id = $_GET['training_session_id'] ?? '';

            try{
                // Only check if squad_id exists from GET request
                if (!empty($_GET['training_session_id'])) {

                    // Get Training Session
                    $training = Training::getTrainingById($pdo, $training_session_id);

                    // Training session does not exist
                    if(!$training){
                        abort(404, 'Training Session Not Found.');
                    }

                    // Authorization 
                    if (!AccessControl::canViewSquad($training)) {
                        abort(403);
                    }
                }
                else{
                    alert("error","Please select a squad", '/training');
                }

                // Get Players for Training Session
                $players = Attendance::getPlayersById($pdo,$training_session_id);

                // POST REQUEST FOR ATTENDANCE SHEET
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Permission Check Again
                    authorize('record_attendance');

                    // Forbidden
                    if (!hasSquadAccess($training['squad_id']) || !hasRole('Club Chairperson')){
                        abort(403);
                    }

                    // Training date is in future show error message
                    if (isFutureDate($training['date']) || isToday($training['date'])){
                        alert('error', 
                            'Unable to update training attendance sheet. Training date must be today or past date',
                            '/training'
                        );
                    }

                    // Begin Transaction
                    $pdo->beginTransaction();

                    $attended = $_POST['attended'] ?? [];

                    // Loop each players 
                    foreach ($players as $player) {
                        // If member_id is in array then update status to Present else Absent
                        if (in_array($player['member_id'], $attended)) {
                            $status = 'Present';
                        } else {
                            $status = 'Absent';
                        }

                        // Create attendance object for each present players
                        $attendance = new Attendance(
                            $player['training_session_id'],
                            $player['member_id'],
                            $status ?? 'Pending'
                        );

                        // Update Status in database using attendance object
                        $updated =  $attendance->update($pdo);
                        if(!$updated){
                            throw new \ErrorException('Failed to mark '.$player['player_name'].' /training/record_attendance');
                        }
                    }
                     // Commmit and success message
                    $pdo->commit();
                    alert('success','Training Attendance Marked Successfuly!.','/');
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert(
                    'error', $e->getMessage(), 
                    '/training/record_attendance?training_session_id='.$training['training_session_id']);
                die($e->getMessage());
            }

            // Render
            $this->render('match/record_attendance', [
                'players' => $players,
                'training' => $training,
                'error' => $error
            ]);
        }


        // Render record training session with permission check
        public function view()
        {
            $pdo = Database::getInstance()->getConnection();

            // Permission Check
            authorize('view_training_session');

            // Set error array
            $error = [];

            try{
                $T = self::checkTrainingSessionId($pdo);

                // POST REQUEST FOR ATTENDANCE SHEET
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Permission Check Again
                    authorize('update_training_session');

                    // Forbidden
                    if (!hasSquadAccess($T['squad_id']) || !hasRole('Club Chairperson')){
                        abort(403);
                    }

                    $action = trimPost('action') ?? '';

                    if ($action === 'update') {
                        // Go to update training session page
                        header(
                            'Location: /training/update?training_session_id='
                            . $T['training_session_id']
                        );
                    }
                    elseif ($action === 'delete') {
                        // Begin Transaction
                        $pdo->beginTransaction();

                        // delete training session
                        $deleted = Training::deleteTraining($pdo, $T['training_session_id']);

                        // If not deleted
                        if(!$deleted){
                            throw new \ErrorException('Unable to delete training session');
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success', 'Training Session Deleted Successfully!', '/');
                    }
                    else{
                        abort(404, "Undefined Action");
                    }
                }
                // DEFAULT VIEW
                else{
                    // Only check if squad_id exists from GET request
                    if (!empty($_GET['training_session_id'])) {
                        // Get Players for Training Session
                        $players = Attendance::getPlayersById($pdo,$T['training_session_id']);
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
                    '/training/view?training_session_id='.$T['training_session_id']);
                die($e->getMessage());
            }

            // Render
            $this->render('match/view', [
                'players' => $players,
                'training' => $T,
                'error' => $error
            ]);
        }

        // Private function if training session id is mandatory
        // view and update
        private function checkTrainingSessionId($pdo){
            if(isset($_GET['training_session_id']) || isset($_POST['training_session_id'])){
                // Default training object value
                $training_session_id = $_GET['training_session_id']
                     ?? ($_POST['training_session_id']);

                // Get Training Session
                $training = Training::getTrainingById($pdo, $training_session_id);

                // Training session does not exist
                if(!$training){
                    abort(404, 'Training Session Not Found.');
                }

                // Authorization 
                if (!AccessControl::canViewSquad($training)) {
                    abort(403);
                }

                // Return as Training Object
                return $training;
            }
            else{
                alert("error","Please select a squad", '/training');
            }
        }
    }

?>