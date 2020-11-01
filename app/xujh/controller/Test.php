<?php
namespace app\xujh\controller;

use app\BaseController;

class test extends BaseController
{
    public function showChar()
    {
        return '欢迎使用PHP框架';
    }
    public function showPage()
    {
        return view();
    }
}
