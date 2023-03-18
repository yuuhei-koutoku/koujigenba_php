<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\validation\Regist;
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

$template = 'regist.html.twig';

if ($_SESSION['res'] === true) {
    // セッションがある場合

    $template = 'list.html.twig';

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
            echo 'ログアウトしました。';
        }
    }
} else {
    // セッションがない場合

    // アカウント登録入力内容チェック
    $err_check = false;
    if (isset($_POST['regist']) === true) {
        unset($_POST['regist']);
        $registArr = $_POST;

        $validation_regist = new Regist();
        // 入力内容に不備があれば、エラーメッセージを配列で取得
        $registErrArr = $validation_regist->errorCheck($registArr);
        // エラーメッセージがなければtrue、エラーメッセージがあればfalse
        $err_check = $validation_regist->getErrorFlg();

        // アカウント登録入力内容保存
        if ($err_check === true) {
            // $_POSTの値をもとに、usersテーブルにデータを挿入
            $regist_result = $session->regist($registArr);
            if ($regist_result === true) {
                // user_idを取得
                $user_id = $session->getUserId($registArr['email']);
                // sessionsテーブルにデータを挿入
                $_SESSION = $session->insertSession($user_id);
                if ($_SESSION['res'] = true) {
                    $template = 'list.html.twig';
                    echo 'アカウントの登録に成功しました。';
                } else {
                    echo 'アカウントの登録に失敗しました。';
                }
            } else {
                echo 'アカウントの登録に失敗しました。';
            }
        }
    }
}

$context['registArr'] = $registArr;
$context['registErrArr'] = $registErrArr;
$context['articleArr'] = $articleArr;
$context['session'] = $_SESSION;
$template = $twig->loadTemplate($template);
$template->display($context);
