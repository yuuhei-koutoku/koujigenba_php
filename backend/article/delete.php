<?php

namespace koujigenba_php\backend\article;

use koujigenba_php\backend\lib\Article;

if (isset($_POST['delete']) === true) {

    $article = new Article($db);
    $delete_article_result = $article->deleteArticle($_POST['article_id']);

    if ($delete_article_result === true) {
        $success_message = '記事を正常に削除しました。';
    } else {
        $error_message = '記事の削除に失敗しました。';
    }
}
