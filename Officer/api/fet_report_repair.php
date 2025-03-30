<?php
// Include the database configuration and necessary class files
require_once '../../config/Database.php'; // Adjust the path according to your setup
require_once '../../class/Report_repair.php'; // This file contains the Report_repair class
require_once '../../class/User.php'; // This file contains the User class

// Initialize the database connections
$databaseUser = new Database("phichaia_student");
$dbUser = $databaseUser->getConnection();

$databaseGeneral = new Database("phichaia_general");
$dbGeneral = $databaseGeneral->getConnection();

// Check if the connections were successful
if ($dbUser === null || $dbGeneral === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

try {
    // Create instances of the necessary classes
    $reports = new Report_repair($dbGeneral); // Connect with the reports database
    $user = new User($dbUser); // Connect with the users database

    // Fetch report data
    $reportData = $reports->getReportRepair();

    if (empty($reportData)) {
        http_response_code(404);
        echo json_encode(['error' => 'No reports found']);
        exit();
    }

    // Fetch user data based on teach_id
    $response = [];
    foreach ($reportData as $report) {
        $teachId = $report['teach_id'];

        // Get user data for this teach_id
        $userData = $user->getTeacherById($teachId);

        if (empty($userData)) {
            $userData = [['Teach_name' => '-', 'Teach_phone' => '-']]; // Default values
        }

        // Combine report data with user data
        $response[] = [
            'report' => $report,
            'user' => $userData
        ];
    }

    // Output the combined data as JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
