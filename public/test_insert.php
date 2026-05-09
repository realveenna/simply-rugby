<?php
require '../src/Database.php';
require '../src/Models/Application.php';

use Test\Database;

$pdo = Database::getInstance()->getConnection();

// fake test data
$data = [
    'id' => 216,
    'table' => 'application_allergy',
    'id_name' => 'application_id'
];

$pdo = Database::getInstance()->getConnection();

$statement = $pdo->prepare(
    "SELECT 
    -- Table
        player_application.*, 
        address.*,

    -- Primary Guardian Alias
        primary_guardian.first_name AS primary_fname,
        primary_guardian.last_name AS primary_lname,
        primary_guardian.mobile_number AS primary_mobile,

    -- Secondary Guardian Alias
        secondary_guardian.first_name AS secondary_fname,
        secondary_guardian.last_name AS secondary_lname,
        secondary_guardian.mobile_number AS secondary_mobile

    FROM player_application

    -- Joins
    JOIN address 
            ON player_application.address_id = address.address_id
        LEFT JOIN application_guardian AS primary_guardian
            ON player_application.primary_guardian_id = primary_guardian.guardian_id
        LEFT JOIN application_guardian AS secondary_guardian
            ON player_application.secondary_guardian_id = secondary_guardian.guardian_id
    "
);

$statement->execute();
$allApplications = $statement->fetchAll(PDO::FETCH_ASSOC);
var_dump($allApplications);


?>