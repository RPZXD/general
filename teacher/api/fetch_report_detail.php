<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once '../../config/Database.php';
require_once '../../class/Report_repair.php';

$response = array('success' => false, 'message' => 'ไม่พบข้อมูล');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        try {
            $database = new Database("phichaia_general");
            $pdo = $database->getConnection();

            $report = new Report_repair($pdo);
            $data = $report->getReportById($id); // สร้างเมธอดในคลาสเพื่อดึงข้อมูล

            if ($data) {
                $response['success'] = true;
                $response['report'] = $data;
            } else {
                $response['message'] = 'ไม่พบข้อมูลของ ID นี้';
            }
        } catch (Exception $e) {
            $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'ID ไม่ถูกต้อง';
    }
}

echo json_encode($response);
?>
