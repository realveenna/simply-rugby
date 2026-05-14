<?php
    session_start();

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once '../src/helpers/functions.php';

    $router = require '../src/Routes/index.php';

?>
