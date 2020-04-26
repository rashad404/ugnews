<?php

//define('COMPANY_NAME', 'AVEYRON INC.');

$connectDb = mysqli_connect(
    "localhost",
    "nmetbuat15_root",
    "Bz1iFm86Vx",
    "nmetbuat15_metbuat");

if (!$connectDb) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysqli_set_charset($connectDb, "utf8");


?>