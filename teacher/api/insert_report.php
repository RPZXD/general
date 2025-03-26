<?php
header('Content-Type: application/json');

require_once '../../config/Database.php'; // Adjust this path according to your setup
require_once '../../class/CKTeach.php'; // This file contains the CKTeach class

$response = array('success' => false, 'message' => 'Unknown error');

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Validate and sanitize POST data
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teach_id = sanitizeInput($_POST['teach_id'], 'string');
    $ck_date = sanitizeInput($_POST['ck_date'], 'string');
    $sub_no = sanitizeInput($_POST['sub_no'], 'int');
    $ck_level = sanitizeInput($_POST['ck_level'], 'int');
    $ck_room = sanitizeInput($_POST['ck_room'], 'int');
    $ck_pstart = sanitizeInput($_POST['ck_pstart'], 'int');
    $ck_pend = sanitizeInput($_POST['ck_pend'], 'int');
    $ck_plan = sanitizeInput($_POST['ck_plan'], 'int');
    $ck_title = sanitizeInput($_POST['ck_title'], 'string');
    $ck_event = sanitizeInput($_POST['ck_event'], 'string');
    $ck_attend = sanitizeInput($_POST['ck_attend'], 'string');
    $ck_rec_K = sanitizeInput($_POST['ck_rec_K'], 'string');
    $ck_rec_P = sanitizeInput($_POST['ck_rec_P'], 'string');
    $ck_rec_A = sanitizeInput($_POST['ck_rec_A'], 'string');
    $ck_problem = sanitizeInput($_POST['ck_problem'], 'string');
    $ck_solution = sanitizeInput($_POST['ck_solution'], 'string');
    $ck_term = sanitizeInput($_POST['term'], 'int');
    $ck_pee = sanitizeInput($_POST['pee'], 'int');

    // Generate ck_id
    $date = date("Y-m-d");
    $randomString = generateRandomString(10);
    $ck_id = 'CK_' . $date . '-' . $randomString;

    // Handle file upload (optional)
    $img_name = '';
    if (isset($_FILES['img_name']) && $_FILES['img_name']['error'] === UPLOAD_ERR_OK) {
        $img_name = $_FILES['img_name']['name'];
        $img_tmp = $_FILES['img_name']['tmp_name'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png');

        if (!in_array($img_ext, $allowed_ext)) {
            $response['message'] = 'กรุณาอัพโหลดไฟล์รูปภาพที่มีนามสกุล .jpg, .jpeg, หรือ .png เท่านั้น';
            echo json_encode($response);
            exit();
        }

        // Rename the image file
        $img_name = $ck_id . '.' . $img_ext;
        $upload_dir = "../uploads/".$ck_pee."/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $img_path = $upload_dir . $img_name;

        if (!move_uploaded_file($img_tmp, $img_path)) {
            error_log("Failed to upload file to $img_path");
            $response['message'] = 'เกิดข้อผิดพลาดในการอัพโหลดรูปภาพ';
            echo json_encode($response);
            exit();
        } else {
            error_log("File uploaded successfully to $img_path");
        }
        
    }

    // Database connection
    $database = new Database_CKTeach();
    $pdo = $database->getConnection();

    // Create a new instance of Subject
    $ckTeach = new Subject($pdo);

    if ($ckTeach->insertReport($ck_id, $teach_id, $ck_date, $sub_no, $ck_level, $ck_room, $ck_pstart, $ck_pend, $ck_plan, $ck_title, $ck_event, $ck_attend, $ck_rec_K, $ck_rec_P, $ck_rec_A, $ck_problem, $ck_solution, $img_name, $ck_term, $ck_pee)) {
        $response['success'] = true;
        $response['message'] = 'บันทึกการสอนประจำวันสำเร็จ';
    } else {
        $response['message'] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
    }

    echo json_encode($response);
}
?>
