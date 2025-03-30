<?php
require_once '../../config/Database.php'; // Adjust this path according to your setup

// Create a new database connection instance
$database = new Database("phichaia_general");
$conn = $database->getConnection();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data from the POST request
    $addDate = isset($_POST['AddDate']) ? $_POST['AddDate'] : '';
    $addLocation = isset($_POST['AddLocation']) ? $_POST['AddLocation'] : '';
    $doorCount = isset($_POST['doorCount']) ? (int)$_POST['doorCount'] : 0;
    $doorDamage = isset($_POST['doorDamage']) ? $_POST['doorDamage'] : '';
    $windowCount = isset($_POST['windowCount']) ? (int)$_POST['windowCount'] : 0;
    $windowDamage = isset($_POST['windowDamage']) ? $_POST['windowDamage'] : '';
    $tablestCount = isset($_POST['tablestCount']) ? (int)$_POST['tablestCount'] : 0;
    $tablestDamage = isset($_POST['tablestDamage']) ? $_POST['tablestDamage'] : '';
    $chairstCount = isset($_POST['chairstCount']) ? (int)$_POST['chairstCount'] : 0;
    $chairstDamage = isset($_POST['chairstDamage']) ? $_POST['chairstDamage'] : '';
    $tabletaCount = isset($_POST['tabletaCount']) ? (int)$_POST['tabletaCount'] : 0;
    $tabletaDamage = isset($_POST['tabletaDamage']) ? $_POST['tabletaDamage'] : '';
    $chairtaCount = isset($_POST['chairtaCount']) ? (int)$_POST['chairtaCount'] : 0;
    $chairtaDamage = isset($_POST['chairtaDamage']) ? $_POST['chairtaDamage'] : '';
    $other1Details = isset($_POST['other1Details']) ? $_POST['other1Details'] : '';
    $other1Count = isset($_POST['other1Count']) ? (int)$_POST['other1Count'] : 0;
    $other1Damage = isset($_POST['other1Damage']) ? $_POST['other1Damage'] : '';
    $tvCount = isset($_POST['tvCount']) ? (int)$_POST['tvCount'] : 0;
    $tvDamage = isset($_POST['tvDamage']) ? $_POST['tvDamage'] : '';
    $audioCount = isset($_POST['audioCount']) ? (int)$_POST['audioCount'] : 0;
    $audioDamage = isset($_POST['audioDamage']) ? $_POST['audioDamage'] : '';
    $hdmiCount = isset($_POST['hdmiCount']) ? (int)$_POST['hdmiCount'] : 0;
    $hdmiDamage = isset($_POST['hdmiDamage']) ? $_POST['hdmiDamage'] : '';
    $projectorCount = isset($_POST['projectorCount']) ? (int)$_POST['projectorCount'] : 0;
    $projectorDamage = isset($_POST['projectorDamage']) ? $_POST['projectorDamage'] : '';
    $other2Details = isset($_POST['other2Details']) ? $_POST['other2Details'] : '';
    $other2Count = isset($_POST['other2Count']) ? (int)$_POST['other2Count'] : 0;
    $other2Damage = isset($_POST['other2Damage']) ? $_POST['other2Damage'] : '';
    $fanCount = isset($_POST['fanCount']) ? (int)$_POST['fanCount'] : 0;
    $fanDamage = isset($_POST['fanDamage']) ? $_POST['fanDamage'] : '';
    $lightCount = isset($_POST['lightCount']) ? (int)$_POST['lightCount'] : 0;
    $lightDamage = isset($_POST['lightDamage']) ? $_POST['lightDamage'] : '';
    $airCount = isset($_POST['airCount']) ? (int)$_POST['airCount'] : 0;
    $airDamage = isset($_POST['airDamage']) ? $_POST['airDamage'] : '';
    $swCount = isset($_POST['swCount']) ? (int)$_POST['swCount'] : 0;
    $swDamage = isset($_POST['swDamage']) ? $_POST['swDamage'] : '';
    $swfanCount = isset($_POST['swfanCount']) ? (int)$_POST['swfanCount'] : 0;
    $swfanDamage = isset($_POST['swfanDamage']) ? $_POST['swfanDamage'] : '';
    $plugCount = isset($_POST['plugCount']) ? (int)$_POST['plugCount'] : 0;
    $plugDamage = isset($_POST['plugDamage']) ? $_POST['plugDamage'] : '';
    $other3Details = isset($_POST['other3Details']) ? $_POST['other3Details'] : '';
    $other3Count = isset($_POST['other3Count']) ? (int)$_POST['other3Count'] : 0;
    $other3Damage = isset($_POST['other3Damage']) ? $_POST['other3Damage'] : '';
    $teachId = isset($_POST['teach_id']) ? (int)$_POST['teach_id'] : 0;
    $term = isset($_POST['term']) ? (int)$_POST['term'] : 0;
    $pee = isset($_POST['pee']) ? $_POST['pee'] : '';

    // Validate data (simple checks)
    if (empty($addDate) || empty($addLocation) || $teachId <= 0 || $term <= 0 || empty($pee)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required and must be valid.']);
        exit;
    }

    try {
        // Prepare the SQL statement
        $query = "INSERT INTO report_repair (
            AddDate, AddLocation, doorCount, doorDamage, windowCount, windowDamage,
            tablestCount, tablestDamage, chairstCount, chairstDamage, tabletaCount, tabletaDamage,
            chairtaCount, chairtaDamage, other1Details, other1Count, other1Damage, tvCount, tvDamage,
            audioCount, audioDamage, hdmiCount, hdmiDamage, projectorCount, projectorDamage,
            other2Details, other2Count, other2Damage, fanCount, fanDamage, lightCount, lightDamage,
            airCount, airDamage, swCount, swDamage, swfanCount, swfanDamage, plugCount, plugDamage,
            other3Details, other3Count, other3Damage, teach_id, term, pee
        ) VALUES (
            :AddDate, :AddLocation, :doorCount, :doorDamage, :windowCount, :windowDamage,
            :tablestCount, :tablestDamage, :chairstCount, :chairstDamage, :tabletaCount, :tabletaDamage,
            :chairtaCount, :chairtaDamage, :other1Details, :other1Count, :other1Damage, :tvCount, :tvDamage,
            :audioCount, :audioDamage, :hdmiCount, :hdmiDamage, :projectorCount, :projectorDamage,
            :other2Details, :other2Count, :other2Damage, :fanCount, :fanDamage, :lightCount, :lightDamage,
            :airCount, :airDamage, :swCount, :swDamage, :swfanCount, :swfanDamage, :plugCount, :plugDamage,
            :other3Details, :other3Count, :other3Damage, :teach_id, :term, :pee
        )";

        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':AddDate', $addDate);
        $stmt->bindParam(':AddLocation', $addLocation);
        $stmt->bindParam(':doorCount', $doorCount);
        $stmt->bindParam(':doorDamage', $doorDamage);
        $stmt->bindParam(':windowCount', $windowCount);
        $stmt->bindParam(':windowDamage', $windowDamage);
        $stmt->bindParam(':tablestCount', $tablestCount);
        $stmt->bindParam(':tablestDamage', $tablestDamage);
        $stmt->bindParam(':chairstCount', $chairstCount);
        $stmt->bindParam(':chairstDamage', $chairstDamage);
        $stmt->bindParam(':tabletaCount', $tabletaCount);
        $stmt->bindParam(':tabletaDamage', $tabletaDamage);
        $stmt->bindParam(':chairtaCount', $chairtaCount);
        $stmt->bindParam(':chairtaDamage', $chairtaDamage);
        $stmt->bindParam(':other1Details', $other1Details);
        $stmt->bindParam(':other1Count', $other1Count);
        $stmt->bindParam(':other1Damage', $other1Damage);
        $stmt->bindParam(':tvCount', $tvCount);
        $stmt->bindParam(':tvDamage', $tvDamage);
        $stmt->bindParam(':audioCount', $audioCount);
        $stmt->bindParam(':audioDamage', $audioDamage);
        $stmt->bindParam(':hdmiCount', $hdmiCount);
        $stmt->bindParam(':hdmiDamage', $hdmiDamage);
        $stmt->bindParam(':projectorCount', $projectorCount);
        $stmt->bindParam(':projectorDamage', $projectorDamage);
        $stmt->bindParam(':other2Details', $other2Details);
        $stmt->bindParam(':other2Count', $other2Count);
        $stmt->bindParam(':other2Damage', $other2Damage);
        $stmt->bindParam(':fanCount', $fanCount);
        $stmt->bindParam(':fanDamage', $fanDamage);
        $stmt->bindParam(':lightCount', $lightCount);
        $stmt->bindParam(':lightDamage', $lightDamage);
        $stmt->bindParam(':airCount', $airCount);
        $stmt->bindParam(':airDamage', $airDamage);
        $stmt->bindParam(':swCount', $swCount);
        $stmt->bindParam(':swDamage', $swDamage);
        $stmt->bindParam(':swfanCount', $swfanCount);
        $stmt->bindParam(':swfanDamage', $swfanDamage);
        $stmt->bindParam(':plugCount', $plugCount);
        $stmt->bindParam(':plugDamage', $plugDamage);
        $stmt->bindParam(':other3Details', $other3Details);
        $stmt->bindParam(':other3Count', $other3Count);
        $stmt->bindParam(':other3Damage', $other3Damage);
        $stmt->bindParam(':teach_id', $teachId);
        $stmt->bindParam(':term', $term);
        $stmt->bindParam(':pee', $pee);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Record added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add record.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
