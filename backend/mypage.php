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

$get_user_info = $user->getUserInfo($_SESSION['user_id']);
$user_info = [
    'id' => $get_user_info[0],
    'last_name' => $get_user_info[1],
    'first_name' => $get_user_info[2],
    'email' => $get_user_info[3]
];

$context = [];
$context['user_info'] = $user_info;
$template = 'mypage.html.twig';

$template = $twig->loadTemplate($template);
$template->display($context);
