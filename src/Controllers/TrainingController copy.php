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

        // Render create training 
        public function createTraining()
        {
            $pdo = Database::getInstance()->getConnection();

            try{
                // GET
                if($_SERVER['REQUEST_METHOD'] === 'GET') {

                    $squad = Squad::getAllSquads($pdo);

                    $this->render('training/create', [
                        'squad' => $squad
                    ]);
                    return; 
                }

                // POST
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $pdo->beginTransaction();

                    // Set data
                    $data = [
                        'squad_id' => $_POST['squad_id'],
                        'coach_member_id' => $_POST['coach_member_id'],
                        'date' => $_POST['date'],
                        'time' => $_POST['time'],
                        'skills_activities' => $_POST['skills_activities']
                    ];

                    $training = Training::createTrainingSession($pdo, $data);
                    if(!$training){
                        throw new \Exception('Failed to create training session.');
                    }

                    // Commit database changes
                    $pdo->commit();

                    // Success messsage
                    alert(
                        'success',
                        'Training session created successfully',
                        '/training'
                    );
                }

            }
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error',$e->getMessage(), '/training');
            }
        }
        
    }
?>