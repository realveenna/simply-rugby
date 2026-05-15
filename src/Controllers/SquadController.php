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
            
        }

        // List of all squads
        public function index()
        {
            $pdo = Database::getInstance()->getConnection();

            $squads = Squad::getAllSquads($pdo);
            $squad_player = [];

            try{
                // If searching for squad players
                if (isset($_GET['type']))
                {
                    $squad_type = $_GET['type'];

                    // Get squad id
                    $squad = Squad::getSquadByType($pdo,$squad_type);
                    if(!$squad){
                        throw new \ErrorException('Squad Not Found!');
                    }

                    // Get squad players
                    $squad_player = Squad::listSquadPlayer($pdo, $squad['squad_id']);

                    // Go to page
                    $this->render('squad/type', [
                        'squad' => $squad,
                        'players' => $squad_player
                    ]);
                    return;
                }
                $this->render('squad/index', [
                    'squads' => $squads,
                    'squadPlayers' => $squad_player
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