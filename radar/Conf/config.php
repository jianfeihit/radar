<?php
return array(
//数据库配置信息
        'DB_TYPE'   =>'mysql', // 数据库类型
        'DB_HOST'   => 'localhost', // 服务器地址
        'DB_NAME'   => 'searchdb', // 数据库名
        'DB_USER'   => 'root', // 用户名
        'DB_PWD'    => '123', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PREFIX' => 't_', // 数据库表前缀 
	'DATA_CACHE_TIME' =>1,
// 开启模板
        'LAYOUT_ON'=>true,
        'LAYOUT_NAME'=>'layout',
        'DEFAULT_MODULE'=>'Login',
        'DEFAULT_ACTION'=>'show',
        'URL_ROUTER_ON'   => true, //开启路由
  		'URL_MODEL'=>1,
		'SHOW_PAGE_TRACE'=>true,
		//'DEFAULT_CHARSET'=> 'utf-8',
        'URL_ROUTE_RULES'=>array(
//'index' => array('News/archive', 'status=1'),
//'index'=> 'Index/index',
//'news/read/:id'          => '/news/:1',
),
'TOKEN_ON'=>true,  // 是否开启令牌验证
'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
'TOKEN_RESET'=>true
);
?>