<?php

namespace koujigenba_php\backend\validation;

class Regist
{
    private $dataArr = [];
    private $errArr = [];

    public function __construct()
    {
    }

    public function errorCheck($dataArr)
    {
        $this->dataArr = $dataArr;
        $this->createErrorMessage();

        $this->lastNameCheck();
        $this->firstNameCheck();
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

    private function lastNameCheck()
    {
        if ($this->dataArr['last_name'] === '') {
            $this->errArr['last_name'] = '姓を入力してください。';
        } elseif (mb_strlen($this->dataArr['last_name']) > 30) {
            $this->errArr['last_name'] =  '姓は30文字以下で入力してください。';
        }
        // このメールアドレスは既に利用されているため、登録できません。
    }

    private function firstNameCheck()
    {
        if ($this->dataArr['first_name'] === '') {
            $this->errArr['first_name'] = '名を入力してください。';
        } elseif (mb_strlen($this->dataArr['first_name']) > 30) {
            $this->errArr['first_name'] =  '名は30文字以下で入力してください。';
        }
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
        } elseif ($this->dataArr['password'] !== $this->dataArr['password_confirmation']) {
            $this->errArr['password'] = 'パスワードとパスワード(確認)が一致しません。';
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
