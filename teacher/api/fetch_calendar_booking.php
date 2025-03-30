<?php
require_once '../../config/Database.php';
require_once '../../class/Booking.php';
require_once '../../class/UserLogin.php';

// Create a new database connection instance
$database = new Database("phichaia_general");
$conn = $database->getConnection();

$database_user = new Database("phichaia_student");
$db = $database_user->getConnection();

// Initialize Booking class
$booking = new Booking($conn);

// Initialize UserLogin class
$user = new UserLogin($db);

// Fetch all bookings
$location = isset($_GET['location']) ? $_GET['location'] : '';
$query = "SELECT * FROM bookings";
if ($location) {
    $query .= " WHERE location = :location";
}
$stmt = $conn->prepare($query);
if ($location) {
    $stmt->bindParam(':location', $location);
}
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for FullCalendar
$events = [];
foreach ($bookings as $booking) {
    // Fetch user data
    $userData = $user->userData($booking['teach_id']);
    $events[] = [
        'title' => $booking['location'],
        'start' => $booking['date'] . 'T' . $booking['time_start'],
        'end' => $booking['date'] . 'T' . $booking['time_end'],
        'extendedProps' => [
            'status' => $booking['status'],
            'location' => $booking['location'],
            'purpose' => $booking['purpose'],
            'name' => $userData['Teach_name'], // Use teacher's name
            'media' => $booking['media'],
            'teach_id' => $booking['teach_id'],
            'phone' => $booking['phone']
        ]
    ];
}

// Return data as JSON
echo json_encode($events);
?>
