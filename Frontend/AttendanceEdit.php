<?php
session_start()
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>勤怠管理ソフト</title>
    <link rel='stylesheet' href='Css/Main.css'>
    <link rel='stylesheet' href='Css/AttendanceEdit.css'>
</head>

<body>
    <div id='root'>
        <img id='root-backgroundPicture' src='./Picture/Background.jpg'></img>
        <div id='root-background'></div>
        <p id='root-attendanceId'><?php echo $_GET['attendanceId'] ?></p>
        <p id='root-employeeId'><?php echo $_SESSION['employeeId'] ?></p>
        <p id='root-status'><?php echo $_GET['status'] ?></p>
        <?php
        $date = "{$_GET['thisMonth']}月{$_GET['workingDay']}日 {$_GET['dayOfTheWeek']}曜日";
        echo "<label id='root-date'>$date</label>";
        ?>
        <label id='root-startTime'>始業時刻</label>
        <input id='root-startTimeInput' type='time' value='<?php echo $_GET['startTime']; ?>'>
        <label id='root-finishTime'>終業時刻</label>
        <input id='root-finishTimeInput' type='time' value='<?php echo $_GET['finishTime']; ?>'>
        <label id='root-breakTime'>休憩時間</label>
        <input id='root-breakTimeInput' type="time" value='<?php echo $_GET['breakTime']; ?>'>
        <label id='root-workingHours'>勤務時間 : <?php echo $_GET['workingHours'] ?></label>
        <label id='root-overtime'>残業時間 : <?php echo $_GET['overtime']; ?></label>
        <label id='root-midnightOvertime'>深夜時間 : <?php echo $_GET['midnightOvertime']; ?></label>
        <label id='root-absenceHours'>欠課時間 : <?php echo $_GET['absenceHours']; ?></label>
        <label id='root-remarks'>備考</label>
        <textarea id='root-remarksInput'><?php echo $_GET['remarks']; ?></textarea>
        <div id='root-button'>更新</div>
    </div>

    <script src="Js/AttendanceEdit.js"></script>
</body>

</html>