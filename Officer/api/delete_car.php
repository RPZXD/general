<?php
include_once("../../config/Database.php");
include_once("../../class/Car.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$car = new Car($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    if ($car->deleteCar($data->id)) {
        echo json_encode(["message" => "Car deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete car"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid request"]);
}
?>