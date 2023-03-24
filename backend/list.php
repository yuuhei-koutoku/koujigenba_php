<?php

namespace koujigenba_php\backend;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\lib\Article;
use koujigenba_php\backend\lib\Auth;
use koujigenba_php\backend\lib\Session;
use koujigenba_php\backend\lib\User;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$article = new Article($db);
$auth = new Auth($db);
$session = new Session($db);
$user = new User($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$session->checkSession();

$registArr = [];
$registErrArr = [];

$loginArr = [];
$loginErrArr = [];

$createArr = [];
$editArr = [];
$submitErrArr = [];
$submitErrArr = [];

$success_message = '';
$error_message = '';

$template = 'list.html.twig';

if ($_SESSION['res'] === true) {
    // セッションがある場合

    // 記事投稿処理
    if ((empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] === Bootstrap::ENTRY_URL) {
        // URLが http://localhost:8888/koujigenba_php/ の場合

        // 記事削除処理
        require_once './backend/article/delete.php';

        // ログアウト処理
        require_once './backend/auth/logout.php';
    } else {
        // URLが http://localhost:8888/koujigenba_php/backend/list.php の場合

        // 記事投稿処理
        require_once './article/create.php';

        // 記事編集処理
        require_once './article/edit.php';

        // 記事削除処理
        require_once './article/delete.php';

        // ログアウト処理
        require_once './auth/logout.php';
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

// 記事一覧データを取得
$listArr = $article->getArticle();

$context = [];

$context['registArr'] = $registArr;
$context['registErrArr'] = $registErrArr;

$context['loginArr'] = $loginArr;
$context['loginErrArr'] = $loginErrArr;

$context['article']['listArr'] = $listArr;
$context['article']['saveArr'] = ($editArr === []) ? $createArr : $editArr;
$context['articleErr']['submitArr'] = $submitErrArr;
$context['articleErr']['imageArr'] = $submitErrArr;

$context['session'] = $_SESSION;

$context['success_message'] = $success_message;
$context['error_message'] = $error_message;

$template = $twig->loadTemplate($template);
$template->display($context);
