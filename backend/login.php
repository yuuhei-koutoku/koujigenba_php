<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\validation\Login;
use koujigenba_php\backend\lib\Session;
use koujigenba_php\backend\lib\Article;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$article = new Article($db);
$session = new Session($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$loginArr = [];
$loginErrArr = [];

$err_check = false;
if (isset($_POST['login']) === true) {
    unset($_POST['login']);
    $loginArr = $_POST;

    $validation_login = new Login();
    $loginErrArr = $validation_login->errorCheck($loginArr);
    $err_check = $validation_login->getErrorFlg();

    if ($err_check === true) {
        // ログイン認証
        $login_result = $session->login($loginArr);
        if ($login_result === true) {
            // user_idを取得
            $user_id = $session->getUserId($loginArr['email']);
            // sessionsテーブルにデータを挿入
            $_SESSION = $session->insertSession($user_id);
            if ($_SESSION['res'] = true) {
                $template = 'list.html.twig';
                echo 'ログインに成功しました。';
            } else {
                echo 'ログインに失敗しました。';
            }
        } else {
            echo 'ログインに失敗しました。';
        }
    }
}

$context = [];
$context['loginArr'] = $loginArr;
$context['loginErrArr'] = $loginErrArr;
$template = $twig->loadTemplate('login.html.twig');
$template->display($context);
