<?php

/**
 * 勤怠リポジトリに対応するインターフェース。
 */

interface IAttendanceRepository
{
    public function findAttendanceFromAttendanceId(int $attendanceId);
    public function findAttendanceFromEmployeeIdAndDate(int $employeeId, string $date);
    public function findTotalAttendance(array $attendanceEntities);
    public function update(AttendanceEntity $attendanceEntity);
}
