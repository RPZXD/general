<?php
// filepath: c:\xampp\htdocs\general\Officer\api\add_driver.php
include_once("../../config/Database.php");
include_once("../../class/Driver.php");

$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

$driver = new Driver($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่าข้อมูลถูกส่งมาในรูปแบบ JSON หรือ POST ปกติ
    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($data) {
        $driver->full_name = $data['FullName'] ?? null;
        $driver->phone = $data['Phone'] ?? null;
        $driver->license_no = $data['LicenseNo'] ?? null;
        $driver->license_expiry = $data['LicenseExpiry'] ?? null;
    } else {
        $driver->full_name = $_POST['FullName'] ?? null;
        $driver->phone = $_POST['Phone'] ?? null;
        $driver->license_no = $_POST['LicenseNo'] ?? null;
        $driver->license_expiry = $_POST['LicenseExpiry'] ?? null;
    }

    // ตรวจสอบว่าค่าทั้งหมดมีอยู่
    if (empty($driver->full_name) || empty($driver->phone) || empty($driver->license_no) || empty($driver->license_expiry)) {
        http_response_code(400);
        echo json_encode(["message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit();
    }

    // เพิ่มข้อมูลลงในฐานข้อมูล
    if ($driver->addDriver()) {
        echo json_encode(["message" => "เพิ่มข้อมูลคนขับสำเร็จ"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "ไม่สามารถเพิ่มข้อมูลคนขับได้"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>
