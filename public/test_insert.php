<?php
require '../src/Database.php';
require '../src/Models/Application.php';
?>

<?php
require '../vendor/autoload.php';

use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

use Test\Database;

$squad_id = 3;
$member_id = 200;

listSquadPlayer($squad_id);

function listSquadPlayer($squad_id,){
$pdo = Database::getInstance()->getConnection();

    $statement = $pdo->prepare
    (
        "SELECT
            m.member_id,
            m.first_name,
            m.last_name,
            s.squad_name,
            pp.*
        FROM squad_player_history h
        JOIN member m ON h.member_id = m.member_id
        JOIN squad s ON h.squad_id = s.squad_id
        JOIN player_profile pp  ON m.member_id = pp.member_id
        WHERE h.squad_id = :squad_id AND h.end_date IS NULL"
    );

    $statement->execute(['squad_id' => $squad_id]);
    $squadPlayer =  $statement->fetchAll(PDO::FETCH_ASSOC);

    var_dump($squadPlayer);
    exit;
    return;
}
?>

