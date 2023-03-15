<?php

namespace koujigenba_php\backend\lib;

class Session
{
    public $session_key = '';
    public $db = NULL;

    public function __construct($db)
    {
        // セッションをスタートする
        session_start();
        // セッションIDを取得する
        $this->session_key = session_id();
        // DBの登録
        $this->db = $db;
    }

    public function regist($dataArr)
    {
        $dataArr['password_hash'] = password_hash($dataArr['password'], PASSWORD_DEFAULT);
        $columnKey = 'last_name, first_name, email, password, created_at, updated_at';
        $columnVal = "'"
                 . $dataArr['last_name'] . "', '"
                 . $dataArr['first_name'] . "', '"
                 . $dataArr['email'] . "', '"
                 . $dataArr['password_hash'] . "', "
                 . 'NOW()' . ", "
                 . 'NOW()';
        $table = ' users ';

        $res = $this->db->insert($table, $columnKey, $columnVal);

        return $res;
    }
}
