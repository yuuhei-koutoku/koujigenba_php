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

// 記事一覧データを取得
$listArr = $article->getArticle();

$loginArr = [];
$loginErrArr = [];

$success_message = '';
$error_message = '';

$template = 'login.html.twig';

if ($_SESSION['res'] === true) {
    // セッションがある場合、list.phpへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
} else {
    // セッションがない場合

    // ログイン処理
    require_once './auth/login.php';
}

$context = [];
$context['loginArr'] = $loginArr;
$context['loginErrArr'] = $loginErrArr;
$context['article']['listArr'] = $listArr;
$context['session'] = $_SESSION;
$context['success_message'] = $success_message;
$context['error_message'] = $error_message;
$template = $twig->loadTemplate($template);
$template->display($context);
