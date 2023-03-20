<?php

namespace koujigenba_php\backend\auth;

use koujigenba_php\backend\validation\Regist;

if (isset($_POST['regist']) === true) {
    // アカウント登録からのPOST通信がある場合は、regist.html.twigを表示する
    $template = 'regist.html.twig';

    unset($_POST['regist']);
    $registArr = $_POST;

    $validation_regist = new Regist();
    // 入力内容に不備があれば、エラーメッセージを配列で取得
    $registErrArr = $validation_regist->errorCheck($registArr, $auth);
    // エラーメッセージがなければtrue、エラーメッセージがあればfalse
    $err_check = $validation_regist->getErrorFlg();

    if ($err_check === true) {
        // $_POSTの値をもとに、usersテーブルにデータを挿入
        $regist_result = $auth->regist($registArr);
        if ($regist_result === true) {
            // user_idを取得
            $user_id = $auth->getUserId($registArr['email']);
            // sessionsテーブルにデータを挿入
            $_SESSION = $session->insertSession($user_id);
            if ($_SESSION['res'] = true) {
                // 入力内容が問題なく、データ挿入も正常に完了したら、list.html.twigを表示する
                $template = 'list.html.twig';
                $success_message = 'アカウントの登録に成功しました。';
            } else {
                $error_message = 'アカウントの登録に失敗しました。';
            }
        } else {
            $error_message = 'アカウントの登録に失敗しました。';
        }
    } else {
        $error_message = 'アカウントの登録に失敗しました。';
    }
}
