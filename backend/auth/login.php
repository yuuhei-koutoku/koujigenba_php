<?php

namespace koujigenba_php\backend\auth;

use koujigenba_php\backend\validation\Login;

if (isset($_POST['login']) === true) {
    // ログインからのPOST通信がある場合は、login.html.twigを表示する
    $template = 'login.html.twig';

    unset($_POST['login']);
    $loginArr = $_POST;

    $validation_login = new Login();
    // 入力内容に不備があれば、エラーメッセージを配列で取得
    $loginErrArr = $validation_login->errorCheck($loginArr, $user);
    // エラーメッセージがなければtrue、エラーメッセージがあればfalse
    $err_check = $validation_login->getErrorFlg();

    if ($err_check === true) {
        // user_idを取得
        $user_id = $user->getUserId($loginArr['email']);
        // sessionsテーブルにデータを挿入
        $_SESSION = $session->insertSession($user_id);
        if ($_SESSION['res'] = true) {
            // ログインが成功したら、list.html.twigを表示する
            $template = 'list.html.twig';
            $success_message = 'ログインに成功しました。';
        } else {
            $error_message = 'ログインに失敗しました。';
        }
    } else {
        $error_message = 'ログインに失敗しました。';
    }
}
