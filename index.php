<?php

namespace koujigenba_php;

require_once dirname(__FILE__) . '/backend/Bootstrap.class.php';
require_once dirname(__FILE__) . '/backend/list.php';

use koujigenba_php\backend\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$context = [];
$template = $twig->loadTemplate('index.html.twig');
$template->display($context);
