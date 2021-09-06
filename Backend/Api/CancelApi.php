<?php

/**
 * 取り下げを行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/AttendanceApplication.php');

header("Content-Type: application/json; charset=utf-8");

$attendanceApplication = new AttendanceApplication();
$errorCode = $attendanceApplication->cancel((int)$_POST['attendanceId']);

echo json_encode(['errorCode' => $errorCode]);
