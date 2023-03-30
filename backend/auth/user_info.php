<?php

namespace koujigenba_php\backend\auth;

$get_user_info = $user->getUserInfo($_SESSION['user_id']);
$user_info = [
    'id' => $get_user_info[0]['id'],
    'last_name' => $get_user_info[0]['last_name'],
    'first_name' => $get_user_info[0]['first_name'],
    'email' => $get_user_info[0]['email'],
    'password' => $get_user_info[0]['password'],
    'admin' => $get_user_info[0]['admin'],
    'delete_flg' => $get_user_info[0]['delete_flg']
];
