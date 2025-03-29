<?php
// Include database configuration file
require_once '../../config/Database.php';
require_once '../../class/Booking.php';

// Create a new database connection instance
$database = new Database_General();
$conn = $database->getConnection();


function sendLineNotifyMessage($accessToken, $message) {
    $url = 'https://notify-api.line.me/api/notify';
    
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Bearer ' . $accessToken
    );
    
    $data = array(
        'message' => $message
    );
    
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    );
    
    $curl = curl_init();
    curl_setopt_array($curl, $options);
    
    $response = curl_exec($curl);
    $error = curl_error($curl);
    
    curl_close($curl);
    
    if ($error) {
    } else {
    }
}

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the POST request
$teach_id = $_POST['teach_id'];
$term = $_POST['term'];
$pee = $_POST['pee'];
$BookmarkDate = $_POST['BookmarkDate'];
$BookmarkTimeStart = $_POST['BookmarkTimeStart'];
$BookmarkTimeEnd = $_POST['BookmarkTimeEnd'];
$BookmarkDetail = $_POST['BookmarkDetail'];
$BookmarkLocation = $_POST['BookmarkLocation'];
$BookmarkTel = $_POST['BookmarkTel'];
$BookmarkMedia = isset($_POST['BookmarkMedia']) ? implode(", ", $_POST['BookmarkMedia']) : NULL;

// Initialize Booking class
$booking = new Booking($conn);

// Check for booking conflicts
if ($booking->checkConflict($BookmarkLocation, $BookmarkDate, $BookmarkTimeStart, $BookmarkTimeEnd)) {
    echo json_encode(['success' => false, 'message' => 'The selected time slot is already booked.']);
    exit;
}

// Prepare data for insertion
$data = [
    'teach_id' => $teach_id,
    'term' => $term,
    'pee' => $pee,
    'date' => $BookmarkDate,
    'time_start' => $BookmarkTimeStart,
    'time_end' => $BookmarkTimeEnd,
    'purpose' => $BookmarkDetail,
    'location' => $BookmarkLocation,
    'media' => $BookmarkMedia,
    'phone' => $BookmarkTel
];

// Convert date to Thai format
$thaiMonths = [
    "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน",
    "05" => "พฤษภาคม", "06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม",
    "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
];
$dateParts = explode("-", $BookmarkDate);
$thaiDate = intval($dateParts[2]) . " " . $thaiMonths[$dateParts[1]] . " " . ($dateParts[0] + 543);

// Convert media array to string
$BookmarkMediaString = is_array($BookmarkMedia) ? implode(", ", $BookmarkMedia) : $BookmarkMedia;

$message = "
    ระบบจองห้องประชุม
    📅 วันที่: $thaiDate
    🕒 เวลา: $BookmarkTimeStart - $BookmarkTimeEnd
    📍 สถานที่: $BookmarkLocation
    📞 โทรศัพท์: $BookmarkTel
    🎯 จุดประสงค์: $BookmarkDetail
    📦 อุปกรณ์ที่ใช้: $BookmarkMediaString
    👤 ชื่อผู้จอง: เจ้าหน้าที่
";


// Insert booking data
if ($booking->createBooking($data)) {
    echo json_encode(['success' => true, 'message' => 'Booking added successfully.']);
    // $accessToken = 'eCBHhUJpqKJ2W8EyVRk7iYRXrYpsuGscoDeX5CWE0ao';
    $accessToken = '';
    sendLineNotifyMessage($accessToken, $message);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Could not add booking.']);
}

// Close the connection
$conn = null;
?>
