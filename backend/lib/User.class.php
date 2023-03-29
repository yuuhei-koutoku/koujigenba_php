<?php

namespace koujigenba_php\backend\lib;

class User
{
    public $db = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserInfo($user_id = '')
    {
        $table = ' users ';
        $where = ($user_id !== '') ? ' id = ' . $user_id : '';

        $res = $this->db->select($table, '', $where);

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

    public function updatePassword($id, $password) {
        $table = ' users ';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $passwordSet = " password = '" . $password_hash . "'";
        $where = ' id =' . $id;

        $res = $this->db->update($table, $passwordSet, $where);

        return $res;
    }
}
