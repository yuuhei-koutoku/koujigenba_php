<?php

namespace koujigenba_php\backend\lib;

class Article
{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getArticle($where = '')
    {
        $table = ' articles ';
        $columnKey = ' articles.id, image, title, content, user_id, last_name, first_name, articles.created_at ';
        $join = ' JOIN users ON articles.user_id = users.id ';
        $where = ($where !== '') ? ' articles.id = ' . $where : '';

        return $this->db->select($table, $columnKey, $where, [], $join);
    }

    public function insertArticle($dataArr, $user_id, $image_name)
    {
        $table = ' articles ';
        $columnKey = 'image, title, content, user_id';
        $columnVal = "'"
                   . $image_name . "', '"
                   . $dataArr['title'] . "', '"
                   . $dataArr['content'] . "', "
                   . $user_id;

        $res = $this->db->insert($table, $columnKey, $columnVal);

        return $res;
    }
}
