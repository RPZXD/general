<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include database connection
require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/Report_repair.php'; // This file contains the Report class

$response = array('success' => false, 'message' => 'ข้อผิดพลาดที่ไม่ทราบสาเหตุ');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($id)) {
        $response['message'] = 'จำเป็นต้องระบุ ID';
        echo json_encode($response);
        exit;
    }

    try {
        $database = new Database_General();
        $pdo = $database->getConnection();

        $report = new Report_repair($pdo);

        $status = $report->getStatus($id); // Assuming getStatus() returns the status of the report

        if ($status !== false) {
            if ($status == 0) {
                if ($report->deleteReport($id)) {
                    $response['success'] = true;
                    $response['message'] = 'ลบรายงานเรียบร้อยแล้ว';
                } else {
                    $response['message'] = 'ลบรายงานล้มเหลว';
                }
            } else {
                $response['message'] = 'รายงานนี้ได้ดำเนินการในกระบวนการแล้ว ไม่สามารถลบได้';
            }
        } else {
            $response['message'] = 'ไม่สามารถดึงสถานะของรายงานได้';
        }
    } catch (PDOException $e) {
        $response['message'] = 'ข้อผิดพลาดฐานข้อมูล: ' . $e->getMessage();
    }

    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'วิธีการร้องขอไม่ถูกต้อง'));
}

?>
