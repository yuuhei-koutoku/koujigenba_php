<?php

namespace koujigenba_php\backend\lib;

class Article
{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getArticle()
    {
        $table = ' articles ';

        return $this->db->select($table);
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
