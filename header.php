<?php
//防止恶意调用
if (!defined('FEI_GE')) {
	exit('Access Defined!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title; ?>-feige数据中心</title>
<link rel="stylesheet" type="text/css" href="../css/css.css"/>
<link rel="stylesheet" type="text/css" href="../css/index.css"/>
</head>

<body>

<div class="wrap">
	<h1 class="logo mTB50"><a href="<?php echo SITE_URL ?>" title="欢迎使用feige数据中心">欢迎使用feige数据中心</a></h1>
</div>


<div class="menu">
	<ul>
	
		<li><a href="<?php echo SITE_URL ?>index.php">首页</a></li>
		
		<li><a href="<?php echo SITE_URL ?>area/area_add.php">区域添加</a>

			<ul>
				<li><a href="<?php echo SITE_URL ?>wuye/wuye_add.php">房屋性质添加</a></li>
			</ul>

		</li>
		
		<li><a href="<?php echo SITE_URL ?>info/info.php">竞品监控信息</a>

			<ul>
				<li><a href="<?php echo SITE_URL ?>info/info_add.php">竞品监控信息添加</a></li>
			</ul>

		</li>
		
		<li><a href="<?php echo SITE_URL ?>tudi/tudi.php">土地信息</a>

			<ul>
				<li><a href="<?php echo SITE_URL ?>tudi/tudi_add.php">土地添加</a></li>
			</ul>

		</li>
		
		<li><a href="<?php echo SITE_URL ?>yushou/yushou.php">预售信息</a>

			<ul>
				<li><a href="<?php echo SITE_URL ?>yushou/yushou_add.php">预售添加</a></li>

			</ul>

		</li>
		
		<li><a href="<?php echo SITE_URL ?>shuji/shuji.php">监控区域成交情况</a>

			<ul>
				<li><a href="<?php echo SITE_URL ?>shuji/shuji2.php">区域成交筛选</a></li>
				<li><a href="<?php echo SITE_URL ?>shuji/shuji_area.php">各区域成交对比</a></li>
				<li><a href="<?php echo SITE_URL ?>shuji/shuji_add.php">区域成交数据添加</a></li>
			</ul>

		</li>
	</ul>

	<div class="clearfix"> </div>
</div>