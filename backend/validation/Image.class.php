<?php

namespace koujigenba_php\backend\validation;

class Image
{
    private $dataArr = [];
    private $errArr = [];

    public function __construct()
    {
    }

    public function errorCheck($tmp_image, $image_mime)
    {
        $this->dataArr = $tmp_image;
        $this->createErrorMessage();

        $this->sizeCheck();
        $this->fileExtensionCheck($image_mime);
        $this->nameCheck();

        return $this->errArr;
    }

    private function createErrorMessage()
    {
        foreach ($this->dataArr as $key => $val) {
            $this->errArr[$key] = '';
        }
        $this->errArr['extension'] = '';
    }

    private function sizeCheck()
    {
        if ($this->dataArr['size'] > 1048576) {
            $this->errArr['size'] = 'アップロードできる画像のサイズは、1MBまでです。';
        }
    }

    private function fileExtensionCheck($image_mime)
    {
        if (preg_match('/^image\/jpeg$/', $image_mime) === 0 && preg_match('/^image\/png$/', $image_mime) === 0) {
            $this->errArr['extension'] = 'JPEG形式(jpg/jpe/jpeg)とPNG形式(png)以外はアップロードできません。';
        }
    }

    private function nameCheck()
    {
        if (mb_strlen($this->dataArr['name']) > 30) {
            $this->errArr['name'] = 'ファイル名は拡張子を含めて30文字以下にしてください。';
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
