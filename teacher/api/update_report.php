<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/Database.php';
require_once '../../class/CKTeach.php';

$response = array('success' => false, 'message' => 'Unknown error');

function sanitizeInput($input, $type) {
    switch ($type) {
        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT);
        case 'string':
            return filter_var($input, FILTER_SANITIZE_STRING);
        default:
            return null;
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $editReportid = sanitizeInput($_POST['editReportid'], 'string');
        $ck_date = sanitizeInput($_POST['Editck_date'], 'string');
        $sub_no = sanitizeInput($_POST['Editsub_no'], 'int');
        $ck_level = sanitizeInput($_POST['Editck_level'], 'int');
        $ck_room = sanitizeInput($_POST['Editck_room'], 'int');
        $ck_pstart = sanitizeInput($_POST['Editck_pstart'], 'int');
        $ck_pend = sanitizeInput($_POST['Editck_pend'], 'int');
        $ck_plan = sanitizeInput($_POST['Editck_plan'], 'int');
        $ck_title = sanitizeInput($_POST['Editck_title'], 'string');
        $ck_event = sanitizeInput($_POST['Editck_event'], 'string');
        $ck_attend = sanitizeInput($_POST['Editck_attend'], 'string');
        $ck_rec_K = sanitizeInput($_POST['Editck_rec_K'], 'string');
        $ck_rec_P = sanitizeInput($_POST['Editck_rec_P'], 'string');
        $ck_rec_A = sanitizeInput($_POST['Editck_rec_A'], 'string');
        $ck_problem = sanitizeInput($_POST['Editck_problem'], 'string');
        $ck_solution = sanitizeInput($_POST['Editck_solution'], 'string');
        $ck_term = sanitizeInput($_POST['Editck_term'], 'int');
        $ck_pee = sanitizeInput($_POST['Editck_pee'], 'int');

        $img_name = '';
        $is_img_uploaded = false;
        if (isset($_FILES['Editimg_name']) && $_FILES['Editimg_name']['error'] === UPLOAD_ERR_OK) {
            $img_name = $_FILES['Editimg_name']['name'];
            $img_tmp = $_FILES['Editimg_name']['tmp_name'];
            $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png');

            if (!in_array($img_ext, $allowed_ext)) {
                $response['message'] = 'กรุณาอัพโหลดไฟล์รูปภาพที่มีนามสกุล .jpg, .jpeg, หรือ .png เท่านั้น';
                echo json_encode($response);
                exit();
            }

            $img_name = $editReportid . '.' . $img_ext;
            $upload_dir = "../uploads/".$ck_pee."/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $img_path = $upload_dir . $img_name;

            if (!move_uploaded_file($img_tmp, $img_path)) {
                $response['message'] = 'เกิดข้อผิดพลาดในการอัพโหลดรูปภาพ';
                echo json_encode($response);
                exit();
            } else {
                $is_img_uploaded = true;
            }
        }

        $database = new Database_CKTeach();
        $pdo = $database->getConnection();

        $ckTeach = new Subject($pdo);

        if ($ckTeach->updateReport($editReportid, $ck_date, $sub_no, $ck_level, $ck_room, $ck_pstart, $ck_pend, $ck_plan, $ck_title, $ck_event, $ck_attend, $ck_rec_K, $ck_rec_P, $ck_rec_A, $ck_problem, $ck_solution, $ck_term, $ck_pee, $is_img_uploaded ? $img_name : null)) {
            $response['success'] = true;
            $response['message'] = 'บันทึกการเปลี่ยนแปลงเรียบร้อยแล้ว';
        } else {
            $errorInfo = $stmt->errorInfo();
            $response['message'] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $errorInfo[2];
        }

        echo json_encode($response);
    }
} catch (Exception $e) {
    $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    echo json_encode($response);
}
?>
