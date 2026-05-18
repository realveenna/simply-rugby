<?php

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once '../src/helpers/functions.php';

    session_start();

    $router = require '../src/Routes/index.php';

?>
