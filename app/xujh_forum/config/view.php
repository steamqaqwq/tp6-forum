<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    'tpl_replace_string' =>[
        '__ROOT__' => '/36xujh/public',
        '__IMG__' => env('view.img_forum','/36xujh/public/static/xujh_forum/images'),
        '__CSS__' => env('view.css_forum',''),
        '__JS__' => env('view.js_forum',''),
        '__STATIC__' => '/36xujh/public/static',
        '__INDEX__' => '/36xujh/public/',
        '__FONT__' =>'/36xujh/public/static/xujh_forum/fonts/fonteditor/',
        '__UPLOAD__'=>env('view.up_forum','')
    ],
    'tpl_cache' =>false,
    // 
];
