<?php

namespace koujigenba_php\backend\lib;

class Auth
{
    public $db = NULL;

    public function __construct($db)
    {
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
}
