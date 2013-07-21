<?php
return array(
        //数据库配置信息
        'DB_TYPE'   =>'mysql', // 数据库类型
        'DB_HOST'   => 'localhost', // 服务器地址
        'DB_NAME'   => 'test', // 数据库名
        'DB_USER'   => 'root', // 用户名
        'DB_PWD'    => '', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PREFIX' => 't_', // 数据库表前缀 
        // 开启模板
        'LAYOUT_ON'=>true,
        'LAYOUT_NAME'=>'layout',
        'DEFAULT_MODULE'=>'Login',
        'DEFAULT_ACTION'=>'show',
        'URL_ROUTER_ON'   => true, //开启路由

        'URL_ROUTE_RULES'=>array(
            //'index' => array('News/archive', 'status=1'),
            //'index'=> 'Index/index',
            //'news/read/:id'          => '/news/:1',
        ),
);
?>