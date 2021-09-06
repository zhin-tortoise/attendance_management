<?php

require_once(dirname(__FILE__) . '/../Backend/Application/EmployeeApplication.php');

session_start();

$employeeApplication = new EmployeeApplication();
if (array_key_exists('mailAddress', $_POST) && array_key_exists('password', $_POST)) {
    if (!$employeeApplication->authorizerLogin($_POST['mailAddress'], $_POST['password'])) {
        require_once('./AuthorizerLoginFailed.html');
        exit;
    }
}

if (!array_key_exists('authorizerId', $_SESSION)) {
    require_once('./AuthorizerLoginFailed.html');
    exit;
}
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>勤怠管理ソフト</title>
    <link rel='stylesheet' href='Css/Main.css'>
    <link rel='stylesheet' href='Css/EmployeeList.css'>
</head>

<body>
    <div id='root'>
        <img id='root-backgroundPicture' src='./Picture/Background.jpg'></img>
        <div id='root-background'></div>
        <div id='root-navigation'>
            <p id='root-navigation-logout'>ログアウト</p>
        </div>
        <div id='root-overview'>
            <?php
            require_once(dirname(__FILE__) . '/../Backend/Application/EmployeeApplication.php');
            require_once(dirname(__FILE__) . '/../Backend/Application/AttendanceApplication.php');

            $employeeApplication = new EmployeeApplication();
            $attendanceApplication = new AttendanceApplication();

            $employeeEntities = $employeeApplication->findEmployeeOfTeam($_SESSION['authorizerId']);
            $thisYearMonth = array_key_exists('thisMonth', $_GET) ? date('Y') . $_GET['thisMonth'] : date('Ym');
            $totalAttendanceValueObject = $attendanceApplication->findTotalAttendanceFromEmployeeEntities($employeeEntities, $thisYearMonth);

            echo "<p id='root-overview-totalPeople'>総人数 : " . count($employeeEntities) . '</p>';
            echo "<p id='root-overview-approved'>承認済み : {$attendanceApplication->getTotalApprovalNumber($employeeEntities,$thisYearMonth)}</p>";
            echo "<p id='root-overview-approvalPending'>承認待ち : {$attendanceApplication->getTotalRequestNumber($employeeEntities,$thisYearMonth)}</p>";
            echo "<p id='root-overview-unapplied'>未申請 : {$attendanceApplication->getTotalUnappliedNumber($employeeEntities,$thisYearMonth)}</p>";
            echo "<p id='root-overview-totalWorkingHours'>総勤務時間 : {$totalAttendanceValueObject->getWorkingHours()->getHoursAndMinutes()}</p>";
            echo "<p id='root-overview-totalOvertimeHours'>総残業時間 : {$totalAttendanceValueObject->getNormalOvertime()->add($totalAttendanceValueObject->getMidnightOvertime())->getHoursAndMinutes()}</p>";
            echo "<p id='root-overview-totalAbsenceHours'>総欠課時間 : {$totalAttendanceValueObject->getAbsenceHours()->getHoursAndMinutes()}</p>";
            ?>
        </div>
        <div id='root-menu'>
            <?php
            $thisMonth = array_key_exists('thisMonth', $_GET) ? (int)$_GET['thisMonth'] : (int)date('m');
            echo "<span id='root-menu-thisMonth'>{$thisMonth}月</span>"
            ?>

            <span id='root-menu-lastMonth'>先月</span>
            <span id='root-menu-nextMonth'>来月</span>
        </div>
        <div id='root-detail'>
            <table id='root-detail-employee'>
                <thead>
                    <tr id='root-detail-employee-header'>
                        <th id='root-detail-employee-header-status'>ステータス</th>
                        <th id='root-detail-employee-header-approvalNumber'>承認数</th>
                        <th id='root-detail-employee-header-verification'>確認</th>
                        <th id='root-detail-employee-header-name'>名前</th>
                        <th id='root-detail-employee-header-workingDays'>勤務日数</th>
                        <th id='root-detail-employee-header-workingHours'>勤務時間</th>
                        <th id='root-detail-employee-header-normalOvertimeHours'>普通残業</th>
                        <th id='root-detail-employee-header-midnightOvertimeHours'>深夜残業</th>
                        <th id='root-detail-employee-header-absenceDays'>欠課日数</th>
                        <th id='root-detail-employee-header-absenceHours'>欠課時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once(dirname(__FILE__) . '/../Backend/Application/EmployeeApplication.php');
                    require_once(dirname(__FILE__) . '/../Backend/Application/AttendanceApplication.php');
                    $employeeApplication = new EmployeeApplication();
                    $attendanceApplication = new AttendanceApplication();

                    $employeeEntities = $employeeApplication->findEmployeeOfTeam($_SESSION['authorizerId']);
                    $thisYearMonth = array_key_exists('thisMonth', $_GET) ? date('Y') . $_GET['thisMonth'] : date('Ym');
                    $employeeEntityIndex = 0;
                    foreach ($employeeEntities as $employeeEntity) {
                        $attendanceEntities = $attendanceApplication->findAttendanceFromEmployeeIdAndDate($employeeEntity->getEmployeeId(), $thisYearMonth);
                        $totalAttendanceValueObject = $attendanceApplication->findTotalAttendance($attendanceEntities);

                        echo "<tr class='root-detail-employee-row'>";
                        echo "<p id='root-detail-employee-row-employeeId-${employeeEntityIndex}' class='root-detail-employee-row-employeeId'>{$employeeEntity->getEmployeeId()}</p>";
                        echo "<td class='root-detail-employee-row-status'>{$totalAttendanceValueObject->getStatus()}</td>";
                        echo "<td class='root-detail-employee-row-approvalNumber'>{$totalAttendanceValueObject->getApprovalNumber()} / {$totalAttendanceValueObject->getDays()}</td>";
                        echo "<td id='root-detail-employee-row-verification-${employeeEntityIndex}' class='root-detail-employee-row-verification'>確認</td>";
                        echo "<td class='root-detail-employee-row-name'>{$employeeEntity->getName()}</td>";
                        echo "<td class='root-detail-employee-row-workingDays'>{$totalAttendanceValueObject->getWorkingDays()}</td>";
                        echo "<td class='root-detail-employee-row-workingHours'>{$totalAttendanceValueObject->getWorkingHours()->getHoursAndMinutes()}</td>";
                        echo "<td class='root-detail-employee-row-normalOvertimeHours'>{$totalAttendanceValueObject->getNormalOvertime()->getHoursAndMinutes()}</td>";
                        echo "<td class='root-detail-employee-row-midnightOvertimeHours'>{$totalAttendanceValueObject->getMidnightOvertime()->getHoursAndMinutes()}</td>";
                        echo "<td class='root-detail-employee-row-absenceDays'>{$totalAttendanceValueObject->getAbsenceDays()}</td>";
                        echo "<td class='root-detail-employee-row-absenceHours'>{$totalAttendanceValueObject->getAbsenceHours()->getHoursAndMinutes()}</td>";
                        echo '</tr>';
                        $employeeEntityIndex++;
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>

    <script src="Js/EmployeeList.js"></script>

</body>


</html>