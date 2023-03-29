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

$template = 'user_list.html.twig';

$session->checkSession();

if ($_SESSION['res'] === false) {
    // セッションがなければ、list.phpへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
} else {
    // セッションがある場合

    // ログインユーザーの情報を取得
    require_once './auth/user_info.php';

    $user_list = $user->getUserInfo();
}


// echo '<pre>';var_dump($user_list);echo '</pre>';

$context = [];

$context['user_info'] = $user_info;
$context['user_list'] = $user_list;

$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);
