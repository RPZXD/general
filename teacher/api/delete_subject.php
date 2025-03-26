<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include database connection
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the Subject class

$response = array('success' => false, 'message' => 'Unknown error');

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data and sanitize
    $sub_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Validate data
    if (empty($sub_no)) {
        $response['message'] = 'ID is required.';
        echo json_encode($response);
        exit;
    }

    try {
        // Create a new instance of Database_CKTeach
        $database = new Database_CKTeach();
        $pdo = $database->getConnection();

        // Create an instance of the Subject class
        $subject = new Subject($pdo);

        // Call the deleteSubject method
        if ($subject->deleteSubject($sub_no)) {
            $response['success'] = true;
            $response['message'] = 'Subject deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete subject.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

    // Output JSON response
    echo json_encode($response);
} else {
    // Handle non-POST requests
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>
