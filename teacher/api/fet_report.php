<?php
// Include the database configuration and Subject class file
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the Subject class

// Create an instance of Database_CKTeach
$database = new Database_CKTeach();
$pdo = $database->getConnection();

// Check if connection was successful
if ($pdo === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Get the Teach_id from the request
if (isset($_GET['Teach_id'])) {
    $teach_id = intval($_GET['Teach_id']);

    try {
        // Create an instance of the Subject class
        $subject = new Subject($pdo);

        // Fetch subjects based on Teach_id
        $report = $subject->getReportByTeachId($teach_id);

        // Output data as JSON
        header('Content-Type: application/json');
        echo json_encode($report);

    } catch (Exception $e) {
        // Handle any errors
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Teach_id is not provided
    http_response_code(400);
    echo json_encode(['error' => 'Teach_id is required']);
}
?>
