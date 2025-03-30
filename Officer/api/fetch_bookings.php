<?php

require_once '../../config/Database.php';
require_once '../../class/Booking.php';
require_once '../../class/UserLogin.php';

// Initialize the database connections
$databaseUser = new Database("phichaia_student");
$dbUser = $databaseUser->getConnection();

$databaseGeneral = new Database("phichaia_general");
$dbGeneral = $databaseGeneral->getConnection();

// Initialize Booking class
$booking = new Booking($dbGeneral);

// Initialize UserLogin class
$user = new UserLogin($dbUser);


$query = "SELECT * 
FROM bookings 
ORDER BY status ASC, date DESC";
$stmt = $dbGeneral->prepare($query);
$stmt->execute();

$bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $userData = $user->userData($row['teach_id'] ?? null); // Fetch user data using teach_id
    $bookings[] = [
        'id' => $row['id'] ?? null,
        'date' => $row['date'] ?? null,
        'name' => $userData['Teach_name'] ?? '', // Default to empty string if not set
        'tel' => $row['phone'] ?? '', // Default to empty string if not set
        'location' => $row['location'] ?? null,
        'start_time' => $row['time_start'] ?? null,
        'end_time' => $row['time_end'] ?? null,
        'purpose' => $row['purpose'] ?? null,
        'status' => $row['status'] ?? null,
        'media' => $row['media'] ?? null
    ];
    
}

header('Content-Type: application/json');
echo json_encode($bookings);
?>
