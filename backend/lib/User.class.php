<?php

namespace koujigenba_php\backend\lib;

class User
{
    public $db = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserInfo($user_id)
    {
        $table = ' users ';
        $where = ' id = ' . $user_id;

        $res = $this->db->select($table, '', $where);

        $id = $res[0]['id'];
        $last_name = $res[0]['last_name'];
        $first_name = $res[0]['first_name'];
        $email = $res[0]['email'];

        return [$id, $last_name, $first_name, $email];
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

    public function updateEmail($id, $email)
    {
        $table = ' users ';
        $emailSet = " email = '" . $email . "'";
        $where = ' id =' . $id;

        $res = $this->db->update($table, $emailSet, $where);

        return $res;
    }
}
