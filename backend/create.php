<?php

namespace koujigenba_php\backend;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\lib\Session;
use koujigenba_php\backend\lib\User;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$session = new Session($db);
$user = new User($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$session->checkSession();

$template = 'create.html.twig';

$createArr = [];

if ($_SESSION['res'] === false) {
    // セッションがなければ、list.phpへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
} else {
    // 記事投稿処理
    require_once './article/create.php';

    // ログインユーザーの情報を取得
    require_once './auth/user_info.php';
}

$context = [];
$context['user_info'] = $user_info;
$context['article']['saveArr'] = $createArr;
$context['session'] = $_SESSION;
$template = $twig->loadTemplate($template);
$template->display($context);
