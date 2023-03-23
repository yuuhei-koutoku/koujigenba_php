<?php

namespace koujigenba_php\backend;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\lib\Session;
use koujigenba_php\backend\lib\Article;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$session = new Session($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$session->checkSession();

if ($_GET === [] || $_SESSION['res'] === false) {
    // $_GETのパラメーターがなければ、トップページへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL);
} else {
    $article = new Article($db);
    $editArr = $article->getArticle($_GET['article_id']);
}

$template = 'edit.html.twig';

$context = [];
$context['article']['saveArr'] = $editArr[0];

$template = $twig->loadTemplate($template);
$template->display($context);
