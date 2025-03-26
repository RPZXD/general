<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include database connection
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the Report class

$response = array('success' => false, 'message' => 'Unknown error');

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data and sanitize
    $ck_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Validate data
    if (empty($ck_id)) {
        $response['message'] = 'ID is required.';
        echo json_encode($response);
        exit;
    }

    try {
        // Create a new instance of Database_CKTeach
        $database = new Database_CKTeach();
        $pdo = $database->getConnection();

        // Create an instance of the Report class
        $report = new Subject($pdo);

        // Call the deleteReport method
        if ($report->deleteReport($ck_id)) {
            $response['success'] = true;
            $response['message'] = 'Report deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete Report.';
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
