<?php

include_once("../../config/Database.php");
include_once("../../class/Setting.php");

$connectDB = new Database_General();
$db = $connectDB->getConnection();

$config = new Setting_Config($db);
$settings_meetingroom = $config->fetchMeetingRooms();

header('Content-Type: application/json');
echo json_encode($settings_meetingroom);
?>
