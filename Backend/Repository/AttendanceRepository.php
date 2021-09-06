<?php

/**
 * 勤怠テーブルのリポジトリ。
 * 勤怠テーブルへのアクセスを担う。
 */

require_once(dirname(__FILE__) . '/IAttendanceRepository.php');
require_once(dirname(__FILE__) . '/../Domain/AttendanceEntity.php');
require_once(dirname(__FILE__) . '/../Domain/TotalAttendanceValueObject.php');

class AttendanceRepository implements IAttendanceRepository
{
    private $pdo; // DBアクセスを行うPDOクラス。

    /**
     * コンストラクタでPDOを設定する。
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 勤怠IDを引数から取得し、それから勤怠エンティティを取得して返す。
     * @param int $attendanceId 勤怠ID。
     * @return AttendanceEntity|false 勤怠エンティティかfalseが返る。
     */
    public function findAttendanceFromAttendanceId(int $attendanceId)
    {
        $sql = "select attendance_id as attendanceId, employee_id as employeeId, ";
        $sql .= "working_day as workingDay, day_of_the_week as dayOfTheWeek, ";
        $sql .= "status, date_format(start_time, '%H:%i') as startTime, ";
        $sql .= "date_format(finish_time, '%H:%i') as finishTime, date_format(break_time, '%H:%i') as breakTime, ";
        $sql .= "date_format(working_hours, '%H:%i') as workingHours, date_format(overtime, '%H:%i') as overtime, ";
        $sql .= "date_format(normal_overtime, '%H:%i') as normalOvertime, ";
        $sql .= "date_format(midnight_overtime, '%H:%i') as midnightOvertime, ";
        $sql .= "date_format(absence_hours, '%H:%i') as absenceHours, remarks ";
        $sql .= 'from attendance ';
        $sql .= "where attendance_id = :attendance_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':attendance_id', $attendanceId);
        $stmt->execute();

        return $stmt->rowCount() ? new AttendanceEntity($stmt->fetch()) : false;
    }

    /**
     * 従業員IDと日付を引数から取得し、それらから勤怠エンティティを読み込み、勤怠エンティティの配列を返す。
     * @param int $employeeId 従業員ID。
     * @param string $date 年月。
     * @return array 引数で与えられた従業員IDと日付に紐づく勤怠エンティティの配列。
     */
    public function findAttendanceFromEmployeeIdAndDate(int $employeeId, string $date)
    {
        $sql = "select attendance_id as attendanceId, employee_id as employeeId, ";
        $sql .= "working_day as workingDay, day_of_the_week as dayOfTheWeek, ";
        $sql .= "status, date_format(start_time, '%H:%i') as startTime, ";
        $sql .= "date_format(finish_time, '%H:%i') as finishTime, date_format(break_time, '%H:%i') as breakTime, ";
        $sql .= "date_format(working_hours, '%H:%i') as workingHours, date_format(overtime, '%H:%i') as overtime, ";
        $sql .= "date_format(normal_overtime, '%H:%i') as normalOvertime, ";
        $sql .= "date_format(midnight_overtime, '%H:%i') as midnightOvertime, ";
        $sql .= "date_format(absence_hours, '%H:%i') as absenceHours, remarks ";
        $sql .= 'from attendance ';
        $sql .= "where employee_id = :employee_id and date_format(working_day, '%Y%m') = :date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':employee_id', $employeeId);
        $stmt->bindValue(':date', $date);
        $stmt->execute();

        $attendances = [];
        foreach ($stmt->fetchAll() as $attendance) {
            $attendances[] = new AttendanceEntity($attendance);
        }

        return $attendances;
    }

    /**
     * 引数の勤怠エンティティのリストから、合計勤怠バリューオブジェクトを返す。
     * @param array $attendanceEntities 勤怠エンティティのリスト。
     * @return TotalAttendanceValueObject 合計勤怠バリューオブジェクト。
     */
    public function findTotalAttendance(array $attendanceEntities)
    {
        return new TotalAttendanceValueObject($attendanceEntities);
    }

    /**
     * 引数の勤怠エンティティによって、勤怠を更新する。
     * @param AttendanceEntity $attendanceEntity 勤怠エンティティ。
     * @return int エラーコード。
     */
    public function update(AttendanceEntity $attendanceEntity)
    {
        $sql = 'update attendance set employee_id = :employee_id, working_day = :working_day, ';
        $sql .= 'day_of_the_week = :day_of_the_week, status = :status, start_time = :start_time, ';
        $sql .= 'finish_time = :finish_time, break_time = :break_time, working_hours = :working_hours, ';
        $sql .= 'overtime = :overtime, normal_overtime = :normal_overtime, midnight_overtime = :midnight_overtime, ';
        $sql .= 'absence_hours = :absence_hours, remarks = :remarks ';
        $sql .= 'where attendance_id = :attendance_id;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':attendance_id', $attendanceEntity->getAttendanceId());
        $stmt->bindValue(':employee_id', $attendanceEntity->getEmployeeId());
        $stmt->bindValue(':working_day', $attendanceEntity->getWorkingDay());
        $stmt->bindValue(':day_of_the_week', $attendanceEntity->getDayOfTheWeek());
        $stmt->bindValue(':status', $attendanceEntity->getStatus());
        $stmt->bindValue(':start_time', $attendanceEntity->getStartTime()->getHoursAndMinutes());
        $stmt->bindValue(':finish_time', $attendanceEntity->getFinishTime()->getHoursAndMinutes());
        $stmt->bindValue(':break_time', $attendanceEntity->getBreakTime()->getHoursAndMinutes());
        $stmt->bindValue(':working_hours', $attendanceEntity->getWorkingHours()->getHoursAndMinutes());
        $stmt->bindValue(':overtime', $attendanceEntity->getOvertime()->getHoursAndMinutes());
        $stmt->bindValue(':normal_overtime', $attendanceEntity->getNormalOvertime()->getHoursAndMinutes());
        $stmt->bindValue(':midnight_overtime', $attendanceEntity->getMidnightOvertime()->getHoursAndMinutes());
        $stmt->bindValue(':absence_hours', $attendanceEntity->getAbsenceHours()->getHoursAndMinutes());
        $stmt->bindValue(':remarks', $attendanceEntity->getRemarks());

        try {
            $stmt->execute();
        } catch (Exception $e) {
            return $e->getCode();
        }

        return $stmt->errorCode();
    }
}
