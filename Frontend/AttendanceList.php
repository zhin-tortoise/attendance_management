<?php

require_once(dirname(__FILE__) . '/../Backend/Application/EmployeeApplication.php');

session_start();

$employeeApplication = new EmployeeApplication();
if (array_key_exists('mailAddress', $_POST) && array_key_exists('password', $_POST)) {
    if (!$employeeApplication->employeeLogin($_POST['mailAddress'], $_POST['password'])) {
        require_once('./EmployeeLoginFailed.html');
        exit;
    }
}

if (!array_key_exists('employeeId', $_SESSION)) {
    require_once('./EmployeeLoginFailed.html');
    exit;
}

?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>勤怠管理ソフト</title>
    <link rel='stylesheet' href='Css/Main.css'>
    <link rel='stylesheet' href='Css/AttendanceList.css'>
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
            require_once(dirname(__FILE__) . '/../Backend/Application/AttendanceApplication.php');
            $attendanceApplication = new AttendanceApplication();
            $thisYearMonth = array_key_exists('thisMonth', $_GET) ? date('Y') . $_GET['thisMonth'] : date('Ym');
            $attendanceEntities = $attendanceApplication->findAttendanceFromEmployeeIdAndDate($_SESSION['employeeId'], $thisYearMonth);
            $totalAttendanceValueObject = $attendanceApplication->findTotalAttendance($attendanceEntities);
            echo "<p id='root-overview-workingDays'>勤務日数 : {$totalAttendanceValueObject->getWorkingDays()}</p>";
            echo "<p id='root-overview-workingHours'>勤務時間 : {$totalAttendanceValueObject->getWorkingHours()->getHoursAndMinutes()}</p>";
            echo "<p id='root-overview-normalOvertime'>普通残業 : {$totalAttendanceValueObject->getNormalOvertime()->getHoursAndMinutes()}</p>";
            echo "<p id='root-overview-midnightOvertime'>深夜残業 : {$totalAttendanceValueObject->getMidnightOvertime()->getHoursAndMinutes()}</p>";
            echo "<p id='root-overview-absenceDays'>欠勤日数 : {$totalAttendanceValueObject->getAbsenceDays()}</p>";
            echo "<p id='root-overview-absenceHours'>欠課時間 : {$totalAttendanceValueObject->getAbsenceHours()->getHoursAndMinutes()}</p>";
            ?>
        </div>
        <div id='root-menu'>
            <?php
            $thisMonth = array_key_exists('thisMonth', $_GET) ? (int)$_GET['thisMonth'] : (int)date('m');
            echo "<span id='root-menu-thisMonth'>{$thisMonth}月</span>"
            ?>

            <span id='root-menu-lastMonth'>先月</span>
            <span id='root-menu-nextMonth'>来月</span>
            <span id='root-menu-bulkRequest'>一括申請</span>
            <span id='root-menu-bulkCancel'>一括取り下げ</span>
        </div>
        <div id='root-detail'>
            <table id='root-detail-attendance'>
                <thead>
                    <tr id='root-detail-attendance-header'>
                        <th id='root-detail-attendance-header-status'>ステータス</th>
                        <th id='root-detail-attendance-header-request'>申請</th>
                        <th id='root-detail-attendance-header-cancel'>取り下げ</th>
                        <th id='root-detail-attendance-header-edit'>編集</th>
                        <th id='root-detail-attendance-header-workingDay'>勤務日</th>
                        <th id='root-detail-attendance-header-dayOfTheWeek'>曜日</th>
                        <th id='root-detail-attendance-header-startTime'>始業時刻</th>
                        <th id='root-detail-attendance-header-finishTime'>終業時刻</th>
                        <th id='root-detail-attendance-header-breakTime'>休憩時間</th>
                        <th id='root-detail-attendance-header-workingHours'>勤務時間</th>
                        <th id='root-detail-attendance-header-overtime'>残業時間</th>
                        <th id='root-detail-attendance-header-midnightOvertime'>深夜残業</th>
                        <th id='root-detail-attendance-header-absenceHours'>欠課時間</th>
                        <th id='root-detail-attendance-header-remarks'>備考</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once(dirname(__FILE__) . '/../Backend/Application/AttendanceApplication.php');
                    $attendanceApplication = new AttendanceApplication();
                    $thisYearMonth = array_key_exists('thisMonth', $_GET) ? date('Y') . $_GET['thisMonth'] : date('Ym');
                    $attendanceEntities = $attendanceApplication->findAttendanceFromEmployeeIdAndDate($_SESSION['employeeId'], $thisYearMonth);
                    $attendanceEntityIndex = 0;

                    foreach ($attendanceEntities as $attendanceEntity) {
                        if ($attendanceEntity->isSaturday()) {
                            echo "<tr id='root-detail-attendance-row' class='root-detail-attendance-row-saturday'>";
                        } elseif ($attendanceEntity->isSunday()) {
                            echo "<tr id='root-detail-attendance-row' class='root-detail-attendance-row-sunday'>";
                        } else {
                            echo "<tr id='root-detail-attendance-row'>";
                        }
                        echo "<p id='root-detail-attendance-row-id-{$attendanceEntityIndex}' class='root-detail-attendance-row-id'>{$attendanceEntity->getAttendanceId()}</p>";
                        echo "<td id='root-detail-attendance-row-status-{$attendanceEntityIndex}' class='root-detail-attendance-row-status'>{$attendanceEntity->getStatus()}</td>";
                        echo "<td id='root-detail-attendance-row-request-{$attendanceEntityIndex}' class='root-detail-attendance-row-request'>申請</td>";
                        echo "<td id='root-detail-attendance-row-cancel-{$attendanceEntityIndex}' class='root-detail-attendance-row-cancel'>取り下げ</td>";
                        echo "<td id='root-detail-attendance-row-edit-{$attendanceEntityIndex}' class='root-detail-attendance-row-edit'>編集</td>";
                        echo "<td id='root-detail-attendance-row-workingDay-{$attendanceEntityIndex}' class='root-detail-attendance-row-workingDay'>" . (int)substr($attendanceEntity->getWorkingDay(), -2, 2) . '</td>';
                        echo "<td id='root-detail-attendance-row-dayOfTheWeek-{$attendanceEntityIndex}' class='root-detail-attendance-row-dayOfTheWeek'>{$attendanceEntity->getDayOfTheWeek()}</td>";
                        echo "<td id='root-detail-attendance-row-stareTime-{$attendanceEntityIndex}' class='root-detail-attendance-row-stareTime'>{$attendanceEntity->getStartTime()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-finishTime-{$attendanceEntityIndex}' class='root-detail-attendance-row-finishTime'>{$attendanceEntity->getFinishTime()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-brakeTime-{$attendanceEntityIndex}' class='root-detail-attendance-row-brakeTime'>{$attendanceEntity->getBreakTime()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-workingHours-{$attendanceEntityIndex}' class='root-detail-attendance-row-workingHours'>{$attendanceEntity->getWorkingHours()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-overtime-{$attendanceEntityIndex}' class='root-detail-attendance-row-overtime'>{$attendanceEntity->getOvertime()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-midnightOvertime-{$attendanceEntityIndex}' class='root-detail-attendance-row-midnightOvertime'>{$attendanceEntity->getMidnightOvertime()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-absenceHours-{$attendanceEntityIndex}' class='root-detail-attendance-row-absenceHours'>{$attendanceEntity->getAbsenceHours()->getHoursAndMinutes()}</td>";
                        echo "<td id='root-detail-attendance-row-remarks-{$attendanceEntityIndex}' class='root-detail-attendance-row-remarks'>{$attendanceEntity->getRemarks()}</td>";
                        echo '</tr>';
                        $attendanceEntityIndex++;
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>

    <script src="Js/AttendanceList.js"></script>

</body>

</html>