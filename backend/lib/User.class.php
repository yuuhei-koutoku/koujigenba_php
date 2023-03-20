<?php

namespace koujigenba_php\backend\lib;

class User
{
    public $db = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserId($email)
    {
        $columnKey = ' id ';
        $table = ' users ';
        $where = " email = '" . $email . "'";

        $res = $this->db->select($table, $columnKey, $where);

        return $res[0]['id'];
    }

    public function checkEmail($email)
    {
        $table = ' users ';
        $where = " email = '" . $email . "'";

        $check_email = $this->db->select($table, '', $where);
        $res = ($check_email === []) ? true : false;

        return $res;
    }

    public function getPassword($email)
    {
        $table = ' users ';
        $columnKey = ' password ';
        $where = " email = '" . $email . "'";

        $res = $this->db->select($table, $columnKey, $where);

        return $res;
    }
}
