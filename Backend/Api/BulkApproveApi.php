<?php

/**
 * 一括承認を行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/AttendanceApplication.php');

header("Content-Type: application/json; charset=utf-8");

$attendanceApplication = new AttendanceApplication();
$errorCode = $attendanceApplication->bulkApprove(json_decode($_POST['attendanceIds'], true));

echo json_encode(['errorCode' => $errorCode]);
