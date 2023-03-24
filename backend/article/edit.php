<?php

namespace koujigenba_php\backend\article;

use koujigenba_php\backend\lib\Article;
use koujigenba_php\backend\validation\Submit;
use koujigenba_php\backend\validation\Image;

if (isset($_POST['edit']) === true) {
    $template = 'edit.html.twig';

    unset($_POST['edit']);
    $editArr = $_POST;

    $article = new Article($db);
    $getArr = ($_GET === []) ? $article->getArticle($_POST['article_id']) : $article->getArticle($_GET['article_id']);

    $submit = new Submit;
    // 入力内容に不備があれば、エラーメッセージを配列で取得
    $submitErrArr = $submit->errorCheck($editArr);
    // エラーメッセージがなければtrue、エラーメッセージがあればfalse
    $submit_err_check = $submit->getErrorFlg();

    $image_err_check = null;
    $tmp_image = $_FILES['image'];
    // 画像が送信された場合の処理
    if ($tmp_image['size'] !== 0) {
        $image = new Image;
        $image_info = getimagesize($tmp_image['tmp_name']);
        $image_mime = $image_info['mime'];
        // 添付画像に不備があれば、エラーメッセージを配列で取得
        $imageErrArr = $image->errorCheck($tmp_image, $image_mime);
        // エラーメッセージがなければtrue、エラーメッセージがあればfalse、添付画像がなければnull
        $image_err_check = $image->getErrorFlg();
    }

    if ($submit_err_check === true && $image_err_check !== false) {
        // article_idを取得
        $article_id = $getArr[0]['id'];
        // 添付画像があればファイル名を指定、添付画像がなければ空文字
        $image_name = ($tmp_image['size'] !== 0) ? 'upload_' . time() . '_' . $tmp_image['name'] : '';

        $article = new Article($db);
        // articlesテーブルにデータを挿入
        $update_article_result = $article->updateArticle($editArr, $article_id, $image_name);
        if ($update_article_result === true) {
            $success_message = '記事を正常に更新しました。';
            // 正常に更新できたら、list.html.twigを表示
            $template = 'list.html.twig';
            // 添付画像があれば、/backend/images/upload/に画像を保存
            if ($tmp_image['size'] !== 0) move_uploaded_file($tmp_image['tmp_name'], './images/upload/' . $image_name);
        } else {
            $error_message = '記事の更新に失敗しました。';
        }
    } else {
        $error_message = '記事の更新に失敗しました。';
    }
}
