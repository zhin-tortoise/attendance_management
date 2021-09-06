<?php

/**
 * 1月の勤怠を表すエンティティ。
 */

class TotalAttendanceValueObject
{
    const APPROVED = '承認済み';
    const REQUEST = '申請中';
    const UNAPPLIED = '未申請';
    private $status; // ステータス。
    private $approvalNumber = 0; // 承認数。
    private $days = 0; // 日数。
    private $workingDays = 0; // 勤務日数。
    private $workingHours; // 勤務時間。
    private $normalOvertime; // 普通残業。
    private $midnightOvertime; // 深夜残業。
    private $absenceDays = 0; // 欠勤日数。
    private $absenceHours; // 欠課時間。

    public function __construct(array $attendanceEntities)
    {
        $this->status = self::APPROVED;
        $this->workingHours = new TimeValueObject('00:00');
        $this->normalOvertime = new TimeValueObject('00:00');
        $this->midnightOvertime = new TimeValueObject('00:00');
        $this->absenceHours = new TimeValueObject('00:00');

        foreach ($attendanceEntities as $attendanceEntity) {
            if ($attendanceEntity->isRequest()) {
                $this->status = self::REQUEST;
            } else if ($this->status === self::APPROVED && $attendanceEntity->isUnapplied()) {
                $this->status = self::UNAPPLIED;
            }

            if ($attendanceEntity->isApproved()) {
                $this->approvalNumber++;
            }

            $this->days++;

            if (!$attendanceEntity->isSaturday() && !$attendanceEntity->isSunday()) {
                $this->workingDays++;
            }
            $this->workingHours = $this->workingHours->add($attendanceEntity->getWorkingHours());
            $this->normalOvertime = $this->normalOvertime->add($attendanceEntity->getNormalOvertime());
            $this->midnightOvertime = $this->midnightOvertime->add($attendanceEntity->getMidnightOvertime());
            if ((int)$attendanceEntity->getAbsenceHours()->getHours() || (int)$attendanceEntity->getAbsenceHours()->getMinutes()) {
                $this->absenceDays += 1;
            }
            $this->absenceHours = $this->absenceHours->add($attendanceEntity->getAbsenceHours());
        }
    }

    /**
     * ステータスのgetter。
     * @return string ステータス。
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 承認数のgetter。
     * @return int 承認数。
     */
    public function getApprovalNumber()
    {
        return $this->approvalNumber;
    }

    /**
     * 日数のgetter。
     * @return int 日数。
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * 勤務日数のgetter。
     * @return int 勤務日数。
     */
    public function getWorkingDays()
    {
        return $this->workingDays;
    }

    /**
     * 勤務時間のgetter。
     * @return TimeValueObject 勤務時間。
     */
    public function getWorkingHours()
    {
        return $this->workingHours;
    }

    /**
     * 普通残業のgetter。
     * @return TimeValueObject 普通残業。
     */
    public function getNormalOvertime()
    {
        return $this->normalOvertime;
    }

    /**
     * 深夜残業のgetter。
     * @return TimeValueObject 深夜残業。
     */
    public function getMidnightOvertime()
    {
        return $this->midnightOvertime;
    }

    /**
     * 欠勤日数のgetter。
     * @return int 欠勤日数。
     */
    public function getAbsenceDays()
    {
        return $this->absenceDays;
    }

    /**
     * 欠課時間のgetter。
     * @return TimeValueObject 欠課時間。
     */
    public function getAbsenceHours()
    {
        return $this->absenceHours;
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
}
