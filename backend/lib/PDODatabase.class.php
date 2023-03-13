<?php

namespace koujigenba_php\backend\lib;

class PDODatabase
{
    private $dbh = null;
    private $db_host = '';
    private $db_user = '';
    private $db_pass = '';
    private $db_name = '';
    private $db_type = '';
    private $order = '';
    private $limit = '';
    private $offset = '';
    private $groupby = '';

    public function __construct($db_host, $db_user, $db_pass, $db_name, $db_type)
    {
        $this->dbh = $this->connectDB($db_host, $db_user, $db_pass, $db_name, $db_type);
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
        // SQL関連
        $this->order = '';
        $this->limit = '';
        $this->offset = '';
        $this->groupby = '';
    }

    private function connectDB($db_host, $db_user, $db_pass, $db_name, $db_type)
    {
        try { // 接続エラー発生→PDOExceptionオブジェクトがスローされる→例外処理をキャッチする
            switch ($db_type) {
                case 'mysql':
                    $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name;
                    $dbh = new \PDO($dsn, $db_user, $db_pass);
                    $dbh->query('SET NAMES utf8');
                    break;

                case 'pgsql':
                    $dsn = 'pgsql:dbname=' . $db_name . ' host=' . $db_host . ' port=5432';
                    $dbh = new \PDO($dsn, $db_user, $db_pass);
                    break;
            }
        } catch (\PDOException $e) {
            var_dump($e->getMessage());
            exit();
        }
        return $dbh;
    }

    private function getSql($type, $table, $where = '', $column = '')
    {
        switch ($type) {
            case 'insert':
                break;

            case 'select':
                $columnKey = ($column !== '') ? $column : '*';
                break;

            case 'count':
                $columnKey = 'COUNT(*) AS NUM ';
                break;

            case 'update':
                break;

            case 'delete':
                break;

            default:
                break;
        }

        $whereSQL = ($where !== '') ? ' WHERE ' . $where : '';
        $other = $this->groupby . '' . $this->order . '' . $this->limit . '' . $this->offset;

        $sql = 'SELECT ' . $columnKey . ' FROM ' . $table . $whereSQL . $other;
        return $sql;
    }

    public function insert()
    {

    }

    public function select($table, $column = '', $where = '', $arrVal = [])
    {
        $sql = $this->getSql('select', $table, $where, $column);

        $this->sqlLogInfo($sql, $arrVal);

        if ($arrVal === []) {
            $stmt = $this->dbh->query($sql);
            $res = $stmt->fetchAll();
        } else {
            $stmt = $this->dbh->prepare($sql);
            $res = $stmt->execute($arrVal);
        }

        if ($res === false) {
            $this->catchError($stmt->errorInfo());
        }

        $data = [];
        if ($arrVal === []) {
            $data = $res;
        } else {
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                array_push($data, $result);
            }
        }

        return $data;
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function setOrder($order = '')
    {
        if ($order !== '') {
            $this->order = ' ORDER BY ' . $order;
        }
    }

    public function setLimitOff($limit = '', $offset = '')
    {
        if ($limit !== '') {
            $this->limit = ' LIMIT ' . $limit;
        }
        if ($offset !== '') {
            $this->offset = ' OFFSET ' . $offset;
        }
    }

    public function setGroupBy($groupby)
    {
        if ($groupby !== '') {
            $this->groupby = ' GROUP BY ' . $groupby;
        }
    }

    private function catchError($errArr = [])
    {
        $errMsg = (!empty($errArr[2])) ? $errArr[2] : '';
        die('SQLエラーが発生しました' . $errArr[2]);
    }

    private function makeLogFile()
    {
        $logDir = dirname(__DIR__) . '/logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777);
        }
        $logPath = $logDir . '/koujigenba_php.log';
        if (!file_exists($logPath)) {
            touch($logPath);
        }
        return $logPath;
    }

    private function sqlLogInfo($str, $arrVal = [])
    {
        $logPath = $this->makeLogFile();
        $logData = sprintf("[SQL_LOG:%s]: %s [%s]\n", date('Y-m-d H:i:s'), $str, implode(",", $arrVal));
        error_log($logData, 3, $logPath);
    }
}
