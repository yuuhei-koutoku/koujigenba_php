<?php

namespace koujigenba_php\backend;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\lib\Session;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$session = new Session($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$session->checkSession();

$template = 'create.html.twig';

if ($_SESSION['res'] === false) {
    // セッションがなければ、トップページへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL);
} else {
    require_once './article/create.php';
}

$context = [];
$context['article']['saveArr'] = $createArr;
$template = $twig->loadTemplate($template);
$template->display($context);
