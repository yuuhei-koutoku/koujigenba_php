<?php

namespace koujigenba_php\backend\auth;

if (isset($_POST['logout']) === true) {
    $template = 'list.html.twig';

    unset($_POST['logout']);
    $user_id = $_SESSION['user_id'];
    $session_key = $_SESSION['session_key'];
    // sessionsテーブルのデータを削除
    $logout_result = $auth->logout($user_id, $session_key);

    if ($logout_result === true) {
        // セッションの値を削除
        $_SESSION = [
            'res' => false,
            'user_id' => 0,
            'session_key' => ''
        ];
        $success_message = 'ログアウトしました。';
    } else {
        $error_message = 'ログアウトできませんでした。';
    }
}
