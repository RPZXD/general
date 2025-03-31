<?php
include_once("../../config/Database.php");
include_once("../../class/Driver.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$driver = new Driver($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    if ($driver->deleteDriver($data->id)) {
        echo json_encode(["message" => "Driver deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete Driver"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid request"]);
}
?>