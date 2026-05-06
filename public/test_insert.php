<?php
require '../src/Database.php';
require '../src/Models/Application.php';
use Test\Models\Application;
use Test\Database;

$pdo = Database::getInstance()->getConnection();

// fake test data
$data = [
    'fName' => 'John',
    'lName' => 'Doe',
    'dob' => '2000-01-01',
    'address_id' => 1,      
    'playerNickname' => 'JD',
    'playerHeight' => 180,
    'playerWeight' => 75,
    'email' => 'john@email.com',
    'doctor_id' => 1,     
    'mobileNum' => '07123456789'
];

try {
    $id = Application::insert($pdo, $data);
    echo "Inserted successfully. ID: " . $id;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>