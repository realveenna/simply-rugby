<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\Squad;
    use Test\Models\AccessControl;
    
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
            $players = [];

            // Get authorized squad access
            $squads = AccessControl::getAuthorizedSquads($pdo, $this->member_id);

            // Forbidden Access
            if(!$squads ){
                abort(403);
            }
            
            try{
                // Higher Admin
                if(isAdmin()){
                    $title = 'All';
                }
                // Section or Membership
                elseif (count($squads) > 1){
                    $title = $squads[0]['section_name'];
                }
                // Coach
                else{
                    $title = $squads[0]['squad_name'];
                }
     
                // If searching for squad players
                if (isset($_GET['type']))
                {
                    $squad_name = $_GET['type'];

                    // Get squad details
                    $squad = Squad::getSquadByName($pdo, $squad_name);

                    // Validate Squad Access
                    $squad = AccessControl::validateSquadAccess($pdo, $squad['squad_id']);
                    
                    // Get squad players
                    $players = Squad::listSquadPlayer($pdo, $squad['squad_id']);

                    // Set title
                    $title = $squad['squad_name'];


                    // Go to page
                    $this->render('squad/name', [
                        'squad' => $squad,
                        'players' => $players,
                        'title' => $title
                    ]);
                    return;
                }
                $this->render('squad/index', [
                    'squads' => $squads,
                    'title' => $title ?? 'All'
                ]);
            }
            catch (\Exception $e){
                alert('error', $e->getMessage(), '/');
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
                alert('error', $e->getMessage(), '/');
            }
        }
    }
?>