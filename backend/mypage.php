<?php

namespace koujigenba_php\backend;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\lib\Session;
use koujigenba_php\backend\lib\User;
use koujigenba_php\backend\validation\UserUpdate;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$session = new Session($db);
$user = new User($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$success_message = '';
$error_message = '';

$dataArr = [
    'email' => '',
    'password' => ''
];
$errArr = [
    'email' => '',
    'password' => [
        'current_password' => '',
        'new_password' => '',
        'new_password_confirmation' => ''
    ]
];

$session->checkSession();

$template = 'mypage.html.twig';

if ($_SESSION['res'] === false) {
    // セッションがなければ、list.phpへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
} else {
    // セッションがあれば、マイページ（ログインしたユーザーの情報）を出力

    // ログインユーザーの情報を取得
    require_once './auth/user_info.php';

    // メールアドレス変更
    if (isset($_POST['email_update']) === true) {
        $dataArr['email'] = $_POST['email'];
        $user_update = new UserUpdate();
        $errArr['email'] = $user_update->emailCheck($user_info['email'], $dataArr['email'], $user);
        if ($errArr['email'] === '') {
            $user->updateEmail($user_info['id'], $dataArr['email']);
            $success_message = 'メールアドレスの更新に成功しました。';
            $user_info['email'] = $dataArr['email'];
            $dataArr['email'] = '';
        } else {
            $error_message = 'メールアドレスの更新に失敗しました。';
        }
    }

    // パスワード変更
    if (isset($_POST['password_update']) === true) {
        $dataArr['password'] = [
            'current_password' => $_POST['current_password'],
            'new_password' => $_POST['new_password'],
            'new_password_confirmation' => $_POST['new_password_confirmation']
        ];
        $user_update = new UserUpdate();
        $errArr['password'] = $user_update->passwordCheck($user_info['password'],
                                                          $dataArr['password']['current_password'],
                                                          $dataArr['password']['new_password'],
                                                          $dataArr['password']['new_password_confirmation'],
                                                          $user);
        if ($errArr['password']['current_password'] === '' &&
            $errArr['password']['new_password'] === '' &&
            $errArr['password']['new_password_confirmation'] === '') {
            $user->updatePassword($user_info['id'], $dataArr['password']['new_password']);
            $success_message = 'パスワードの更新に更新に成功しました。';
            $dataArr['password'] = [
                'current_password' => '',
                'new_password' => '',
                'new_password_confirmation' => ''
            ];
        } else {
            $error_message = 'パスワードの更新に失敗しました。';
        }
    }
}

$context = [];

$context['user_info'] = $user_info;

$context['dataArr']['email'] = $dataArr['email'];
$context['dataArr']['password'] = $dataArr['password'];

$context['session'] = $_SESSION;

$context['success_message'] = $success_message;
$context['error_message'] = $error_message;

$context['errArr']['email'] = $errArr['email'];
$context['errArr']['password'] = $errArr['password'];

$template = $twig->loadTemplate($template);
$template->display($context);
