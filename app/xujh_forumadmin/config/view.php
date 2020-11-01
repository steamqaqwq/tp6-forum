<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    'tpl_replace_string' =>[
        '__ROOT__' => '/36xujh/public',
        '__IMG__' => env('view.img_forumadmin','/36xujh/public/static/xujh_forumadmin/images'),
        '__CSS__' => env('view.css_forumadmin',''),
        '__JS__' => env('view.js_forumadmin',''),
        '__STATIC__' => '/36xujh/public/static',
    ],
    'tpl_cache' =>false,
    // 
];
