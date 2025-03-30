<?php

include_once("../../config/Database.php");
include_once("../../class/Car.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $connectDB = new Database("phichaia_general");
    $db = $connectDB->getConnection();

    $car = new Car($db);

    $result = $car->getCarById($_GET['id']);

    if ($result) {
        echo json_encode([
            'id' => $result['id'],
            'vehicle_type' => $result['vehicle_type'],
            'license_plate' => $result['license_plate'],
            'latest_mileage' => $result['latest_mileage'],
            'fuel_level' => $result['fuel_level'],
            'image_url' => $result['image_url'], // Added image_url
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Car not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
