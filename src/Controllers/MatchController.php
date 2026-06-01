<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\Squad;
    use Test\Models\AccessControl;
    use Test\Models\Matches;
    use Test\Models\Player;

    class MatchController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        // Render Senior Matches for index
        public function index()
        {
            $pdo = $this->pdo;

            // Get All Match Details
            $matches = Matches::getMatches($pdo, 3);           

            // Senior Matches
            $seniorUpcoming = [];
            $seniorPast = [];

            // Today's date
            $today = date('Y-m-d');

            $matchesAndHalves = [];

            foreach ($matches as $match) {
                $isSenior = $match['section_name'] === 'Senior';
                $isUpcoming = $match['match_date'] >= $today;
               
                // Get each match half
                $halves = Matches::getMatchHalfById($pdo,$match['match_id']);
                $match['halves'] = $halves;

                // Calculate total points
                $match['our_total_points'] = $halves[0]['our_total_points'] ?? 0;
                $match['opponent_total_points'] = $halves[0]['opponent_total_points'] ?? 0;

                $matchesAndHalves[] = $match;

                 // Senior matches
                if ($isSenior) {
                    if ($isUpcoming) {
                        $seniorUpcoming[] = $match;
                    } else {
                        $seniorPast[] = $match;
                    }
                }
            }

            // Update matches data with halves
            $matches = $matchesAndHalves;

            // Limit to 4 each match details
            $seniorUpcoming = array_slice($seniorUpcoming, 0, 6);
            $seniorPast = array_slice($seniorPast, 0, 6);

            // Not Found 
            if(!$matches){
               abort(404, 'No Matches Available');
            }

            $this->render('/match/index',[
                'seniorUpcoming' => $seniorUpcoming,
                'seniorPast' => $seniorPast,
            ]);
        }

         // Render all match details with permission check
        public function all()
        {
            $pdo = $this->pdo;

            // Get All Match Details
            $matches = AccessControl::getAuthorizedMatches($pdo);   
            
            // Senior Matches
            $seniorUpcoming = [];
            $seniorPast = [];

            // Junior Matches
            $juniorUpcoming = [];
            $juniorPast = [];

            // Today's date
            $today = date('Y-m-d');

            $matchesAndHalves = [];

            foreach ($matches as $match) {
                $isSenior = $match['section_name'] === 'Senior';
                $isUpcoming = $match['match_date'] >= $today;
               
                // Get each match half
                $halves = Matches::getMatchHalfById($pdo,$match['match_id']);
                $match['halves'] = $halves;

                // Calculate total points
                $match['our_total_points'] = $halves[0]['our_total_points'] ?? 0;
                $match['opponent_total_points'] = $halves[0]['opponent_total_points'] ?? 0;

                $matchesAndHalves[] = $match;

                 // Senior matches
                if ($isSenior) {
                    if ($isUpcoming) {
                        $seniorUpcoming[] = $match;
                    } else {
                        $seniorPast[] = $match;
                    }
                }
                // Junior matches and has permission
                elseif (hasPermission('view_junior_match')) {
                    if ($isUpcoming) {
                        $juniorUpcoming[] = $match;
                    } else {
                        $juniorPast[] = $match;
                    }
                }
            }

            // Update matches data with halves
            $matches = $matchesAndHalves;

            // Filter by category
            $category = $_GET['category'] ?? null;
            $displayMatches = [];

            switch($category){
                case 'Junior Upcoming':
                    $displayMatches = $juniorUpcoming;
                    $category = 'Junior Upcoming';
                    break;

                case 'Junior Past':
                    $displayMatches = $juniorPast;
                    $category = 'Junior Past';
                    break;

                case 'Senior Upcoming':
                    $displayMatches = $seniorUpcoming;
                    $category = 'Senior Upcoming';
                    break;

                case 'Senior Past':
                    $displayMatches = $seniorPast;
                    $category = 'Senior Past';
                    break;

                default:
                    $displayMatches = $matches;
                    $category = 'All';
                    break;
            }

            // Not Found 
            if(!$displayMatches){
               abort(404, 'No Matches Available');
            }

            $this->render('/match/all',[
                'matches' => $displayMatches,
                'category' => $category,
            ]);
        }

        //  Render view for single match details
        public function view()
        {
            $pdo = $this->pdo;
            $lineup = [];
            $coaches = [];

            try{
                // Pemission check to get match details
                $match = $this->getMatchId($pdo);

                // Get lineup
                $lineup = Matches::getLineup($pdo, $match['match_id']);

                // If no existing lineup
                if(!$lineup){
                    // Create lineup from squad players
                    $insert = Matches::createLineupFromSquad($pdo, $match['match_id'], $match['squad_id']);
                    if(!$insert){
                        throw new \ErrorException('Failed to insert to player lineup');
                    }
                }
                
                // Get lineup
                $lineup = Matches::getLineup($pdo, $match['match_id']);

                // Get Coaches
                $coaches = Squad::getSquadCoaches($pdo, $match['squad_id']);

                // Match status title
                $title = $match['result'] === 'Pending' ? 'Upcoming Match' : 'Past Match';

                // Restrict permission for junior matches
                if ($match['section_name'] === 'Junior' && !hasPermission('view_junior_match')) {
                    abort(403);
                }
                // Get each match half
                $halves = Matches::getMatchHalfById($pdo,$match['match_id']);
                $match['halves'] = $halves;

                // Calculate total points
                $match['our_total_points'] = $halves[0]['our_total_points'] ?? 0;
                $match['opponent_total_points'] = $halves[0]['opponent_total_points'] ?? 0;


                 // Check if any lineup position is null
                foreach ($lineup as $player) {
                    if (empty($player['position'])) {
                        $message = "Attention! Please update player's position for upcoming match";
                        break;
                    }
                }

                if ((hasPermission('create_team'))
                    && $match['result'] === 'Pending' && !isFutureDate($match['match_date'])){
                    $message = "Attention! Please update player's match stats";
                }

                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Get POST action
                    $action = trimPost('action') ?? '';

                    // Override match objext
                    $match = $this->getMatchId($pdo);

                    if ($action === 'delete') {
                        // Begin Transaction
                        $pdo->beginTransaction();

                        // delete Match
                        $deleted = Matches::delete($pdo, $match['match_id']);

                        // If not deleted
                        if(!$deleted){
                            throw new \ErrorException('Unable to delete match');
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success', 'Match Details Deleted Successfully!', '/match/all');
                    }
                    else{
                        abort(404, "Undefined Action");
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/match/view');
            }
            // Render
            $this->render('match/view', [
                'title' => $title,
                'match' => $match,
                'lineup' => $lineup,
                'coaches' => $coaches,
                'message' => $message ?? '',
                'halves' => $halves ?? '',
                
            ]);
        }

        // Render create match with permission check
        public function create()
        {
            // Set PDO Connection
            $pdo = $this->pdo;

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
                        'result' => 'Pending'
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

                        // Insert each squach players to match_lineup
                        $insertLineup =  Matches::createLineupFromSquad($pdo, $M->match_id, $M->squad_id);
                        if(!$insertLineup){
                            throw new \ErrorException('Failed to insert to player lineup');
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
            }

            // Render
            $this->render('match/create', [
                'squads' => $squads ?? '',
                'match' => $M ?? '',
                'error' => $error
            ]);
        }

        // Render lineup view with permission check
        public function lineup()
        {
            $pdo = $this->pdo;

            $lineup = [];
            $errors = [];
            $positions = self::listAllPositions();

            try{
                $match = new Matches($this->getMatchId($pdo));
                $lineup = Matches::getLineup($pdo,$match->match_id);
                
                // Validate Squad Access Control
                AccessControl::validateSquadAccess($pdo, $match->squad_id);
               
                // If no existing lineup
                if(!$lineup){
                    // Create lineup from squad players
                    $insert = Matches::createLineupFromSquad($pdo, $match->match_id, $match->squad_id);
                    if(!$insert){
                        throw new \ErrorException('Failed to insert to player lineup');
                    }
                }

                // Get lineup
                $lineup = Matches::getLineup($pdo, $match->match_id);

                // Abort if match already has result 
                if($match->result !== 'Pending'){
                    abort(403);
                }

                // Check if any lineup position is null
                foreach ($lineup as $player) {
                    if (empty($player['position'])) {
                        $message = "Attention! Please update player's position for upcoming match";
                        break;

                    }
                }

                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    
                    // Validate Match Id
                    $match = new Matches($this->getMatchId($pdo));

                    // Check for if match already has result
                    if($match->result !== 'Pending'){
                        abort(403, 'Unable to modify lineup. This match already has a result.');
                    }

                    // filter empty value from POST
                    $selectedPlayers = array_filter($_POST);

                    // Unset match id from filtering post players
                    unset($selectedPlayers['match_id']);

                    // Filtered array of selected players
                    $selectedPlayers = array_filter($selectedPlayers);

                    // Check if any position select is empty
                    foreach ($positions as $key => $position) {
                        if (empty($_POST[$key])) {
                            $errors[$key] = 'Please select a player.';
                        }
                    }

                    // Check for duplicate postion for a player
                    if (count($selectedPlayers) !== count(array_unique($selectedPlayers))) {
                        $errors['duplicate'] =  'A player cannot be selected multiple times.';
                    }

                    if (empty($errors)){
                        // Begin Transaction
                        $pdo->beginTransaction();

                        // Insert each selected player with position
                        foreach ($_POST as $key => $player_id) {
                            // Skip match_id
                            if ($key === 'match_id') {
                                continue;
                            }

                            // Convert form key to match db ENUM value
                            $position = $positions[$key];

                            // Update player position
                            $inserted = Matches::updatePlayerPosition
                                ($pdo, $match->match_id, $player_id, $position);

                            // Failed to insert
                            if (!$inserted) {
                                throw new \Exception('Unable to update player positions.');
                            }
                        }

                        // Insert unselected player with position as substitute
                        foreach ($lineup as $player) {

                            // If player was not selected
                            if (!in_array($player['player_id'], $selectedPlayers)) {
                                $inserted = Matches::updatePlayerPosition
                                    ($pdo,$match->match_id,$player['player_id'],'Substitute');
                                
                                // Failed to insert
                                if (!$inserted) {
                                    throw new \Exception('Unable to update player positions.');
                                }
                            }
                        }
                        // Commmit and success message
                        $pdo->commit();
                        alert('success','Player Position for Match Lineup Successfully Updated!','/match');
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/match/lineup?match_id='. $match->match_id);
                die();
            }
            // Render
            $this->render('match/lineup', [
                'message' => $message ?? '',
                'match' => $match,
                'lineup' => $lineup,
                'errors' => $errors,
                'positions' => $positions
            ]);
        }

        // Render matchPlayerStats view with permission check
        public function matchPlayerStats()
        {
            $pdo = $this->pdo;

            $lineup = [];
            $errors = [];
            $positions = self::listAllPositions();

            try{
                $match = $this->getMatchId($pdo);
                $lineup = Matches::getLineup($pdo,$match['match_id']);

                // Validate Squad Access Control
                AccessControl::validateSquadAccess($pdo, $match['squad_id']);

                // If no existing lineup
                if(!$lineup){
                    abort(500, 'Unable to update match player stats. There is no exisiting lineup in this match.');
                }

                // Abort if match result is pending
                if($match['result'] === 'Pending'){
                    abort(403);
                        abort(403, 'Unable to modify lineup. This match has no exising result yet.');
                }

                // Check if any lineup position is null
                foreach ($lineup as $player) {
                    if (empty($player['position'])) {
                        abort(500, 'Unable to update match player stats. There is a player without lineup in this match.');
                        break;
                    }
                }

                // Get each match half
                $halves = Matches::getMatchHalfById($pdo,$match['match_id']);
                $match['halves'] = $halves;

                // Calculate total points
                $match['our_total_points'] = $halves[0]['our_total_points'] ?? 0;
                $match['opponent_total_points'] = $halves[0]['opponent_total_points'] ?? 0;
                
                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Validate Match Id
                    $match = $this->getMatchId($pdo);

                    // Check for if match already has result
                    if($match['result'] === 'Pending'){
                        abort(403, 'Unable to modify lineup. This match has no exisint result yet.');
                    }

                    // Chech POST value
                    $stats = $_POST['stats'];
                    foreach ($stats as $player_id => $playerStats) {
                        $minutesPlayed = (int)$playerStats['minutes_played'] ?? 0;
                        $tries = (int)$playerStats['tries'] ?? 0;
                        $conversions = (int)$playerStats['conversions'] ?? 0;
                        $penalties = (int)$playerStats['penalties'] ?? 0;
                        $dropGoals = (int)$playerStats['drop_goals'] ?? 0;
                        $yellowCards = (int)$playerStats['yellow_cards'] ?? 0;
                        $redCards = (int)$playerStats['red_cards'] ?? 0;
                    }

                    $hasError = false;

                    // Valid input, Check for no input then if value is valid
                    foreach ($stats as $player_id => $playerStats) {
                        foreach ($playerStats as $input => $value) {
                            // Empty input
                            if ($value === '') {
                                $errors['message'] = 'Please fill in all fields';
                                $hasError = true;
                                break;
                            }

                            // Invalid points or mins
                            if (!is_numeric($value) || (int)$value < 0) {
                                $errors['message'] = 'Please enter positive number';
                                $hasError = true;
                                break;
                            }
                        }
                    }

                    // Get each match half
                    $halves = Matches::getMatchHalfById($pdo, $match['match_id']);
                    $match['halves'] = $halves;

                    // Get Sum of total points
                    $match['our_total_points'] = $halves[0]['our_total_points'] ?? 0;
                    $match['opponent_total_points'] = $halves[0]['opponent_total_points'] ?? 0;
                    $officialTotal =(int) $match['our_total_points'];
                    $total = 0;

                    // If no error from previous validation check if total are equal to official result
                    if(!$hasError){
                        // Calculate Total points by each player stats
                        foreach ($stats as $playerStats) {
                            $total +=
                                ((int)$playerStats['tries'] * 5) +
                                ((int)$playerStats['conversions'] * 2) +
                                ((int)$playerStats['penalties'] * 3) +
                                ((int)$playerStats['drop_goals'] * 3);
                        }

                        // Check if both points are equal
                        if ((int)$total !== (int)$officialTotal){
                            $errors['message'] = "Player stats total does not match official match score. Please try again";
                        }
                    }

                    // No errors
                    if (empty($errors)){
                        // Begin Transaction
                        $pdo->beginTransaction();

                        // Insert each to player_match_stats
                        foreach ($stats as $player_id => $playerStats) {
                            $inserted = Player::insertPlayerMatchStats($pdo, 
                                $match['match_id'], $player_id, $playerStats);
                            if(!$inserted){
                                throw new \ErrorException('Failed to insert player stats.');
                            }
                        }

                        // Commit
                        $pdo->commit();
                        alert('success','Player Match Player Stats Succesfully Updated!','/match');
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/match/match-player-stats?match_id='. $match['match_id']);
                die();
            }
            // Render
            $this->render('match/match-player-stats', [
                'message' => $message ?? '',
                'match' => $match,
                'lineup' => $lineup,
                'errors' => $errors,
                'positions' => $positions
            ]);
        }

        // Render updateMatch view with permission check
        public function updateMatch()
        {
            // Set PDO Connection
            $pdo = $this->pdo;

            try{
                // Set error array
                $error = [];

                // Get match details as object with permission check
                $match = new Matches($this->getMatchId($pdo));

                // Validate Squad Access Control
                AccessControl::validateSquadAccess($pdo, $match->squad_id);


                // POST REQUEST
                if($_SERVER['REQUEST_METHOD'] === 'POST') {

                    // Create Matches object, override the original
                    $match = new Matches([
                        'match_id' => $match->match_id,
                        'squad_id' => trimPost('squad_id'),
                        'match_venue' => trimPost('match_venue'),
                        'match_date' => trimPost('match_date'),
                        'opposition_team_name' => trimPost('opposition_team_name'),
                        'kick_off_time' => trimPost('kick_off_time'),
                        'result' => trimPost('result')
                    ]);

                    // Validate Squad Access Control
                    AccessControl::validateSquadAccess($pdo, $match->squad_id);
                 
                    // Validate inputs
                    if (empty($match->match_venue)) {
                        $error['match_venue'] = "Please select match venue.";
                    }

                    if (empty($match->match_date)) {
                        $error['match_date'] = "Please enter match date.";
                    }
                    else{
                        $match->match_date = formatDate($match->match_date);
                    }

                    if (empty($match->opposition_team_name)) {
                        $error['opposition_team_name'] = "Please enter opposition team name.";
                    }

                    if (empty($match->kick_off_time)) {
                        $error['kick_off_time'] = "Please enter kick off time.";
                    }

                    // No validation errors add to database
                    if (empty($error)) {
                        // Begin Transaction
                        $pdo->beginTransaction();

                        AccessControl::validateSquadAccess($pdo, $match->squad_id);
                        
                        // Get all squad players
                        $players = Squad::getSquadPlayers($pdo, $match->squad_id);

                        if(!$players){
                            throw new \ErrorException('There is no players in this squad.');
                        }
                        
                        // Check if Squad has enough playing member
                        if (count($players) < 15) {
                            throw new \ErrorException('Squad must have at least 15 players to participate in a match.');
                        }

                        // Update match details
                        $updated = $match->update($pdo);

                        if(!$updated){
                            throw new \ErrorException('Failed to update match details');
                        }

                        // Commmit and success message
                        $pdo->commit();
                        alert('success','Match Updated Successfully.','/match');
                    }
                }
            }
            // Catch error
            catch (\Exception $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert('error', $e->getMessage(), '/match/update');
            }

            // Render
            $this->render('match/update', [
                'squads' => $squads ?? '',
                'match' => $match,
                'error' => $error
            ]);
        }

        // Update match result
        public function updateResult()
        {
            $pdo = $this->pdo;
            $error = [];

            // Get match details
            $match = $this->getMatchId($pdo);


            // Abort if match already has result 
            if($match['result'] !== 'Pending'){
                alert('error', 'Unable to modify result of a past match', '/match');
                die();
            }

            // Get each match half
            $halves = Matches::getMatchHalfById($pdo,$match['match_id']);
            $match['halves'] = $halves;

            // Calculate total points
            $match['our_total_points'] = $halves[0]['our_total_points'] ?? 0;
            $match['opponent_total_points'] = $halves[0]['opponent_total_points'] ?? 0;


            try {
                // POST request
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Begin transaction
                    $pdo->beginTransaction();

                    // Form data
                    $data = [
                        // First Half
                        'fh_our_points' => trimPost('fh_our_points'),
                        'fh_opponent_points' => trimPost('fh_opponent_points'),
                        'fh_our_comments' => trimPost('fh_our_comments'),
                        'fh_opponent_comments' => trimPost('fh_opponent_comments'),

                        // Second Half
                        'sh_our_points' => trimPost('sh_our_points'),
                        'sh_opponent_points' => trimPost('sh_opponent_points'),
                        'sh_our_comments' => trimPost('sh_our_comments'),
                        'sh_opponent_comments' => trimPost('sh_opponent_comments')
                    ];

                    // Validate empty input
                    $error['fh_our_points'] = ifEmpty
                        ($data['fh_our_points'], 'Please enter our team points');
                    $error['fh_opponent_points'] = ifEmpty
                        ($data['fh_opponent_points'], 'Please enter opponent team points');
                    $error['fh_our_comments'] = ifEmpty
                        ($data['fh_our_comments'], 'Please enter our team comments');
                    $error['fh_opponent_comments'] = ifEmpty
                        ($data['fh_opponent_comments'], 'Please enter opponent comments');
                    $error['sh_our_points'] = ifEmpty
                        ($data['sh_our_points'], 'Please enter our team points');
                    $error['sh_opponent_points'] = ifEmpty
                        ($data['sh_opponent_points'], 'Please enter opponent team points');
                    $error['sh_our_comments'] = ifEmpty
                        ($data['sh_our_comments'], 'Please enter our team comments');
                    $error['sh_opponent_comments'] = ifEmpty
                        ($data['sh_opponent_comments'], 'Please enter opponent comments');

                    // Please enter a valid points
                    if ($data['fh_our_points'] !== '' && $data['fh_our_points'] < 0) {
                        $error['fh_our_points'] = 'Please enter a valid points.';
                    }

                    if ($data['fh_opponent_points'] !== '' && $data['fh_opponent_points'] < 0) {
                        $error['fh_opponent_points'] = 'Please enter a valid points.';
                    }

                    if ($data['sh_our_points'] !== '' && $data['sh_our_points'] < 0) {
                        $error['sh_our_points'] = 'Please enter a valid points.';
                    }

                    if ($data['sh_opponent_points'] !== '' && $data['sh_opponent_points'] < 0) {
                        $error['sh_opponent_points'] = 'Please enter a valid points.';
                    }

                    // Remove empty validation errors
                    $error = array_filter($error);

                    // No validation errors
                    if (empty($error)) {
                        // First Half in array
                        $firstHalf = [
                            'half_type' => 'First Half',
                            'our_points' => $data['fh_our_points'],
                            'opponent_points' => $data['fh_opponent_points'],
                            'our_comments' => $data['fh_our_comments'],
                            'opponent_comments' => $data['fh_opponent_comments']
                        ];

                        // Second Half in array
                        $secondHalf = [
                            'half_type' => 'Second Half',
                            'our_points' => $data['sh_our_points'],
                            'opponent_points' => $data['sh_opponent_points'],
                            'our_comments' => $data['sh_our_comments'],
                            'opponent_comments' => $data['sh_opponent_comments']
                        ];
                        
                        // Save both halves
                        Matches::saveMatchHalf($pdo,$match['match_id'],$firstHalf);
                        Matches::saveMatchHalf($pdo,$match['match_id'],$secondHalf);

                        // Calculate totals
                        $ourTotal = $data['fh_our_points'] + $data['sh_our_points'];
                        $opponentTotal = $data['fh_opponent_points'] + $data['sh_opponent_points'];

                        // Determine match result
                        if ($ourTotal > $opponentTotal) {
                            $result = 'Win';
                        }
                        elseif ($ourTotal < $opponentTotal) {
                            $result = 'Lose';
                        }
                        else {
                            $result = 'Draw';
                        }

                        // Update match result
                        $updated =  Matches::updateMatchResult($pdo,$result, $match['match_id']);
                        if (!$updated) {
                            throw new \ErrorException('Failed to update match result' );
                        }

                        // Commit
                        $pdo->commit();

                        // Alert message
                        alert(
                            'success', 'Match result updated successfully!',
                            '/match/view?match_id=' . $match['match_id']
                        );
                    }
                }
            }
            catch (\Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                alert(
                    'error', $e->getMessage(), '/match/view?match_id='. $match['match_id']
                );
            }

            $this->render('match/update-result', [
                'match' => $match,
                'halves' => $halves,
                'error' => $error,
                'data' => $data ?? []
            ]);
        }

        // Private method if match_id is mandatory
        // view and update
        private function getMatchId($pdo){
            // IF match_id is requested by GET or POST
            if(isset($_GET['match_id']) || isset($_POST['match_id'])){

                // Set match_id
                $match_id = $_GET['match_id'] ?? ($_POST['match_id']);

                // Get Match Details 
                $match = AccessControl::validateMatchAccess($pdo, $match_id);

                // Return as match Object
                return $match;
            }
            else{
                alert("error","Please select a match", '/match/all');
            }
        }

        // Returns an array of positions
        public static function listAllPositions(){
            return [
                'Loosehead_Prop' => 'Loosehead Prop',
                'Hooker' => 'Hooker',
                'Tighthead_Prop' => 'Tighthead Prop',
                'Lock_1' => 'Lock',
                'Lock_2' => 'Lock',
                'Blindside_Flanker' => 'Blindside Flanker',
                'Openside_Flanker' => 'Openside Flanker',
                'Number_8' => 'Number 8',
                'Scrum_half' => 'Scrum-half',
                'Fly_half' => 'Fly-half',
                'Left_Wing' => 'Left Wing',
                'Inside_Centre' => 'Inside Centre',
                'Outside_Centre' => 'Outside Centre',
                'Right_Wing' => 'Right Wing',
                'Fullback' => 'Fullback',
            ];
        }
    }
?>