<?php

/**
 * 勤怠のアプリケーションクラス。
 * 勤怠に関するユースケースを記述するクラス。
 */

require_once(dirname(__FILE__) . '/../Repository/AttendanceRepository.php');
require_once(dirname(__FILE__) . '/../Repository/MysqlRepository.php');

class AttendanceApplication
{
    const SUCCESS_CODE = 0; // 成功時コード。
    const ERROR_CODE = 1; // エラーコード。
    private $attendanceRepository; // 勤怠リポジトリ。

    public function __construct()
    {
        $mysqlRepository = new MysqlRepository();
        $this->attendanceRepository = new AttendanceRepository($mysqlRepository->getPdo());
    }

    /**
     * 従業員IDと日付から、その従業員の勤怠のリストを取得する。
     * @param int $employeeId 従業員ID。
     * @param string $date 日付。
     * @return array 従業員IDと日付に紐づく勤怠エンティティのリスト。
     */
    public function findAttendanceFromEmployeeIdAndDate(int $employeeId, string $date)
    {
        return $this->attendanceRepository->findAttendanceFromEmployeeIdAndDate($employeeId, $date);
    }

    /**
     * 勤怠エンティティのリストから、合計勤怠バリューオブジェクトを取得する。
     * @param array $attendanceEntities 勤怠エンティティのリスト。
     * @return TotalAttendanceValueObject 勤怠エンティティのリストから作成した、合計勤怠バリューオブジェクト。
     */
    public function findTotalAttendance(array $attendanceEntities)
    {
        return $this->attendanceRepository->findTotalAttendance($attendanceEntities);
    }

    /**
     * 従業員のリストから、合計勤怠バリューオブジェクトを作成する。
     * @param array $employeeEntities 従業員のリスト。
     * @param $date 日付。
     * @return TotalAttendanceValueObject 従業員のリストと日付から作成した、合計勤怠バリューオブジェクト。
     */
    public function findTotalAttendanceFromEmployeeEntities(array $employeeEntities, string $date)
    {
        $totalEmployeeEntities = [];
        foreach ($employeeEntities as $employeeEntity) {
            $attendanceEntities = $this->findAttendanceFromEmployeeIdAndDate($employeeEntity->getEmployeeId(), $date);
            $totalEmployeeEntities = array_merge($totalEmployeeEntities, $attendanceEntities);
        }

        return $this->findTotalAttendance($totalEmployeeEntities);
    }

    /**
     * 勤怠IDを取得し、承認を行う。
     * @param int $attendanceId 勤怠ID。
     * @return int エラーコード。
     */
    public function approve(int $attendanceId)
    {
        $attendanceEntity = $this->attendanceRepository->findAttendanceFromAttendanceId($attendanceId);
        if ($attendanceEntity === false) {
            return self::ERROR_CODE;
        }

        $attendanceEntity->approve();
        $errorCode = $this->attendanceRepository->update($attendanceEntity);

        return $errorCode;
    }

    /**
     * 勤怠IDのリストを取得し、一括承認を行う。
     * @param array $attendanceIds 勤怠IDのリスト。
     * @return int エラーコード。
     */
    public function bulkApprove(array $attendanceIds)
    {
        $errorCode = self::SUCCESS_CODE;

        foreach ($attendanceIds as $attendanceId) {
            $errorCode = $this->approve($attendanceId);
            if ((int)$errorCode !== self::SUCCESS_CODE) {
                return $errorCode;
            }
        }

        return $errorCode;
    }

    /**
     * 勤怠IDを取得し、申請を行う。
     * @param int $attendanceId 勤怠ID。
     * @return int エラーコード。
     */
    public function request(int $attendanceId)
    {
        $attendanceEntity = $this->attendanceRepository->findAttendanceFromAttendanceId($attendanceId);
        if ($attendanceEntity === false) {
            self::ERROR_CODE;
        }

        $attendanceEntity->request();
        $errorCode = $this->attendanceRepository->update($attendanceEntity);

        return $errorCode;
    }

    /**
     * 勤怠IDのリストを取得し、一括申請を行う。
     * @param array $attendanceIds 勤怠IDのリスト。
     * @return int エラーコード。
     */
    public function bulkRequest(array $attendanceIds)
    {
        $errorCode = self::SUCCESS_CODE;

        foreach ($attendanceIds as $attendanceId) {
            $errorCode = $this->request($attendanceId);
            if ((int)$errorCode !== self::SUCCESS_CODE) {
                return $errorCode;
            }
        }

        return $errorCode;
    }

    /**
     * 勤怠IDを取得し、キャンセルを行う。
     * @param int $attendanceId 勤怠ID。
     * @return int エラーコード。
     */
    public function cancel(int $attendanceId)
    {
        $attendanceEntity = $this->attendanceRepository->findAttendanceFromAttendanceId($attendanceId);
        if ($attendanceEntity === false) {
            return self::ERROR_CODE;
        }

        $attendanceEntity->cancel();
        $errorCode = $this->attendanceRepository->update($attendanceEntity);

        return $errorCode;
    }

    /**
     * 勤怠IDのリストを取得し、一括キャンセルを行う。
     * @param array $attendanceIds 勤怠IDのリスト。
     * @return int エラーコード。
     */
    public function bulkCancel(array $attendanceIds)
    {
        $errorCode = self::SUCCESS_CODE;

        foreach ($attendanceIds as $attendanceId) {
            $errorCode = $this->cancel($attendanceId);
            if ((int)$errorCode !== self::SUCCESS_CODE) {
                return $errorCode;
            }
        }

        return $errorCode;
    }

    /**
     * 引数の勤怠によって、勤怠を更新する。
     * @param array $attendance 勤怠。
     * @return int エラーコード。
     */
    public function update(array $attendance)
    {
        $attendanceEntity = new AttendanceEntity($attendance);
        $errorCode = $this->attendanceRepository->update($attendanceEntity);

        return $errorCode;
    }

    /**
     * 引数の従業員のリストから、承認済みの従業員の数を返す。
     * @param array $employeeEntities 従業員のリスト。
     * @param date 日付。
     * @return 承認済みの従業員の数。
     */
    public function getTotalApprovalNumber(array $employeeEntities, string $date)
    {
        $approved = 0;
        foreach ($employeeEntities as $employeeEntity) {
            $attendanceEntities = $this->findAttendanceFromEmployeeIdAndDate($employeeEntity->getEmployeeId(), $date);
            $totalAttendanceValueObject = $this->findTotalAttendance($attendanceEntities);
            if ($totalAttendanceValueObject->isApproved()) {
                $approved++;
            }
        }

        return $approved;
    }

    /**
     * 引数の従業員のリストから、申請中の従業員の数を返す。
     * @param array $employeeEntities 従業員のリスト。
     * @param date 日付。
     * @return 申請中の従業員の数。
     */
    public function getTotalRequestNumber(array $employeeEntities, string $date)
    {
        $request = 0;
        foreach ($employeeEntities as $employeeEntity) {
            $attendanceEntities = $this->findAttendanceFromEmployeeIdAndDate($employeeEntity->getEmployeeId(), $date);
            $totalAttendanceValueObject = $this->findTotalAttendance($attendanceEntities);
            if ($totalAttendanceValueObject->isRequest()) {
                $request++;
            }
        }

        return $request;
    }

    /**
     * 引数の従業員のリストから、未申請の従業員の数を返す。
     * @param array $employeeEntities 従業員のリスト。
     * @param date 日付。
     * @return 未申請の従業員の数。
     */
    public function getTotalUnappliedNumber(array $employeeEntities, string $date)
    {
        $unapplied = 0;
        foreach ($employeeEntities as $employeeEntity) {
            $attendanceEntities = $this->findAttendanceFromEmployeeIdAndDate($employeeEntity->getEmployeeId(), $date);
            $totalAttendanceValueObject = $this->findTotalAttendance($attendanceEntities);
            if ($totalAttendanceValueObject->isUnapplied()) {
                $unapplied++;
            }
        }

        return $unapplied;
    }
}
