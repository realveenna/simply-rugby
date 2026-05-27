<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Member;
    use Test\Models\Role;
    use Test\Models\Squad;
    use Test\Models\AccessControl;
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
            $pdo = $this->pdo;

            $squads = AccessControl::getAuthorizedSquads($pdo, $this->member_id);
            
            $players = [];

            // Forbidden Access
            if(!$squads ){
                abort(403);
            }
            
            try{
                // If searching for squad players
                if (isset($_GET['name']))
                {
                    $squad_name = $_GET['name'];

                    // Get squad details
                    $squad = Squad::getSquadByName($pdo, $squad_name);

                    // Validate Squad Access
                    $squad = AccessControl::validateSquadAccess($pdo, $squad['squad_id']);
                    
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

        // Display Senior squad for public
        public function senior()
        {
            $pdo = $this->pdo;

            try{
                // Get senior squad 
                $squad = Squad::getSquadByName($pdo, 'Senior');
                
                if(!$squad){
                    abort(404, 'No Squad Found');
                }

                $players = Squad::listSquadPlayer($pdo, $squad['squad_id']);
                $coaches = Squad::getSquadCoaches($pdo, $squad['squad_id']);

                $this->render('squad/senior', [
                    'squad' => $squad,
                    'players' => $players,
                    'coaches' => $coaches,
                ]);
            }
            catch (\Exception $e){
                alert('errors', $e->getMessage(), '/');
            }
        }
    }
?>