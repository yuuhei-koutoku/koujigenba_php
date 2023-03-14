<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\validation\Regist;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$dataArr = [];
$errArr = [];

if (isset($_POST['action']) === true) {
    unset($_POST['action']);
    $dataArr = $_POST;

    $validation_regist = new Regist();
    $errArr = $validation_regist->errorCheck($dataArr);
    $err_check = $validation_regist->getErrorFlg();
}

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);
