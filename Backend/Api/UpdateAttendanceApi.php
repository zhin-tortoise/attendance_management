<?php

/**
 * 勤怠の更新を行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/AttendanceApplication.php');

header("Content-Type: application/json; charset=utf-8");

$attendanceApplication = new AttendanceApplication();
$errorCode = $attendanceApplication->update($_POST);

echo json_encode(['errorCode' => $errorCode]);
