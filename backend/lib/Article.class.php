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
        $columnKey = ' articles.id, image, title, content, user_id, last_name, first_name, delete_flg, articles.created_at ';
        $join = ' JOIN users ON articles.user_id = users.id ';
        $where = ($where !== '') ? ' articles.id = ' . $where : '';

        return $this->db->select($table, $columnKey, $where, [], $join);
    }

    public function insertArticle($createArr, $user_id, $image_name)
    {
        $table = ' articles ';
        $columnKey = 'image, title, content, user_id';
        $columnVal = "'"
                   . $image_name . "', '"
                   . $createArr['title'] . "', '"
                   . $createArr['content'] . "', "
                   . $user_id;

        $res = $this->db->insert($table, $columnKey, $columnVal);

        return $res;
    }

    public function updateArticle($editArr, $article_id, $image_name)
    {
        $table = ' articles ';
        $titleSet = "title = '" . $editArr['title'] . "'";
        $contentSet = "content = '" . $editArr['content'] . "'";
        $imageSet = "image = '" . $image_name . "'";
        $where = ' id = ' . $article_id;

        if ($image_name === '') {
            $setArr = [$titleSet, $contentSet];
            $value = implode(', ' , $setArr);
        } else {
            $setArr = [$titleSet, $contentSet, $imageSet];
            $value = implode(', ' , $setArr);
        }

        $res = $this->db->update($table, $value, $where);

        return $res;
    }

    public function deleteArticle($article_id)
    {
        $table = ' articles ';
        $where = ' id = :id ';
        $whereArr = [
            'id' => $article_id
        ];

        $article_delete = $this->db->delete($table, $where, $whereArr);
        $article_select = $this->db->select($table, '', $where, $whereArr);

        $res = ($article_delete === true && $article_select === []) ? true : false;

        return $res;
    }
}
