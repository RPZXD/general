<?php
include_once("../../config/Database.php");
include_once("../../class/Driver.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$driver = new Driver($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driver->id = $_POST['id'];
    $driver->full_name = $_POST['FullName'];
    $driver->phone = $_POST['Phone'];
    $driver->license_no = $_POST['LicenseNo'];
    $driver->license_expiry = $_POST['LicenseExpiry'];

    if ($driver->updateDriver()) {
        echo json_encode(["message" => "Driver updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update driver"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>