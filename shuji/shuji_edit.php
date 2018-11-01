<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '区域成交数据修改';
include ('../header.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {         // 接收来自index.php传递过来的id
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // 接收表单中的id
	$id = $_POST['id'];
} else { //如果这两个条件都不为TRUE，那么将不能继续处理页面，从而显示一条出错消息，并终止脚本的执行
	echo '404';
	include ('../footer.php');
	exit();
}

require (MYSQL);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);
	$errors = array();
	if(empty($trimmed['wdate'])) {
		$errors[] = '日期不能为空';
	} else {
		$w = mysqli_real_escape_string ($conn, $trimmed['wdate']);
	}
	
	if ( isset($trimmed['city']) && filter_var($trimmed['city'], FILTER_VALIDATE_INT, array('city' => 1)) ) {
		$c = mysqli_real_escape_string ($conn, $trimmed['city']);;
	} else {
		$errors[] = '请选择区域';
	}
	
	if (is_numeric($trimmed['taoshu']) && ($trimmed['taoshu'] > 0)) {
		$t = mysqli_real_escape_string ($conn, (int) $trimmed['taoshu']);
	} else {
		$errors[] = '请输入一个正确的套数';
	}
	if (is_numeric($trimmed['area']) && ($trimmed['area'] > 0)) {
		$a = mysqli_real_escape_string ($conn, (float) $trimmed['area']);
	} else {
		$errors[] = '请输入一个正确的面积';
	}
	
	if (empty($errors)) {		
		$q = "UPDATE f_chengjiao SET fang_time='$w', city_id='$c', taoshu='$t', area='$a', os_time=NOW() WHERE id=$id LIMIT 1";
		$r = @mysqli_query ($conn, $q);
		if (mysqli_affected_rows($conn) == 1) { 
			ShowMsg('这条数据已经修改成功','index.php');				
		} else {
			ShowMsg('由于系统错误，您暂时无法编辑，请联系管理员',null);	
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
$q = "SELECT * FROM f_chengjiao WHERE id=$id LIMIT 1";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">区域成交数据修改</div>
	<div class="portlet-body">
	<form action="zhuzhai_edit.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>" />
		<div class="control-group">
			<label class="control-label">时间</label>
			<div class="controls">
				<input type="text" name="wdate" class="add-in" onClick="WdatePicker()" value="<?php echo $row['fang_time']?>" />时间格式例如：2014-12-12
			</div>
		</div> 
        <div class="control-group">
			<label class="control-label">区域</label>
			<div class="controls">
				<select name="city_name" class="select-city">
                    <?php 
					$q2 = "SELECT city_name FROM f_city ORDER BY city_id ASC";		
					$r2 = mysqli_query ($conn, $q2) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
					if (@mysqli_num_rows($r2) > 0) {
						while ($row2 = mysqli_fetch_array ($r2, MYSQLI_NUM)) {
							echo "<option value=\"$row2[0]\"";
							if (isset($row['city_name']) && ($row['city_name'] == $row2[0]) ) echo ' selected="selected"';
							echo ">$row2[0]</option>\n";
						}
						mysqli_free_result ($r2);
					} else {
						echo '<option>暂无区域，请添加一个新地区</option>';
					}
					mysqli_close($conn);
					?>
				</select>
			</div>
		</div>
        <div class="control-group">
			<label class="control-label">套数</label>
			<div class="controls">
				<input type="text" name="taoshu" class="add-in" value="<?php echo $row['taoshu']?>" />套
			</div>
		</div> 
        <div class="control-group">
			<label class="control-label">面积</label>
			<div class="controls">
				<input type="text" name="area" class="add-in" value="<?php echo $row['area']?>" />平方(默认保留2位小数)
			</div>
		</div> 
        <div class="form-actions">
            <button class="btn" name="submit" type="submit">提交</button>
	   	</div>   
	</form>
    </div>
</div>
<script type="text/javascript" src="../js/WdatePicker.js"></script>
<?php
include ('../footer.php');
?>