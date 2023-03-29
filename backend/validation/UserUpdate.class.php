<?php

namespace koujigenba_php\backend\validation;

class UserUpdate
{
    public function __construct()
    {
    }

    public function emailCheck($old_email, $new_email, $user)
    {
        $email_error = '';
        $check_email = $user->checkEmail($new_email);
        if ($new_email === '') {
            $email_error = 'メールアドレスを入力してください。';
        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $new_email) === 0) {
            $email_error = 'メールアドレスを正しい形式で入力してください。';
        } elseif ($old_email === $new_email) {
            $email_error = '既に登録されているメールアドレスと同じメールアドレスが入力されています。';
        } elseif ($check_email === false) {
            $email_error = 'このメールアドレスは既に利用されているため、登録できません。';
        }

        return $email_error;
    }

    public function passwordCheck($current_password_hash, $current_password, $new_password, $new_password_confirmation)
    {
        $password_error = [
            'current_password' => '',
            'new_password' => '',
            'new_password_confirmation' => ''
        ];
        if ($current_password === '') {
            $password_error['current_password'] = '現在のパスワードを入力してください。';
        } elseif (password_verify($current_password, $current_password_hash) === false) {
            $password_error['current_password'] = '現在のパスワードが間違っています。';
        }
        if ($new_password === '') {
            $password_error['new_password'] = '新しいパスワードを入力してください。';
        } elseif (preg_match('/^[a-zA-Z0-9.?\/-]{8,16}$/', $new_password) === 0) {
            $password_error['new_password'] = '新しいパスワードは8文字以上16文字以下で入力してください。';
        }
        if ($new_password_confirmation === '') {
            $password_error['new_password_confirmation'] = '新しいパスワード（確認）を入力してください。';
        } elseif ($new_password !== $new_password_confirmation) {
            $password_error['new_password_confirmation'] = '新しいパスワードと新しいパスワード（確認）が一致しません。';
        }

        return $password_error;
    }
}
