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

$template = 'edit.html.twig';

$article = new Article($db);
$getArr = $article->getArticle($_GET['article_id']);

if ($_GET !== [] && $_GET['article_id'] !== '' && isset($_GET['article_id']) && $_SESSION['res'] === true) {
    // article_idをもとに、記事詳細情報を取得
    $detailArr = $article->getArticle($_GET['article_id']);

    // データが取得できれば、記事編集を表示
    if ($detailArr !== [] && $detailArr[0]['user_id'] === $_SESSION['user_id']) {
        // 記事削除処理
        require_once './article/edit.php';
    } else {
        // データが取得できなければ、list.phpへリダイレクト
        header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
    }
} else {
    // $_GETのパラメーターがなければ、list.phpへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
}

$context = [];
$context['article']['saveArr'] = $getArr[0];
$context['article']['article_id'] = $_GET['article_id'];
$context['session'] = $_SESSION;
$template = $twig->loadTemplate($template);
$template->display($context);
