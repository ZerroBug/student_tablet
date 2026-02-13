<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=u421860731_tablet_db;charset=utf8mb4",
        "u421860731_oashs",
        "Ananeeric@172090"
    );
    echo "SUCCESS";
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>