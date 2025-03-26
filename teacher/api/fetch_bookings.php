<?php

include_once("../../config/Database.php");
include_once("../../class/Utils.php");

// Initialize database connection
$connectDB = new Database_General();
$db = $connectDB->getConnection();

$teach_id = isset($_GET['teach_id']) ? $_GET['teach_id'] : die(json_encode(['success' => false, 'message' => 'Teacher ID not provided.']));

$query = "SELECT * FROM bookings WHERE teach_id = :teach_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':teach_id', $teach_id);
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
        'media' => $row['media']
    ];
}

header('Content-Type: application/json');
echo json_encode($bookings);
?>
