<?php
include_once("../../config/Database.php");
include_once("../../class/Car.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$car = new Car($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car->id = $_POST['id'];
    $car->vehicle_type = $_POST['vehicleType'];
    $car->license_plate = $_POST['licensePlate'];
    $car->latest_mileage = $_POST['latestMileage'];
    $car->fuel_level = $_POST['fuelLevel'];

    if (isset($_FILES['carImage']) && $_FILES['carImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['carImage']['tmp_name'];
        $imageName = uniqid() . '_' . $_FILES['carImage']['name'];
        $imageUploadPath = "../../uploads/cars/" . $imageName;

        if (move_uploaded_file($imageTmpPath, $imageUploadPath)) {
            $car->image_url = "uploads/cars/" . $imageName;
        }
    }

    if ($car->updateCar()) {
        echo json_encode(["message" => "Car updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update car"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>