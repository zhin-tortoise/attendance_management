<?php

/**
 * 承認を行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/AttendanceApplication.php');

header("Content-Type: application/json; charset=utf-8");

$attendanceApplication = new AttendanceApplication();
$errorCode = $attendanceApplication->approve((int)$_POST['attendanceId']);

echo json_encode(['errorCode' => $errorCode]);
