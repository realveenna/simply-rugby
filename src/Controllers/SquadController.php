<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Squad;
    use Test\Database;
    
    class SquadController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        // Display Squad with permission check
        // List of all squads
        public function index()
        {
            // Permission Check
            authorize('view_squad');
            
            $pdo = Database::getInstance()->getConnection();

            $squads = Squad::getSquadAccess($pdo, $this->member_id, $this->rbac);
            
            $players = [];

            // Forbidden Access
            if(!$squads ){
                $error = new Error();
                $error->forbidden();
                exit;
            }
            
            try{
                // If searching for squad players
                if (isset($_GET['name']))
                {
                    $squad_name = $_GET['name'];

                    // Get squad details
                    $squad = Squad::getSquadByName($pdo, $squad_name);

                    if(!$squad){
                        $error = new Error();
                        $error->notFound('Squad Not Found!');
                        exit;
                    }

                    // Authorization 
                    if (!Squad::canViewSquad($squad)) {
                        $error = new Error();
                        $error->forbidden();
                        exit;
                    }
                    
                    // Get squad players
                    $players = Squad::listSquadPlayer($pdo, $squad['squad_id']);

                    // Go to page
                    $this->render('squad/name', [
                        'squad' => $squad,
                        'players' => $players
                    ]);
                    return;
                }
                $this->render('squad/index', [
                    'squads' => $squads
                ]);
            }
            catch (\Exception $e){
                alert('errors', $e->getMessage(), '/');
            }
        }

        // List of all members
        public function squadMember()
        {
            $pdo = Database::getInstance()->getConnection();

            $squads = Squad::getAllSquads($pdo);

            $squad_player = Squad::listSquadPlayer($pdo, $squads['squad_id']);

            $this->render('squad/index', [
                'squads' => $squads,
                'squadPlayers' => $squad_player,
            ]);
        }
        
    }
?>