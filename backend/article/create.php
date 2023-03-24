<?php

namespace koujigenba_php\backend\article;

use koujigenba_php\backend\lib\Article;
use koujigenba_php\backend\validation\Submit;
use koujigenba_php\backend\validation\Image;

if (isset($_POST['create']) === true) {
    $template = 'create.html.twig';

    unset($_POST['create']);
    $createArr = $_POST;

    $submit = new Submit;
    // 入力内容に不備があれば、エラーメッセージを配列で取得
    $submitErrArr = $submit->errorCheck($createArr);
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
        // user_idを取得
        $user_id = $_SESSION['user_id'];
        // 添付画像があればファイル名を指定、添付画像がなければ空文字
        $image_name = ($tmp_image['size'] !== 0) ? 'upload_' . time() . '_' . $tmp_image['name'] : '';

        $article = new Article($db);
        // articlesテーブルにデータを挿入
        $insert_article_result = $article->insertArticle($createArr, $user_id, $image_name);
        if ($insert_article_result === true) {
            $success_message = '記事を正常に投稿しました。';
            // 正常に投稿できたら、list.html.twigを表示
            $template = 'list.html.twig';
            // 添付画像があれば、/backend/images/upload/に画像を保存
            if ($tmp_image['size'] !== 0) move_uploaded_file($tmp_image['tmp_name'], './images/upload/' . $image_name);
        } else {
            $error_message = '記事の投稿に失敗しました。';
        }
    } else {
        $error_message = '記事の投稿に失敗しました。';
    }
}
