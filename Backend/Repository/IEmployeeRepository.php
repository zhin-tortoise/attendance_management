<?php

/**
 * 従業員リポジトリに対応するインターフェース。
 */

interface IEmployeeRepository
{
    public function findEmployeeFromMailAddressAndPassword(string $mailAddress, string $password);
    public function findAuthorizerFromMailAddressAndPassword(string $mailAddress, string $password);
    public function findEmployeeOfTeam(int $authorizerId);
}
