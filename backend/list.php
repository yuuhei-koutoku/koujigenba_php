<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$link = mysqli_connect('localhost', 'koujigenba_user', 'koujigenba_pass', 'koujigenba_db');
if ($link !== false) {
    echo 'データベースの接続に成功しました';

    $query = "SELECT * FROM articles";
    $res = mysqli_query($link, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        array_push($data, $row);
    }

    // arsort 降順（逆順）で表示
} else {
    echo 'データベースの接続に失敗しました';
}

$context = [];
if (isset($data) === true) $context['article'] = $data;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);
