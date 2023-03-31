<?php

namespace koujigenba_php\backend\auth;

// 退会機能
if (isset($_POST['delete_account']) === true) {
    $user_id = $_SESSION['user_id'];
    $session_key = $_SESSION['session_key'];

    // sessionsテーブルのデータを削除
    $logout_result = $auth->logout($user_id, $session_key);

    if ($logout_result === true) {
        // usersテーブルのdelete_flgカラムを1にする
        $user->updateDeleteFlg($_SESSION['user_id']);

        // セッションの値を削除
        $_SESSION = [
            'res' => false,
            'user_id' => 0,
            'session_key' => ''
        ];

        $success_message = '退会しました。';
    }
}
