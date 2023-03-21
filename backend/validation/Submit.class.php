<?php

namespace koujigenba_php\backend\validation;

class Submit
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

        $this->titleCheck();
        $this->contentCheck();

        return $this->errArr;
    }

    private function createErrorMessage()
    {
        foreach ($this->dataArr as $key => $val) {
            $this->errArr[$key] = '';
        }
    }

    private function titleCheck()
    {
        if ($this->dataArr['title'] === '') {
            $this->errArr['title'] = 'タイトルを入力してください。';
        } elseif (mb_strlen($this->dataArr['title']) > 100) {
            $this->errArr['title'] =  'タイトルは100文字以下で入力してください。';
        }
    }

    private function contentCheck()
    {
        if ($this->dataArr['content'] === '') {
            $this->errArr['content'] = '本文を入力してください。';
        } elseif (mb_strlen($this->dataArr['content']) > 10000) {
            $this->errArr['content'] =  'タイトルは10000文字以下で入力してください。';
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
