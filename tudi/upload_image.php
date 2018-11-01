<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传图片</title>
</head>

<body>
<?php 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
	//设置上传图片的类型
	$allowed = array ('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
	
	//判断类型是否是数组里的一种
	if (is_array($allowed)) {
		if (!in_array($_FILES['upload']['type'],$allowed)) {
			echo '上传图片必须是JPEG或PNG中的一种';
		}
	}		
	
	//判断文件错误类型
	if ($_FILES['upload']['error'] > 0) {
		echo '<p class="error">图片无法上传的原因：<strong>';
		switch ($_FILES['upload']['error']) {
			case 1:
				print '上传的文件超过了php.ini中upload_max_filesize选项限制的值';
				break;
			case 2:
				print '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
				break;
			case 3:
				print '文件只有部分被上传';
				break;
			case 4:
				print '没有文件被上传';
				break;
			case 6:
				print '没有可用的临时文件夹';
				break;
			case 7:
				print '无法写入到磁盘';
				break;
			case 8:
				print '文件上传停止';
				break;
			default:
				print '发生系统错误';
				break;
		} 		
		print '</strong></p>';	
	}
		
	//移动文件
	if (is_uploaded_file($_FILES['upload']['tmp_name'])) {
	
			//获取文件的扩展名例如【.jpg】
			$_n = explode('.',$_FILES['upload']['name']);
			//创建一个临时文件的名称
			$temp = '../uploads/' . time() . '.' . $_n[1];	
			
		if	(!@move_uploaded_file($_FILES['upload']['tmp_name'],$temp)) {
			echo '移动失败';
			$temp = $_FILES['upload']['tmp_name'];
		} else {
			echo "<script>alert('上传成功！');window.opener.document.getElementById('dituweizhi').value='$temp';window.close();</script>";
			exit();
		}
	} else {
		echo '上传的临时文件不存在！';
		$temp = NULL;
	}	
		
	// 如果存在临时文件的话，删除掉它
	if ( isset($temp) && file_exists ($temp) && is_file($temp) ) {
		unlink ($temp);
	}
	
}
?>
<form enctype="multipart/form-data" action="upload_image.php" method="post">

	<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
	<fieldset><legend>选择一个小于512KB的JPEG或者PNG图像进行上传：</legend>
	
	<p><b>选择图片：</b> <input type="file" name="upload" /></p>
	
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="确定上传" /></div>

</form>

</body>
</html>
