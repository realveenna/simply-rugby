<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Squad;
    use Test\Database;
    use Test\Models\Training;
    
    class TrainingController extends Controller
    {
        public function __construct()
        {
            $this->member_id = $_SESSION['user']['member_id'] ?? null;
            $this->rbac = $_SESSION['rbac'] ?? null;
        }

        // List of all squads
        public function index()
        {
            $pdo = Database::getInstance()->getConnection();

            $events = Training::getAllTraining($pdo);

            $this->render(
                'training/index',
                $events
            );
    }

        // Render create training with permission check
        public function create()
        {
            // Permission Check
            authorize('create_training_session');

            $pdo = Database::getInstance()->getConnection();

            $error = [];
            $selectedSquad = $training['squad_id'] ?? '';
            
            // Default training values if getAllSquads, set squad_id
            $training = [
                'squad_id' => $_GET['squad_id'] ?? '',
                'skills_activities' => ''
            ];

            // Only check if squad_id exists
            if (!empty($training['squad_id'])) {
                $squad = Squad::getSquadById($pdo, $training['squad_id']);
                // Squad does not exist
                if(!$squad){
                    $error = new Error();
                    $error->notFound('Squad not found.');
                    exit;
                }

                // Authorization 
                if (!Squad::canViewSquad($squad)) {
                    $error = new Error();
                    $error->forbidden();
                    exit;
                }
            }

            try{
                // Get Squad Access
                 $squads = Squad::getSquadAccess($pdo, $this->member_id, $this->rbac);

                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $data = [
                        'squad_id' => trimPost('squad_id'),
                        'skills_activities' => trimPost('skills_activities'),
                        'training_date' => trimPost('training_date'),
                        'start_time' => trimPost('start_time'),
                        'end_time' => trimPost('end_time'),
                        'coach_member_id' => $this->member_id ?? null
                    ];

                    // IMPORTANT
                    $training = $data;

                    if(empty($data['squad_id'])){
                        $error['squad_id'] = "Please select a squad.";
                    }
                    if(empty($data['skills_activities'])){
                        $error['skills_activities'] = "Please enter skills and activities.";
                    }
                    if(empty($data['training_date'])){
                        $error['training_date'] = "Please enter date of training session.";
                    }
                    if(empty($data['start_time'])){
                        $error['start_time'] = "Please enter start time of training session.";
                    }
                    if(empty($data['end_time'])){
                        $error['end_time'] = "Please enter end time of training session.";
                    }

                    // Check valid time
                    if ($data['end_time'] <= $data['start_time']) {
                        $error['end_time'] = 'End time must be after start time.';
                    }
                    
                    $data['training_date'] = formatDate($data['training_date']);
                    if (!isFutureDate($data['training_date'])) {
                        $error['training_date'] = 'Training session date must be in future date.';
                    }
                    
                    $pdo->beginTransaction();

                    // No validation errors add to database
                    if (empty($error)) {
                        // Get submitted squad
                        $squad = Squad::getSquadById($pdo, $data['squad_id']);

                        // Squad does not exist
                        if(!$squad){
                            $error = new Error();
                            $error->notFound('Squad not found.');
                            exit;
                        }

                        // No Access 
                        if (!Squad::canViewSquad($squad)) {
                            $error = new Error();
                            $error->forbidden();
                            exit;
                        }

                        // Insert training session details to db
                        $training_id = Training::insert($pdo, $data);
                        if(!$training_id){
                            throw new \ErrorException('Failed to add training session');
                        }

                        // Insert members to attendance
                        $attendance = Attendance::insert($pdo, $data);
                        if(!$attendance){
                            throw new \ErrorException('Failed to create training attendance');
                        }

                        $pdo->commit();
                        alert('success','Training session created successfully.','/');
                    }
                }
            }
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/training/create');
                die($e->getMessage());
            }

            // SINGLE RENDER
            $this->render('training/create', [
                'squads' => $squads,
                'training' => $training,
                'error' => $error
            ]);
        }
    }
?>