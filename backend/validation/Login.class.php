<?php

namespace koujigenba_php\backend\validation;

class Login
{
    private $dataArr = [];
    private $errArr = [];

    public function __construct()
    {
    }

    public function errorCheck($loginArr)
    {
        $this->dataArr = $loginArr;
        $this->createErrorMessage();

        $this->emailCheck();
        $this->passwordCheck();

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
