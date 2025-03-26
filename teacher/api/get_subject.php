<?php
// Include the database connection and Subject class file
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

// Sanitize and validate the subject number from the request
$sub_no = isset($_GET['sub_no']) ? $_GET['sub_no'] : null;

// Sanitize input to ensure it's an integer
$sub_no = filter_var($sub_no, FILTER_SANITIZE_NUMBER_INT);

// Validate that sanitized input is a valid integer
if (!filter_var($sub_no, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid subject number provided']);
    exit();
}

try {
    // Create an instance of the Subject class
    $subject = new Subject($pdo);

    // Fetch subject details by subject number
    $subjects = $subject->getSubjectsById($sub_no);

    if (!empty($subjects)) {
        // Since getSubjectsById returns an array, fetch the first element
        $subjectDetails = $subjects[0];
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($subjectDetails);
    } else {
        echo json_encode(['error' => 'Subject not found']);
    }
} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
