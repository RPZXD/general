<?php

include_once("../../config/Database.php");

// Initialize database connection
$connectDB = new Database_General();
$db = $connectDB->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->status)) {
    $id = $data->id;
    $status = $data->status;

    $query = "UPDATE bookings SET status = :status WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>
