<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\validation\Regist;
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

// 記事一覧データを取得
$articleArr = $article->getArticle();

$session->checkSession();

$registArr = [];
$registErrArr = [];
$loginArr = [];
$loginErrArr = [];

$success_message = '';
$error_message = '';

$template = 'list.html.twig';

if ($_SESSION['res'] === true) {
    // セッションがある場合

    // ログアウト
    if (isset($_POST['logout']) === true) {
        unset($_POST['logout']);
        $user_id = $_SESSION['user_id'];
        $session_key = $_SESSION['session_key'];
        $session_result = $session->logout($user_id, $session_key);
        if ($session_result === true) {
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
} else {
    // セッションがない場合

    $err_check = false;

    // アカウント登録
    if (isset($_POST['regist']) === true) {
        // アカウント登録からのPOST通信がある場合は、regist.html.twigを表示する
        $template = 'regist.html.twig';

        unset($_POST['regist']);
        $registArr = $_POST;

        $validation_regist = new Regist();
        // 入力内容に不備があれば、エラーメッセージを配列で取得
        $registErrArr = $validation_regist->errorCheck($registArr, $session);
        // エラーメッセージがなければtrue、エラーメッセージがあればfalse
        $err_check = $validation_regist->getErrorFlg();

        // アカウント登録入力内容保存
        if ($err_check === true) {
            // $_POSTの値をもとに、usersテーブルにデータを挿入
            $regist_result = $session->regist($registArr);
            if ($regist_result === true) {
                $email = $registArr['email'];
                // user_idを取得
                $user_id = $session->getUserId($email);
                // sessionsテーブルにデータを挿入
                $_SESSION = $session->insertSession($user_id);
                if ($_SESSION['res'] = true) {
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

    // ログイン
    if (isset($_POST['login']) === true) {
        // ログインからのPOST通信がある場合は、login.html.twigを表示する
        $template = 'login.html.twig';

        unset($_POST['login']);
        $loginArr = $_POST;

        $validation_login = new Login();
        $loginErrArr = $validation_login->errorCheck($loginArr, $session);
        $err_check = $validation_login->getErrorFlg();

        if ($err_check === true) {
            // user_idを取得
            $user_id = $session->getUserId($loginArr['email']);
            // sessionsテーブルにデータを挿入
            $_SESSION = $session->insertSession($user_id);
            if ($_SESSION['res'] = true) {
                $template = 'list.html.twig';
                $success_message = 'ログインに成功しました。';
            } else {
                $error_message = 'ログインに失敗しました。';
            }
        } else {
            $error_message = 'ログインに失敗しました。';
        }
    }
}

$context = [];
$context['registArr'] = $registArr;
$context['registErrArr'] = $registErrArr;
$context['loginArr'] = $loginArr;
$context['loginErrArr'] = $loginErrArr;
$context['articleArr'] = $articleArr;
$context['session'] = $_SESSION;
$context['success_message'] = $success_message;
$context['error_message'] = $error_message;
$template = $twig->loadTemplate($template);
$template->display($context);
