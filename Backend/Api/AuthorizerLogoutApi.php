<?php

/**
 * 承認者ログアウトを行うAPI。
 */

require_once(dirname(__FILE__) . '/../Application/EmployeeApplication.php');

header("Content-Type: application/json; charset=utf-8");

session_start();
$employeeApplication = new EmployeeApplication();
$employeeApplication->authorizerLogout();

echo json_encode([]);
