<?php

/**
 * 従業員のリポジトリ。
 * 従業員テーブルへのアクセスを担う。
 */

require_once(dirname(__FILE__) . '/IEmployeeRepository.php');
require_once(dirname(__FILE__) . '/../Domain/EmployeeEntity.php');

class EmployeeRepository implements IEmployeeRepository
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
     * メールアドレスとパスワードを引数から取得し、それらから従業員を読み取り、従業員エンティティを返す。
     * @param string $mailAddress メールアドレス。
     * @param string $password パスワード。
     * @return EmployeeEntity|false 引数で与えられたメールアドレスに紐づく従業員エンティティ。
     */
    public function findEmployeeFromMailAddressAndPassword(string $mailAddress, string $password)
    {
        $sql = 'select employee_id as employeeId, name, mail_address as mailAddress, password ';
        $sql .= 'from employee where mail_address = :mail_address';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':mail_address', $mailAddress);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row) && password_verify($password, $row['password'])) {
            return new EmployeeEntity($row);
        } else {
            return false;
        }
    }

    /**
     * メールアドレスとパスワードを引数から取得し、それらから承認者を読み取り、従業員エンティティを返す。
     * @param string $mailAddress メールアドレス。
     * @param string $password パスワード。
     * @return EmployeeEntity|false 引数で与えられたメールアドレスに紐づく従業員エンティティ。
     */
    public function findAuthorizerFromMailAddressAndPassword(string $mailAddress, string $password)
    {
        $sql = 'select employee.employee_id as employeeId, name, mail_address as mailAddress, password ';
        $sql .= 'from employee, team ';
        $sql .= 'where employee.employee_id = team.authorizer_id ';
        $sql .= 'and mail_address = :mail_address ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':mail_address', $mailAddress);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row) && password_verify($password, $row['password'])) {
            return new EmployeeEntity($row);
        } else {
            return false;
        }
    }

    /**
     * 承認者IDえお引数から取得し、その承認者のチームに含まれる従業員エンティティのリストを返す。
     * @param int $authorizerId 承認者ID。
     * @return array 従業員エンティティのリスト。
     */
    public function findEmployeeOfTeam(int $authorizerId)
    {
        $sql = 'select employee.employee_id as employeeId, name, mail_address as mailAddress, password ';
        $sql .= 'from employee, team ';
        $sql .= 'where employee.employee_id = team.employee_id ';
        $sql .= 'and team.authorizer_id = :authorizer_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':authorizer_id', $authorizerId);
        $stmt->execute();

        $employeeEntities = [];
        foreach ($stmt->fetchAll() as $employee) {
            $employeeEntities[] = new EmployeeEntity($employee);
        }

        return $employeeEntities;
    }
}
