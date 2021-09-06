<?php

/**
 * 1つの従業員を表すエンティティ。
 */

class EmployeeEntity
{
    private $employee_id; // 従業員ID。
    private $name; // 名前。
    private $mailAddress; // メールアドレス。
    private $password; // パスワード。

    public function __construct(array $employee)
    {
        $this->employee_id = $employee['employeeId'];
        $this->name = $employee['name'];
        $this->mailAddress = $employee['mailAddress'];
        $this->password = $employee['password'];
    }

    /**
     * 従業員IDのゲッター。
     * @return int 従業員ID。
     */
    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    /**
     * 名前のゲッター。
     * @return string 名前。
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * メールアドレスのゲッター。
     * @return string メールアドレス。
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * パスワードのゲッター。
     * @return string パスワード。
     */
    public function getPassword()
    {
        return $this->password;
    }
}
