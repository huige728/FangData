<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '房屋性质修改';
include ('../header.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {         // 接收其他页面传递过来的id
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // 接收表单中的id
	$id = $_POST['id'];
} else { //如果这两个条件都不为TRUE，那么将不能继续处理页面，从而显示一条出错消息，并终止脚本的执行
	echo '404';
	include ('footer.php');
	exit();
}

require (MYSQL);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);
	$errors = array();

	if(empty($trimmed['wuye_name'])) {
		$errors[] = '请确定有无区域';
	} else {
		$c = mysqli_real_escape_string ($conn, $trimmed['wuye_name']);
	}
	
	if (empty($errors)) {		
		$q = "UPDATE f_wuye SET wuye_name='$c' WHERE wuye_id=$id LIMIT 1";
		$r = @mysqli_query ($conn, $q);
		if (mysqli_affected_rows($conn) == 1) { 
			ShowMsg('这条数据已经修改成功','wuye_add.php');	
			//echo '<p>已经添加成功</p>';			
		} else {
			ShowMsg('由于系统错误，您暂时无法编辑，请联系管理员',null);	
			//echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>'; 
		}
	}	
} 

if ( !empty($errors) && is_array($errors) ) {
	echo '<p style="font-weight: bold; color: #C00">发生下列错误:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '请在试一遍</p>';
}
$q = "SELECT * FROM f_wuye WHERE wuye_id=$id LIMIT 1";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">房屋性质修改</div>
	<div class="portlet-body">
	<form action="wuye_edit.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>" />
        <div class="control-group">
			<label class="control-label">房屋性质</label>
			<div class="controls">
			
				<input type="text" name="wuye_name" class="add-in" value="<?php echo $row['wuye_name']?>" />例子：住宅、非住宅、公寓、写字楼、商铺等
				
			</div>
		</div> 
        <div class="form-actions">
            <button class="btn" name="submit" type="submit">提交</button>
	   	</div>   
	</form>
    </div>
</div>
<?php
include ('../footer.php');
?>