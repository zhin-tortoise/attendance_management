<?php

class MysqlRepository
{
    // const ENVIRONMENT = 'production'; // 商用環境で使用する際に使用する変数。
    const ENVIRONMENT = 'develop'; // 開発環境で使用する際に使用する変数。
    private $pdo; // DBアクセスを行うPDOクラス。

    /**
     * コンストラクタでPDOの作成を行う。
     */
    public function __construct()
    {
        $environmentFile = dirname(__FILE__) . '/Mysql.json';
        $environment = json_decode(file_get_contents($environmentFile), true)[self::ENVIRONMENT];
        $this->pdo = new PDO("mysql:dbname=$environment[name];host=$environment[host];", $environment['user'], $environment['password']);
    }

    /**
     * PDOのゲッター。
     * @return PDO 接続が確立されたpdoクラス。
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
