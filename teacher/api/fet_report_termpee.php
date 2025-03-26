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
    echo json_encode(['error' => 'การเชื่อมต่อฐานข้อมูลล้มเหลว']);
    exit();
}

// Get the Teach_id, term, and pee from the request
if (isset($_GET['Teach_id']) && isset($_GET['term']) && isset($_GET['pee'])) {
    $teach_id = intval($_GET['Teach_id']);
    $term = intval($_GET['term']);
    $pee = intval($_GET['pee']);

    try {
        // Create an instance of the Subject class
        $subject = new Subject($pdo);

        // Fetch subjects based on Teach_id
        $report = $subject->getReportTermPee($teach_id, $term, $pee);

        // Check if any data is returned
        if (empty($report)) {
            // No data found
            http_response_code(200);
            echo json_encode(['message' => 'ไม่มีข้อมูล']);
        } else {
            // Data found, output as JSON
            header('Content-Type: application/json');
            echo json_encode($report);
        }

    } catch (Exception $e) {
        // Handle any errors
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Required parameters are not provided
    http_response_code(400);
    echo json_encode(['error' => 'Teach_id, term, และ pee เป็นข้อมูลที่จำเป็น']);
}
?>
