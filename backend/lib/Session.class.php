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

    public function login($dataArr)
    {
        $passwordArr = $this->getPassword($dataArr['email']);
        $password_hash = $passwordArr[0]['password'];

        $res = (password_verify($dataArr['password'], $password_hash)) ? true : false;

        return $res;
    }

    private function getPassword($email)
    {
        $table = ' users ';
        $columnKey = ' password ';
        $where = " email = '" . $email . "'";

        $res = $this->db->select($table, $columnKey, $where);

        return $res;
    }

    public function logout($user_id, $session_key)
    {
        $table = ' sessions ';
        $where = " user_id = :user_id AND session_key = :session_key ";
        $whereArr = [
            'user_id' => $user_id,
            'session_key' => $session_key
        ];

        $sessions_delete = $this->db->delete($table, $where, $whereArr);
        $sessions_select = $this->db->select($table, '', $where, $whereArr);

        $res = ($sessions_delete === true && $sessions_select === []) ? true : false;

        return $res;
    }

    public function getUserId($email)
    {
        $columnKey = ' id ';
        $table = ' users ';
        $where = " email = '" . $email . "'";

        $res = $this->db->select($table, $columnKey, $where);

        return $res[0]['id'];
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
