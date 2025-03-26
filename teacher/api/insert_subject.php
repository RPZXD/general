<?php
// Insert the subject into the database
header('Content-Type: application/json');

// Include database connection and Subject class
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the Subject class

$response = array('success' => false, 'message' => 'Unknown error');

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data and sanitize
    $sub_name = filter_input(INPUT_POST, 'sub_name', FILTER_SANITIZE_STRING);
    $sub_id = filter_input(INPUT_POST, 'sub_id', FILTER_SANITIZE_STRING);
    $sub_level = filter_input(INPUT_POST, 'sub_level', FILTER_SANITIZE_NUMBER_INT);
    $sub_type = filter_input(INPUT_POST, 'sub_type', FILTER_SANITIZE_STRING); // Assuming sub_type is a string or number
    $sub_status = filter_input(INPUT_POST, 'sub_status', FILTER_SANITIZE_NUMBER_INT);
    $teach_id = filter_input(INPUT_POST, 'teach_id', FILTER_SANITIZE_NUMBER_INT);
    $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING); // Assuming department is a string or number

    // Validate data
    if (empty($sub_name) || empty($sub_id) || empty($sub_level) || empty($sub_type) || !isset($sub_status) || empty($teach_id) || empty($department)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }
    

    try {
        // Create a new instance of Database_CKTeach
        $database = new Database_CKTeach();
        $pdo = $database->getConnection();

        // Create a new instance of Subject
        $subject = new Subject($pdo);

        // Insert the subject using the insertSubject method
        if ($subject->insertSubject($sub_name, $sub_id, $sub_level, $sub_type, $sub_status, $teach_id, $department)) {
            $response['success'] = true;
            $response['message'] = 'Subject added successfully.';
        } else {
            $response['message'] = 'Failed to add subject.';
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
