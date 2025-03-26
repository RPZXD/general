<?php
// Include database connection and class
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the Subject class

header('Content-Type: application/json');

$database = new Database_CKTeach();
$pdo = $database->getConnection();
// Retrieve and filter POST data
$sub_no = isset($_POST['editsub_no']) ? filter_var(trim($_POST['editsub_no']), FILTER_SANITIZE_STRING) : null;
$sub_name = isset($_POST['editsub_name']) ? filter_var(trim($_POST['editsub_name']), FILTER_SANITIZE_STRING) : null;
$sub_id = isset($_POST['editsub_id']) ? filter_var(trim($_POST['editsub_id']), FILTER_SANITIZE_STRING) : null;
$sub_level = isset($_POST['editsub_level']) ? filter_var(trim($_POST['editsub_level']), FILTER_SANITIZE_NUMBER_INT) : null;
$tsub_id = isset($_POST['editsub_type']) ? filter_var(trim($_POST['editsub_type']), FILTER_SANITIZE_NUMBER_INT) : null;
$sub_status = isset($_POST['editsub_status']) ? filter_var(trim($_POST['editsub_status']), FILTER_SANITIZE_NUMBER_INT) : null;

// Validate input
if ($sub_no === null || $sub_name === null | $sub_id === null || $sub_level === null || $tsub_id === null || $sub_status === null ) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

// Additional validation for numbers
if (!filter_var($tsub_id, FILTER_VALIDATE_INT) || !filter_var($sub_status, FILTER_VALIDATE_INT) || !filter_var($sub_level, FILTER_VALIDATE_INT)) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
    exit;
}

try {
    // Create an instance of the CKTeach class
    $subject = new Subject($pdo);
    $result = $subject->updateSubject($sub_no, $tsub_id, $sub_name, $sub_id, $sub_status, $sub_level);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'ข้อมูลรายวิชาได้รับการอัปเดตเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลที่ต้องการอัปเดต']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage()]);
}

error_log(print_r($_POST, true)); // Log the POST data for debugging


?>
