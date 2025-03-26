<?php
// Include the database connection and Report class file
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the CKTeach class

// Create an instance of Database_CKTeach
$database = new Database_CKTeach();
$pdo = $database->getConnection();

// Check if connection was successful
if ($pdo === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Sanitize and validate the Report number from the request
$report_id = isset($_GET['id']) ? $_GET['id'] : null;

// Sanitize input to ensure it's an integer
$report_id = filter_var($report_id, FILTER_SANITIZE_NUMBER_INT);

// Validate that sanitized input is a valid integer
if (!filter_var($report_id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid Report number provided']);
    exit();
}

try {
    // Create an instance of the CKTeach class (not Subject)
    $ckTeach = new Subject($pdo);

    // Fetch report details by report ID
    $report = $ckTeach->getReportById($report_id);

    if (!empty($report)) {
        // Send JSON response
        $reportDetails = $report[0];
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($reportDetails);
    } else {
        echo json_encode(['error' => 'Report not found']);
    }
} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
