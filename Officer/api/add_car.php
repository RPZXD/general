<?php
// filepath: c:\xampp\htdocs\general\Officer\api\add_car.php
include_once("../../config/Database.php");
include_once("../../class/Car.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$car = new Car($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleType = $_POST['vehicleType'];
    $licensePlate = $_POST['licensePlate'];
    $latestMileage = $_POST['latestMileage'];
    $fuelLevel = $_POST['fuelLevel'];

    // Handle image upload
    if (isset($_FILES['carImage']) && $_FILES['carImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['carImage']['tmp_name'];
        $imageName = uniqid() . '_' . $_FILES['carImage']['name'];
        $imageUploadPath = "../../uploads/cars/" . $imageName;

        if (move_uploaded_file($imageTmpPath, $imageUploadPath)) {
            $car->vehicle_type = $vehicleType;
            $car->license_plate = $licensePlate;
            $car->latest_mileage = $latestMileage;
            $car->fuel_level = $fuelLevel;
            $car->image_url = "uploads/cars/" . $imageName;

            if ($car->addCar()) {
                echo json_encode(["message" => "Car added successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to add car"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to upload image"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid image file"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>