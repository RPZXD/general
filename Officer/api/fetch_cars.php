
<?php
header('Content-Type: application/json'); // Ensure the response is JSON
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 0); // Suppress errors in the output

include_once("../../config/Database.php");
include_once("../../class/Car.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$car = new Car($db);

$data = $car->fetchVehicles(); // Fetch all cars from the database

if ($data) {
    echo json_encode($data); // Return JSON response
} else {
    echo json_encode([]); // Return an empty array if no data
}
?>