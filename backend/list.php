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

$user_info = [];

$registArr = [];
$registErrArr = [];

$loginArr = [];
$loginErrArr = [];

$createArr = [];
$editArr = [];
$submitErrArr = [];
$imageErrArr = [];

$success_message = '';
$error_message = '';

$template = 'list.html.twig';

if ($_SESSION['res'] === true) {
    // セッションがある場合

    // ログインユーザーの情報を取得
    require_once './auth/user_info.php';

    // 記事投稿処理
    require_once './article/create.php';

    // 記事編集処理
    require_once './article/edit.php';

    // 記事削除処理
    require_once './article/delete.php';

    // ログアウト処理
    require_once './auth/logout.php';

    // 退会処理
    require_once './auth/delete_flg.php';

} else {
    // セッションがない場合

    // アカウント登録処理
    require_once './auth/regist.php';

    // ログイン処理
    require_once './auth/login.php';

}

// 記事一覧データを取得
$listArr = $article->getArticle();
arsort($listArr);

$context = [];

$context['user_info'] = $user_info;

$context['registArr'] = $registArr;
$context['registErrArr'] = $registErrArr;

$context['loginArr'] = $loginArr;
$context['loginErrArr'] = $loginErrArr;

$context['article']['listArr'] = $listArr;
$context['article']['saveArr'] = ($editArr === []) ? $createArr : $editArr;
$context['articleErr']['submitArr'] = $submitErrArr;
$context['articleErr']['imageArr'] = $imageErrArr;

$context['session'] = $_SESSION;

$context['success_message'] = $success_message;
$context['error_message'] = $error_message;

$template = $twig->loadTemplate($template);
$template->display($context);
