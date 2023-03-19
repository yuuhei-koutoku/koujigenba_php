<?php

namespace koujigenba_php\backend;

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

    // URLが http://localhost:8888/koujigenba_php/backend/list.php の場合のみ読み込む
    if ((empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] === Bootstrap::ENTRY_URL . 'backend/list.php') {
        // アカウント登録処理
        require_once './auth/regist.php';

        // ログイン処理
        require_once './auth/login.php';
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
