<?php

namespace koujigenba_php\backend;

date_default_timezone_set('Asia/Tokyo');

require_once dirname(__FILE__) . './../vendor/autoload.php';

class Bootstrap
{
    const DB_HOST = 'localhost';

    const DB_NAME = 'koujigenba_db';

    const DB_USER = 'koujigenba_user';

    const DB_PASS = 'koujigenba_pass';

    const DB_TYPE = 'mysql';

    const APP_DIR = '/Applications/MAMP/htdocs/';

    const TEMPLATE_DIR = self::APP_DIR . 'koujigenba_php/frontend/templates/';

    const CACHE_DIR = false;

    const APP_URL = 'http://localhost:8888/';

    const ENTRY_URL = self::APP_URL . 'koujigenba_php/backend/';

    public static function loadClass($class)
    {
        $path = str_replace('\\', '/', self::APP_DIR . $class . '.class.php');
        require_once $path;
    }
}

// これを実行しないとオートローダーとして動かない
spl_autoload_register([
    'koujigenba_php\backend\Bootstrap',
    'loadClass'
]);
