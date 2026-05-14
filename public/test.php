<?php

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=simply_rugby;charset=utf8mb4",
        "root",
        ""
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    die("DB CONNECTED");

} catch (Exception $e) {
    die($e->getMessage());
}

?>