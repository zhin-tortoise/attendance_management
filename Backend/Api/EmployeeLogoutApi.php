<?php

/**
 * 従業員のログアウトを行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/EmployeeApplication.php');

header("Content-Type: application/json; charset=utf-8");

session_start();
$employeeApplication = new EmployeeApplication();
$employeeApplication->employeeLogout();

echo json_encode([]);
