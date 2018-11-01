<?php
//防止恶意调用
if (!defined('FEI_GE')) {
	exit('Access Defined!');
}
// 拒绝PHP低版本
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    exit('PHP Version is to Low!');	
}
// 设置页面编码 
header('Content-Type: text/html; charset=utf-8');
// 转换硬路径常量（站点www下inc文件的路径和站点www的路径）
define('BASE_INC', str_replace("\\", '/', dirname(__FILE__) ) );
define('BASE_URL', str_replace("\\", '/', substr(BASE_INC,0,-3) ) );






/**
 * 配置开始
 * 以下信息是搭建程序后要配置的内容
 */


// 定义一个常量用于指向连接数据库的路径
define('MYSQL', BASE_INC.'/conn.php');
// 定义站点域名
define ('SITE_URL', 'http://127.0.0.1/');
// 设置yushou.php(预售信息)一页出现多少条信息
$yushou_fenye = 10;
// 设置tudi.php(土地信息)一页出现多少条信息
$tudi_fenye = 10;
// 设置shuji.php(监控区域成交情况),你要监控的区域
$shuji_city_name='东湖区';
// 设置shuji.php(监控区域成交情况),你要监控的房屋性质
$shuji_wuye_name='住宅';
// 设置shuji.php(监控区域成交情况),你要监控的多少天
$shuji_fenye=30;
// 设置info.php(竞品监控信息),一页出现多少条信息
$info_fenye=30;


/**
 * 配置结束
 * 以上信息是搭建程序后要配置的内容
 */




// 设置一个常量用于判断站点是否在网络上运行
define('LIVE', FALSE);
// 函数接收5个参数（错误编号，出错消息，发生错误的脚本，php认为发生错误的行号，一个数组【包含了当错误发生时，在用的每个变量以及它们的值】）
function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {
	// 生成错误消息
	$message = "出错文件是 '$e_file' 出错行号是 $e_line: $e_message\n";
	// 添加日期时间
	$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n";
	if (!LIVE) { // 站点上线运行（打印详细的错误）

		// 显示错误信息
		echo '<div class="error">' . nl2br($message);
	
		// 把现有的变量添加到出错信息中
		echo '<pre>' . print_r ($e_vars, 1) . "\n";
		debug_print_backtrace();
		echo '</pre></div>';
		
	} else { // 不显示错误

		// 发送邮件给管理员
		$body = $message . "\n" . print_r ($e_vars, 1);
		mail(EMAIL, 'Site Error!', $body, 'From: 414412984@qq.com');
	
		// 如果错误不是一个通知，只打印一个错误信息
		if ($e_number != E_NOTICE) {
			echo '<div class="error">发生系统错误，对此不便，我们表示歉意。</div>';
		}
	} 

}
// 使用自定义的错误函数
set_error_handler ('my_error_handler');	
 


/**
 * 返回信息定向函数
 */
function ShowMsg($_msg,$_url) {	
	if (empty($_url)) {
		echo "<script type='text/javascript'>alert('$_msg');history.back();</script>";
		exit();
	} else { 
		if (empty($_msg)) { 
			header('Location:'.$_url);
		} else {
			echo "<script type='text/javascript'>alert('$_msg');location.href='$_url';</script>";
			exit();
		}
	}
}


function check_content($_string,$_max) {
	if (mb_strlen($_string,'utf-8') > $_max) {
		ShowMsg('字数不能大于'.$_max.'位！',null);
	}
	return $_string;
}





?>