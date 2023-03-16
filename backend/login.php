<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\validation\Login;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$loginArr = [];
$loginErrArr = [];

$err_check = false;
if (isset($_POST['login']) === true) {
    unset($_POST['login']);
    $loginArr = $_POST;

    $validation_login = new Login();
    $loginErrArr = $validation_login->errorCheck($loginArr);
    $err_check = $validation_login->getErrorFlg();
}

$context = [];
$context['loginArr'] = $loginArr;
$context['loginErrArr'] = $loginErrArr;
$template = $twig->loadTemplate('login.html.twig');
$template->display($context);
