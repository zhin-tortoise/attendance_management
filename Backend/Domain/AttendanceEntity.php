<?php

/**
 * 1日の勤怠を表すエンティティ。
 */

require_once(dirname(__FILE__) . '/TimeValueObject.php');

class AttendanceEntity
{
    const APPROVED = '承認済み';
    const REQUEST = '申請中';
    const UNAPPLIED = '未申請';
    private $attendanceId; // 勤怠ID。
    private $employeeId; // 従業員ID。
    private $workingDay; // 勤務日。
    private $dayOfTheWeek; // 曜日。
    private $status; // ステータス。
    private $startTime; // 始業時刻。
    private $finishTime; // 終業時刻。
    private $breakTime; // 休憩時間。
    private $workingHours; // 勤務時間。
    private $overtime; // 残業時間。
    private $normalOvertime; // 普通残業。
    private $midnightOvertime; // 深夜残業。
    private $absenceHours; // 欠課時間。
    private $remarks; // 備考。

    public function __construct(array $attendance)
    {
        $this->attendanceId = $attendance['attendanceId'];
        $this->employeeId = $attendance['employeeId'];
        $this->workingDay = $attendance['workingDay'];
        $this->dayOfTheWeek = $attendance['dayOfTheWeek'];
        $this->status = $attendance['status'];
        $this->startTime = new TimeValueObject($attendance['startTime']);
        $this->finishTime = new TimeValueObject($attendance['finishTime']);
        $this->breakTime = new TimeValueObject($attendance['breakTime']);
        $this->workingHours = new TimeValueObject($attendance['workingHours']);
        $this->overtime = new TimeValueObject($attendance['overtime']);
        $this->normalOvertime = new TimeValueObject($attendance['normalOvertime']);
        $this->midnightOvertime = new TimeValueObject($attendance['midnightOvertime']);
        $this->absenceHours = new TimeValueObject($attendance['absenceHours']);
        $this->remarks = $attendance['remarks'];
    }

    /**
     * 勤怠IDのゲッター。
     * @return int 勤怠ID。
     */
    public function getAttendanceId()
    {
        return $this->attendanceId;
    }

    /**
     * 従業員IDのゲッター。
     * @return int 従業員ID。
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * 勤務日のゲッター。
     * @return string 勤務日。
     */
    public function getWorkingDay()
    {
        return $this->workingDay;
    }

    /**
     * 曜日のゲッター。
     * @return string 曜日。
     */
    public function getDayOfTheWeek()
    {
        return $this->dayOfTheWeek;
    }

    /**
     * ステータスのゲッター。
     * @return string ステータス。
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 始業時刻のゲッター。
     * @return TimeValueObject 始業時刻。
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * 終業時刻のゲッター。
     * @return TimeValueObject 終業時刻。
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * 休憩時間のゲッター。
     * @return TimeValueObject 休憩時間。
     */
    public function getBreakTime()
    {
        return $this->breakTime;
    }

    /**
     * 勤務時間のゲッター。
     * @return TimeValueObject 勤務時間。
     */
    public function getWorkingHours()
    {
        return $this->workingHours;
    }

    /**
     * 残業時間のゲッター。
     * @return TimeValueObject 残業時間。
     */
    public function getOvertime()
    {
        return $this->overtime;
    }

    /**
     * 普通残業のゲッター。
     * @return TimeValueObject 普通残業。
     */
    public function getNormalOvertime()
    {
        return $this->normalOvertime;
    }

    /**
     * 深夜残業のゲッター。
     * @return TimeValueObject 深夜残業。
     */
    public function getMidnightOvertime()
    {
        return $this->midnightOvertime;
    }

    /**
     * 欠課時間のゲッター。
     * @return TimeValueObject 欠課時間。
     */
    public function getAbsenceHours()
    {
        return $this->absenceHours;
    }

    /**
     * 備考のゲッター。
     * @return string 備考。
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * 承認を行う。
     * @return null
     */
    public function approve()
    {
        $this->status = self::APPROVED;
    }

    /**
     * 申請を行う。
     * @return null
     */
    public function request()
    {
        $this->status = self::REQUEST;
    }

    /**
     * 取り下げを行う。
     * @return null
     */
    public function cancel()
    {
        $this->status = self::UNAPPLIED;
    }

    /**
     * 承認済みかどうか
     * @return bool 承認済みの場合true、それ以外はfalse。
     */
    public function isApproved()
    {
        return $this->status === self::APPROVED;
    }

    /**
     * 申請中かどうか
     * @return bool 申請中の場合true、それ以外はfalse。
     */
    public function isRequest()
    {
        return $this->status === self::REQUEST;
    }

    /**
     * 未申請かどうか
     * @return bool 未申請の場合true、それ以外はfalse。
     */
    public function isUnapplied()
    {
        return $this->status === self::UNAPPLIED;
    }

    /**
     * 土曜日かどうか
     * @return bool 土曜日の場合true、それ以外はfalse。
     */
    public function isSaturday()
    {
        return $this->dayOfTheWeek === '土';
    }

    /**
     * 日曜日かどうか
     * @return bool 日曜日の場合true、それ以外はfalse。
     */
    public function isSunday()
    {
        return $this->dayOfTheWeek === '日';
    }
}
