<?php

include_once("../../config/Database.php");
include_once("../../class/Driver.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $connectDB = new Database("phichaia_general");
    $db = $connectDB->getConnection();

    $driver = new Driver($db);

    $result = $driver->getDriverById($_GET['id']);

    if ($result) {
        echo json_encode([
            'id' => $result['id'],
            'full_name' => $result['full_name'],
            'phone' => $result['phone'],
            'license_no' => $result['license_no'],
            'license_expiry' => $result['license_expiry']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Car not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
