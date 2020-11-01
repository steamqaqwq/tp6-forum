<?php

return [
   
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => env('database_forum.type', 'mysql'),
            // 服务器地址
            'hostname'          => env('database_forum.hostname', '127.0.0.1'),
            // 数据库名
            'database'          => env('database_forum.database', ''),
            // 用户名
            'username'          => env('database_forum.username', ''),
            // 密码
            'password'          => env('database_forum.password', ''),        
            // 数据库编码默认采用utf8
            'charset'           => env('database_forum.charset', 'utf8'),
            // 数据库表前缀
            'prefix'            => env('database_forum.prefix', ''),
           
        ],

        'mysqladmin' => [
            // 数据库类型
            'type'              => env('database_forumchange.type', 'mysql'),
            // 服务器地址
            'hostname'          => env('database_forumchange.hostname', '127.0.0.1'),
            // 数据库名
            'database'          => env('database_forumchange.database', ''),
            // 用户名
            'username'          => env('database_forumchange.username', 'root'),
            // 密码
            'password'          => env('database_forumchange.password', ''),        
            // 数据库编码默认采用utf8
            'charset'           => env('database_forumchange.charset', 'utf8'),
            // 数据库表前缀
            'prefix'            => env('database_forumchange.prefix', ''),
           
        ],
        // 更多的数据库配置信息
    ],
];
