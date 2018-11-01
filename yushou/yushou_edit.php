<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '预售数据修改';
include ('../header.php');
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {         // 接收来自yushou.php传递过来的id
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
	if (!empty($trimmed['pro_name'])) {
		$pro_name = mysqli_real_escape_string ($conn, $trimmed['pro_name']);
	} else {
		$errors[] = '项目名称不能为空';
	}	
	if(empty($trimmed['yushou_id'])) {
		$errors[] = '预售证号不能为空';
	} else {
		$yushou_id = mysqli_real_escape_string ($conn, $trimmed['yushou_id']);
	}
	
	if(empty($trimmed['fa_time'])) {
		$errors[] = '发证日期不能为空';
	} else {
		$fa_time = mysqli_real_escape_string ($conn, $trimmed['fa_time']);
	}
	if(empty($trimmed['yushou_lou'])) {
		$errors[] = '预售楼栋不能为空';
	} else {
		$yushou_lou = mysqli_real_escape_string ($conn, $trimmed['yushou_lou']);
	}	
	if (is_numeric($trimmed['yushou_tao']) && ($trimmed['yushou_tao'] > 0)) {
		$yushou_tao = mysqli_real_escape_string ($conn, (int) $trimmed['yushou_tao']);
	} else {
		$errors[] = '请输入一个正确的预售套数';
	}
	if (is_numeric($trimmed['yushou_mianji']) && ($trimmed['yushou_mianji'] > 0)) {
		$yushou_mianji = mysqli_real_escape_string ($conn, (float) $trimmed['yushou_mianji']);
	} else {
		$errors[] = '请输入一个正确的预售面积(㎡)';
	}
	if(empty($trimmed['yushou_yongtu'])) {
		$errors[] = '用途不能为空';
	} else {
		$yushou_yongtu = mysqli_real_escape_string ($conn, $trimmed['yushou_yongtu']);
	}	
	if(empty($trimmed['city_name'])) {
		$errors[] = '请选择区域';
	} else {
		$city_name = mysqli_real_escape_string ($conn, $trimmed['city_name']);
	}	
	if(empty($trimmed['qiye_name'])) {
		$errors[] = '开发商不能为空';
	} else {
		$qiye_name = mysqli_real_escape_string ($conn, $trimmed['qiye_name']);
	}
	
	if (empty($errors)) {

		$q = "SELECT id FROM f_yushou WHERE yushou_id='$yushou_id' AND id != $id";
		$r = @mysqli_query($conn, $q);
		if (mysqli_num_rows($r) == 0) {
		
			$q = "UPDATE f_yushou SET city_name='$city_name', pro_name='$pro_name', yushou_id='$yushou_id', fa_time='$fa_time', yushou_lou='$yushou_lou', yushou_tao='$yushou_tao', yushou_mianji='$yushou_mianji', qiye_name='$qiye_name', yushou_yongtu='$yushou_yongtu' WHERE id=$id LIMIT 1";		
			$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
			if (mysqli_affected_rows($conn) == 1) {
				ShowMsg('这条数据已经修改成功','yushou.php');
				$_POST = array();	
			} else {
				echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>';
				//ShowMsg('由于系统错误，您暂时无法编辑，请联系管理员',null);			
			}	

		} else {
			echo '预售证号已经被使用了';
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
$q = "SELECT * FROM f_yushou WHERE id=$id LIMIT 1";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">预售数据修改</div>
	<div class="portlet-body">
	<form action="yushou_edit.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>" />
		<div class="control-group">
			<label class="control-label">项目名称</label>
			<div class="controls">
				<input type="text" name="pro_name" class="add-in w600" value="<?php echo $row['pro_name']?>" />
			</div>
		</div> 
		<div class="control-group">
			<label class="control-label">预售证号</label>
			<div class="controls">
				<input type="text" name="yushou_id" class="add-in w600" value="<?php echo $row['yushou_id']?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">发证日期</label>
			<div class="controls">
				<input type="text" name="fa_time" class="add-in" onClick="WdatePicker()" value="<?php echo $row['fa_time']?>" />时间格式例如：2014-12-12
			</div>
		</div>  
		<div class="control-group">
			<label class="control-label">预售楼栋</label>
			<div class="controls">
				<input type="text" name="yushou_lou" class="add-in w600" value="<?php echo $row['yushou_lou']?>" />
			</div>
		</div> 
		<div class="control-group">
			<label class="control-label">预售套数</label>
			<div class="controls">
				<input type="text" name="yushou_tao" class="add-in" value="<?php echo $row['yushou_tao']?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">预售面积(㎡)</label>
			<div class="controls">
				<input type="text" name="yushou_mianji" class="add-in" value="<?php echo $row['yushou_mianji']?>" />默认保留2位小数
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label">用途</label>
			<div class="controls">
				<input type="text" name="yushou_yongtu" class="add-in" value="<?php echo $row['yushou_yongtu']?>" />住宅/商住楼/商业用房等
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
			<label class="control-label">开发商</label>
			<div class="controls">
				<input type="text" name="qiye_name" class="add-in w600" value="<?php echo $row['qiye_name']?>" />
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