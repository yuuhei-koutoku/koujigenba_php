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

    public function insertSession($user_id)
    {
        $table = ' sessions ';
        $columnKey = 'user_id, session_key';
        $columnVal = $user_id . ", '" . $this->session_key . "'";

        $res = $this->db->insert($table, $columnKey, $columnVal);

        if ($res === true) {
            $_SESSION = [
                'res' => true,
                'user_id' => $user_id,
                'session_key' => $this->session_key
            ];
        } else {
            $_SESSION = [
                'res' => false,
                'user_id' => 0,
                'session_key' => ''
            ];
        }

        return $_SESSION;
    }

    public function checkSession()
    {
        $table = ' sessions ';
        $where = " session_key = '" . $this->session_key . "'";

        $res = $this->db->select($table, '', $where);

        if ($res !== []) {
            $_SESSION = [
                'res' => true,
                'user_id' => $res[0]['user_id'],
                'session_key' => $this->session_key
            ];
        } else {
            $_SESSION = [
                'res' => false,
                'user_id' => 0,
                'session_key' => ''
            ];
        }

        return $_SESSION;
    }
}
