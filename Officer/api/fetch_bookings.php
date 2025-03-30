<?php

include_once("../../config/Database.php");
include_once("../../class/Utils.php");

// Initialize database connection
$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();



$query = "SELECT * 
FROM bookings 
ORDER BY status ASC, date DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $bookings[] = [
        'id' => $row['id'],
        'date' => $row['date'],
        'name' => $row['name'],
        'location' => $row['location'],
        'start_time' => $row['time_start'],
        'end_time' => $row['time_end'],
        'purpose' => $row['purpose'],
        'status' => $row['status'],
        'media' => $row['media']
    ];
}

header('Content-Type: application/json');
echo json_encode($bookings);
?>
