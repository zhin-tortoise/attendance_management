<?php

/**
 * 一括申請を行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/AttendanceApplication.php');

header("Content-Type: application/json; charset=utf-8");

$attendanceApplication = new AttendanceApplication();
$errorCode = $attendanceApplication->bulkRequest(json_decode($_POST['attendanceIds'], true));

echo json_encode(['errorCode' => $errorCode]);
