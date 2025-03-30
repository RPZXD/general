<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include database connection
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/Booking.php'; // This file contains the booking class

$response = array('success' => false, 'message' => 'Unknown error');

// Check if DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the input data
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT) : null;

    // Validate data
    if (empty($id) || $id <= 0) {
        $response['message'] = 'ID is required and must be valid.';
        echo json_encode($response);
        exit;
    }

    try {
        // Create a new instance of Database_General
        $database = new Database("phichaia_general");
        $pdo = $database->getConnection();

        // Create an instance of the booking class
        $booking = new Booking($pdo);

        // Check if the booking exists and its status is 0
        $stmt = $pdo->prepare("SELECT status FROM bookings WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $bookingData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookingData) {
            // If the status is 0, allow deletion
            if ($bookingData['status'] == 0) {
                // Call the delete method
                if ($booking->delete($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Booking deleted successfully.';
                } else {
                    $response['message'] = 'Failed to delete booking.';
                }
            } else {
                // If status is not 0, return an error message
                $response['message'] = 'Booking cannot be deleted because its status is not 0.';
            }
        } else {
            $response['message'] = 'Booking not found.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

    // Output JSON response
    echo json_encode($response);
} else {
    // Handle non-DELETE requests
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
