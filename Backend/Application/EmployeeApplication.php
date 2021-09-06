<?php

/**
 * 従業員のアプリケーションクラス。
 * 従業員周りのユースケースを記載するクラス。
 */

require_once(dirname(__FILE__) . '/../Repository/EmployeeRepository.php');
require_once(dirname(__FILE__) . '/../Repository/MysqlRepository.php');

class EmployeeApplication
{
    private $employeeRepository; // 従業員リポジトリ

    public function __construct()
    {
        $mysqlRepository = new MysqlRepository();
        $this->employeeRepository = new EmployeeRepository($mysqlRepository->getPdo());
    }

    /**
     * 承認者IDからチームに所属する従業員のリストを返す。
     * @param int $authorizerId 承認者ID。
     * @return array 従業員のリスト。
     */
    public function findEmployeeOfTeam(int $authorizerId)
    {
        return $this->employeeRepository->findEmployeeOfTeam($authorizerId);
    }

    /**
     * 従業員のログインを行う。
     * @param string $mailAddress メールアドレス。
     * @param string $password パスワード。
     * @return bool ログイン成功時にはtrue、失敗時にはfalseを返す。
     */
    public function employeeLogin(string $mailAddress, string $password)
    {
        $employeeEntity = $this->employeeRepository->findEmployeeFromMailAddressAndPassword($mailAddress, $password);
        if ($employeeEntity) {
            $_SESSION['employeeId'] = $employeeEntity->getEmployeeId();
        }

        return !empty($employeeEntity);
    }

    /**
     * 従業員のログアウトを行う。
     * @return null
     */
    public function employeeLogout()
    {
        unset($_SESSION['employeeId']);
    }

    /**
     * 承認者のログインを行う。
     * @param string $mailAddress メールアドレス。
     * @param string $password パスワード。
     * @return bool ログイン成功時にはtrue、失敗時にはfalseを返す。
     */
    public function authorizerLogin(string $mailAddress, string $password)
    {
        $employeeEntity = $this->employeeRepository->findAuthorizerFromMailAddressAndPassword($mailAddress, $password);
        if ($employeeEntity) {
            $_SESSION['authorizerId'] = $employeeEntity->getEmployeeId();
        }

        return !empty($employeeEntity);
    }

    /**
     * 承認者のログアウトを行う。
     * @return null
     */
    public function authorizerLogout()
    {
        unset($_SESSION['authorizerId']);
    }
}
