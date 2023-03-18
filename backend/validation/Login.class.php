<?php

namespace koujigenba_php\backend\validation;

class Login
{
    private $dataArr = [];
    private $errArr = [];

    public function __construct()
    {
    }

    public function errorCheck($loginArr, $session)
    {
        $this->dataArr = $loginArr;
        $this->createErrorMessage();

        $this->emailCheck();
        $this->passwordCheck();

        $loginErrArr = $this->errArr;
        if ($loginErrArr['email'] === '' && $loginErrArr['password'] === '') {
            $this->authenticationCheck($session);
        }
        echo '<br>';var_dump($this->errArr);echo '<br>';

        return $this->errArr;
    }

    private function createErrorMessage()
    {
        foreach ($this->dataArr as $key => $val) {
            $this->errArr[$key] = '';
        }
        unset($this->errArr['password_confirmation']);
    }

    private function emailCheck()
    {
        if ($this->dataArr['email'] === '') {
            $this->errArr['email'] = 'メールアドレスを入力してください。';
        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $this->dataArr['email']) === 0) {
            $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください。';
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

    public function authenticationCheck($session)
    {
        $password = $this->dataArr['password'];
        $email = $this->dataArr['email'];
        $passwordArr = $session->getPassword($email);

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
