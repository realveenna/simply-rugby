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
    
    class TrainingController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

       
        public function index()
        {
            $pdo = $this->pdo;

            // Get Training Details
            $trainings = AccessControl::getAuthorizedTraining($pdo, $this->member_id);

            // Not Found 
            if(!$trainings){
               abort(404, 'No Training Session Found');
            }

            // Check for training status
            foreach ($trainings as &$training) {
                $training['status'] = $this->checkTrainingStatus($pdo, $training);
            }
            unset($training);

            $this->render('/training/index',[
                'trainings' => $trainings
            ]);
        }

        private function checkTrainingStatus($pdo, $training){
            // Future training
            if (isFutureDate($training['date'])) 
            {
                return 'Upcoming';
            }

            // Attendance still pending
            if ((int)$training['pending_count'] > 0) 
            {
                return 'Attendance';
            }

            // Skills not completed
            if (!Training::hasSkillRatings($pdo,  $training['training_session_id'])) 
            {
                return 'Skills';
            }

            // Fully completed
            return 'Completed';
        }

           

        // Render create training with permission check
        public function create()
        {
            $pdo = $this->pdo;

            // Permission Check
            authorize('create_training_session');

            // Set error array
            $error = [];

            // Default training object value
            $training = new Training(['squad_id' => $_GET['squad_id'] ?? '']);

            try{
                // Only check if squad_id exists from GET request
                if (!empty($training->squad_id)) {
                    AccessControl::validateSquadAccess($pdo, $training->squad_id);
                }

                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Create training object
                    $training = new Training([
                        'squad_id' => trimPost('squad_id'),
                        'skills_activities' => trimPost('skills_activities'),
                        'start_time' => trimPost('start_time'),
                        'end_time' => trimPost('end_time'),
                        'date' => trimPost('date'),
                        'coach_member_id' => $this->member_id ?? null
                    ]);

                    // Validate inputs
                    if(empty($training->squad_id)){
                        $error['squad_id'] = "Please select a squad.";
                    }
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
                    $training->date = formatDate($training->date);
                    if (!isFutureDate($training->date)) {
                        $error['date'] = 'Training session date must be in future date.';
                    }

                    // No validation errors add to database
                    if (empty($error)) {
                        // Begin Transaction
                        $pdo->beginTransaction();

                        // Validate Squad Access
                        $squad = AccessControl::validateSquadAccess($pdo, $training->squad_id);

                        // Get all squad players
                        $players = Squad::getSquadPlayers($pdo, $training->squad_id);
                        if(!$players){
                            throw new \ErrorException('There is no players in this squad.');
                        }

                        // Check section
                        $isJunior = $squad['section_name'] !== 'Senior';

                        // Insert training session details to db
                        $training->training_session_id = $training->insert($pdo);
                        if(!$training->training_session_id){
                            throw new \ErrorException('Failed to add training session');
                        }
                        
                        // Insert each player to training_attendance table
                        foreach($players as $player){
                            $added = Attendance::insert($pdo, $training->training_session_id, $player['member_id']);
                            if(!$added){
                                throw new \ErrorException
                                    ('Failed to add player: ' .$player['first_name'] . ' ' 
                                    .$player['last_name'] . 'to attendance sheet');
                            }
                            // If is Junior set email as the parent email
                            if($isJunior){
                                $recipient = $player['guardian_email'];
                                $name = $player['guardian_first_name'];
                            }
                            // Else use player's own email
                            else{
                                $recipient = $player['player_email'];
                                $name = $player['first_name'];
                            }
                            // Continue if recipient is null
                            if(empty($recipient)){
                                continue;
                            }
                            // Send Mail 
                            MailController::newTraining([
                                    'name' => ucfirst($name),
                                    'start_time' => formatTime($training->start_time),
                                    'end_time' => formatTime($training->end_time),
                                    'date' => formatDate($training->date),
                                    'activities' => $training->skills_activities,
                                    'recipient' => trim($recipient)
                                ],
                            );
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success','Training session created successfully.','/');
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/training/create');
                die($e->getMessage());
            }

            // Render
            $this->render('training/create', [
                'squads' => $this->squads,
                'training' => $training ?? '',
                'error' => $error
            ]);
        }
        // Render update training with permission check
        public function update()
        {
            $pdo = $this->pdo;

            // Permission Check
            authorize('update_training_session');
           
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
            $this->render('training/update', [
                'squads' => $this->squads, 
                'training' => $training ?? '',
                'error' => $error
            ]);
        }


        // Render record training attendance with permission check
        public function record()
        {
            $pdo = $this->pdo;

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
            $this->render('training/record_attendance', [
                'players' => $players,
                'training' => $training,
                'error' => $error
            ]);
        }

        // Render record training player skill with permission check
        public function recordSkill()
        {
            $pdo = $this->pdo;

            // Set error array
            $error = [];

            try{
                // Default training_session_id value
                $training_session_id = $_GET['training_session_id'] ?? '';

                // Only check if squad_id exists from GET request
                if (!empty($_GET['training_session_id'])) {

                    // Get Training Session
                    $training = Training::getTrainingById($pdo, $training_session_id);
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
                    '/training/record_player_skills?training_session_id='.$training['training_session_id']);
                die($e->getMessage());
            }

            // Render
            $this->render('training/record_player_skills', [
                'players' => $players,
                'training' => $training,
                'error' => $error
            ]);
        }


        // Render record training session with permission check
        public function view()
        {
            $pdo = $this->pdo;

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
            $this->render('training/view', [
                'players' => $players,
                'training' => $T,
                'error' => $error
            ]);
        }

        // Private function if training session id is mandatory
        // view and update
        private function checkTrainingSessionId($pdo){
            if(isset($_GET['training_session_id']) || isset($_POST['training_session_id'])){
                // Default training_session_id value
                $training_session_id = $_GET['training_session_id']
                     ?? ($_POST['training_session_id']);

                // Get Training Session
                $training = Training::getTrainingById($pdo, $training_session_id);

                // Training session does not exist
                if(!$training){
                    abort(404, 'Training Session Not Found.');
                }

                // Validation of Squad 
                AccessControl::validateSquadAccess($pdo, $training['squad_id']);

                // Return as Training Object
                return $training;
            }
            else{
                alert("error","Please select a squad", '/training');
            }
        }
    }

?>