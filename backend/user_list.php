<?php

namespace koujigenba_php\backend;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use koujigenba_php\backend\Bootstrap;
use koujigenba_php\backend\lib\PDODatabase;
use koujigenba_php\backend\lib\Session;
use koujigenba_php\backend\lib\User;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$session = new Session($db);
$user = new User($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$template = 'user_list.html.twig';

$session->checkSession();

$success_message = '';

if ($_SESSION['res'] === false) {
    // セッションがなければ、list.phpへリダイレクト
    header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
} else {
    // セッションがある場合

    // ログインユーザーの情報を取得
    require_once './auth/user_info.php';

    if ($user_info['admin'] === 0) {
        // ログインユーザーのusersテーブルのadminカラムが0であれば、list.phpへリダイレクト
        header('Location: ' . Bootstrap::ENTRY_URL . 'list.php');
    } else {
        // 全てのユーザーの情報を取得
        $user_list = $user->getUserInfo();

        // adminとdelete_flgの更新処理
        if (isset($_POST['update']) === true) {
            $user_id = $_POST['user_id'];
            $admin = $_POST["admin_{$user_id}"];
            $delete_flg = $_POST["delete_flg_{$user_id}"];
            $update_permission_result = $user->updatePermission($user_id, $admin, $delete_flg);
            if ($update_permission_result === true) {
                $user_list[intval($_POST['num'])] = [
                    'id' => $user_list[intval($_POST['num'])]['id'],
                    'last_name' => $user_list[intval($_POST['num'])]['last_name'],
                    'first_name' => $user_list[intval($_POST['num'])]['first_name'],
                    'email' => $user_list[intval($_POST['num'])]['email'],
                    'password' => $user_list[intval($_POST['num'])]['password'],
                    'admin' => $admin,
                    'delete_flg' => $delete_flg
                ];
                $success_message = 'ユーザーID' . $user_id . 'の更新に成功しました。';
            }
        }
    }
}

$context = [];

$context['user_info'] = $user_info;
$context['user_list'] = $user_list;

$context['session'] = $_SESSION;

$context['success_message'] = $success_message;

$template = $twig->loadTemplate($template);
$template->display($context);
