<?php
session_start();
// Include necessary files
require_once '../config/Database.php'; // Adjust this path according to your setup
require_once '../class/CKTeach.php'; // This file contains the CKTeach class
require_once '../class/UserLogin.php'; // This file contains the UserLogin class
require_once '../class/Utils.php'; // This file contains the UserLogin class

if (!isset($_SESSION['Teacher_login'])) {
    $sw2 = new SweetAlert2(
        'คุณยังไม่ได้เข้าสู่ระบบ',
        'error',
        '../login.php' // Redirect URL
    );
    $sw2->renderAlert();
    exit;
}

// Function to repeat &nbsp;
function repeatSpace($count) {
    return str_repeat('&nbsp;', $count);
}

// Check if id is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database_CKTeach();
    $pdo = $database->getConnection();

    $ckTeach = new Subject($pdo);

    // Fetch report details by report ID
    $data = $ckTeach->getReportByIdforPrint($id);

    // Check if data is found
    if ($data) {
        $connectDB = new Database_User();
        $db = $connectDB->getConnection();

        $user = new UserLogin($db);

        $userid = $data['teach_id'];
        $userData = $user->userData($userid);

        echo "<html>";
        echo "<head>";
        echo "<title>บันทึกหลังแผนการจัดการเรียนรู้ที่ " . htmlspecialchars($data['ck_plan']) . 
        " วิชา " . htmlspecialchars($data['sub_name']) . " รหัสวิชา " . htmlspecialchars($data['sub_id']) . "</title>";
        echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>";
        echo "<link rel='preconnect' href='https://fonts.googleapis.com'>";
        echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
        echo "<link href='https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap' rel='stylesheet'>";
        echo "<style>
                body {
                    font-family: 'Sarabun', sans-serif;
                    font-size: 16px; /* Default font size */
                }
                h5 {
                    font-size: 18px; /* h5 font size */
                }
                .print-content {
                    padding: 50px;
                }
                .page-break {
                    page-break-before: always;
                }
              </style>";
        echo "</head>";
        echo "<body>";
        echo "<div class='container'>";
        echo "<div class='print-content'>";
        echo "<p class='text-center'> <img src='../dist/img/logo-phicha.png' style='width:50px;height:50px;'></p>";
        echo "<h5 class='text-center'><strong>บันทึกหลังแผนการจัดการเรียนรู้ที่ " . htmlspecialchars($data['ck_plan']) . "</strong></h5>";
        echo "<p><strong>1. ผลการสอน</strong></p>";
        echo "<p>" . repeatSpace(20) . "1.1 ด้านการจัดการเรียนรู้ (K)</p>";
        echo "<p>" . repeatSpace(20) . htmlspecialchars($data['ck_rec_K']) . "</p>";
        echo "<p>" . repeatSpace(20) . "1.2 ด้านทักษะกระบวนการ (P)</p>";
        echo "<p>" . repeatSpace(20) . htmlspecialchars($data['ck_rec_P']) . "</p>";
        echo "<p>" . repeatSpace(20) . "1.3 ด้านคุณลักษณะอันพึงประสงค์ (A)</p>";
        echo "<p>" . repeatSpace(20) . htmlspecialchars($data['ck_rec_A']) . "</p>";
        echo "<p><strong>2. ปัญหา/อุปสรรค</strong></p>";
        echo "<p>" . repeatSpace(20) . htmlspecialchars($data['ck_problem']) . "</p>";
        echo "<p><strong>3. ข้อเสนอแนะ/แนวทางแก้ไข</strong></p>";
        echo "<p>" . repeatSpace(20) . htmlspecialchars($data['ck_solution']) . "</p>";
        echo "<p><strong>4. ภาพประกอบการสอน</strong></p>";
        echo "<br>";
        if (!empty($data['img_name'])) {
            echo "<p class='text-center'>
                    <img src='uploads/" . htmlspecialchars($data['ck_pee']) . "/" . htmlspecialchars($data['img_name']) . "' 
                        style='width:auto;max-width:100%;height:250px;'>
                </p>";

        }
        echo "<br><br>";
        echo "<p class='text-right'>ลงชื่อ" . str_repeat('.', 50) . "ครูผู้สอน" . repeatSpace(30) . "</p>";
        echo "<p class='text-right'>(" . htmlspecialchars($userData['Teach_name']) . ")" . repeatSpace(47) . "</p>";
        echo "<p class='text-right'>" . 
            "วันที่" . str_repeat('.', 10) . 
            "เดือน" . str_repeat('.', 30) . 
            "ปี" . str_repeat('.', 10) . 
            repeatSpace(33) . "</p>";
        echo "<br>";
        
        // Add a page break before the comments section
        echo "<div class='page-break'></div>";
        echo "<br><br><br><br><br>";
        
        echo "<p><strong>ข้อคิดเห็น/ข้อเสนอแนะ ของผู้อํานวยการหรือผู้ที่ได้รับมอบหมาย</strong></p>";
        echo "<p>" . str_repeat('.', 210) . "</p>";
        echo "<p>" . str_repeat('.', 210) . "</p>";
        echo "<p>" . str_repeat('.', 210) . "</p>";
        echo "<p>" . str_repeat('.', 210) . "</p>";
        echo "<br>";
        echo "<p class='text-right'>ลงชื่อ" . str_repeat('.', 50) . repeatSpace(30) . "</p>";
        echo "<p class='text-right'>(" . str_repeat('.', 49) . ")" . repeatSpace(30) . "</p>";
        // echo "<p class='text-right'>รองผู้อำนวยการฝ่ายวิชาการ" . repeatSpace(30) . "</p>";
        echo "</div>";
        echo "</div>";
        echo "<script>window.print();</script>";
        echo "</body>";
        echo "</html>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>ไม่พบข้อมูล</div>";
    }
} else {
    echo "<div class='alert alert-warning' role='alert'>ไม่มี id ที่ถูกส่งมา</div>";
}
?>
