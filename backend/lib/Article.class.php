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
}
