<?php
//防止恶意调用
if (!defined('FEI_GE')) {
	exit('Access Defined!');
}
//数据库连接信息
DEFINE ('DB_HOST', 'localhost');	// 服务器地址 
DEFINE ('DB_USER', 'root');			// 数据库用户名
DEFINE ('DB_PASSWORD', '');			// 数据库密码
DEFINE ('DB_NAME', 'fang01');		// 数据库名

$conn = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); // 连接MYSQL数据库,选择指定的数据库

if (!$conn) {
	trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
} else { 
	mysqli_set_charset($conn, 'utf8'); // 设置字符集
}
?>