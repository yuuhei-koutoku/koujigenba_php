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

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 記事一覧データを取得
$articleArr = $article->getArticle();

$registArr = [];
$registErrArr = [];

$template = 'regist.html.twig';

// アカウント登録入力内容チェック
$err_check = false;
if (isset($_POST['regist']) === true) {
    unset($_POST['regist']);
    $registArr = $_POST;

    $validation_regist = new Regist();
    $registErrArr = $validation_regist->errorCheck($registArr);
    $err_check = $validation_regist->getErrorFlg();
}

// アカウント登録入力内容保存
if ($err_check === true) {
    $session_regist = new Session($db);
    $regist_result = $session_regist->regist($registArr);
    if ($regist_result === true) $template = 'list.html.twig';
}

$context['registArr'] = $registArr;
$context['registErrArr'] = $registErrArr;
$context['articleArr'] = $articleArr;
$template = $twig->loadTemplate($template);
$template->display($context);
