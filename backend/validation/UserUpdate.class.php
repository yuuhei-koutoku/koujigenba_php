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

}
