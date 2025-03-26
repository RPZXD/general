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

// Get the data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sub_name']) && isset($data['sub_id']) && isset($data['sub_level']) && isset($data['sub_type']) && isset($data['sub_status']) && isset($data['Teach_id'])) {
    $sub_name = $data['sub_name'];
    $sub_id = $data['sub_id'];
    $sub_level = $data['sub_level'];
    $sub_type = $data['sub_type'];
    $sub_status = $data['sub_status'];
    $teach_id = intval($data['Teach_id']);

    try {
        // Create an instance of the Subject class
        $subject = new Subject($pdo);

        // Add the new subject
        $result = $subject->addSubject($sub_name, $sub_id, $sub_level, $sub_type, $sub_status, $teach_id);

        // Output success message
        if ($result) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Subject added successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to add subject']);
        }

    } catch (Exception $e) {
        // Handle any errors
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Missing required fields
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
}
?>
