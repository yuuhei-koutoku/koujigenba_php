<?php

namespace koujigenba_php\backend\validation;

class Login
{
    private $dataArr = [];
    private $errArr = [];

    public function __construct()
    {
    }

    public function errorCheck($loginArr, $user)
    {
        $this->dataArr = $loginArr;
        $this->createErrorMessage();

        $this->emailCheck($user);
        $this->passwordCheck();

        $loginErrArr = $this->errArr;
        if ($loginErrArr['email'] === '' && $loginErrArr['password'] === '') {
            $this->authenticationCheck($user);
        }

        return $this->errArr;
    }

    private function createErrorMessage()
    {
        foreach ($this->dataArr as $key => $val) {
            $this->errArr[$key] = '';
        }
        unset($this->errArr['password_confirmation']);
    }

    private function emailCheck($user)
    {
        $check_email = $user->checkEmail($this->dataArr['email']);
        if ($this->dataArr['email'] === '') {
            $this->errArr['email'] = 'メールアドレスを入力してください。';
        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $this->dataArr['email']) === 0) {
            $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください。';
        } elseif ($check_email === []) {
            $this->errArr['email'] = 'このメールアドレスは登録されていません。';
        } elseif ($check_email[0]['delete_flg'] === 1) {
            $this->errArr['email'] = 'このメールアドレスで登録されたアカウントは退会済みです。アカウントを復元する場合は、運営へ問い合わせてください。';
        }
    }

    private function passwordCheck()
    {
        if ($this->dataArr['password'] === '') {
            $this->errArr['password'] = 'パスワードを入力してください。';
        } elseif (preg_match('/^[a-zA-Z0-9.?\/-]{8,16}$/', $this->dataArr['password']) === 0) {
            $this->errArr['password'] = '8文字以上16文字以下のパスワードを入力してください。';
        }
    }

    public function authenticationCheck($user)
    {
        $password = $this->dataArr['password'];
        $email = $this->dataArr['email'];
        $passwordArr = $user->getPassword($email);

        $password_hash = '';
        if ($passwordArr !== []) $password_hash = $passwordArr[0]['password'];

        $res = (password_verify($password, $password_hash)) ? true : false;

        if ($res === false) {
            $this->errArr['authentication'] = 'メールアドレスまたはパスワードが違います。';
        }
    }

    public function getErrorFlg()
    {
        $err_check = true;
        foreach ($this->errArr as $key => $value) {
            if ($value !== '') {
                $err_check = false;
            }
        }
        return $err_check;
    }
}
