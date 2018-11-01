<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '预售添加';
include ('../header.php');
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
		$q = "INSERT INTO f_yushou (pro_name, yushou_id, fa_time, yushou_lou, yushou_tao, yushou_mianji,yushou_yongtu, city_name, qiye_name) VALUES ('$pro_name', '$yushou_id', '$fa_time', '$yushou_lou', '$yushou_tao', '$yushou_mianji', '$yushou_yongtu', '$city_name', '$qiye_name')";
		$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
		if (mysqli_affected_rows($conn) == 1) {
			ShowMsg('这条数据已经添加成功','yushou_add.php');
			$_POST = array();	
		} else {
			echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>';
			//ShowMsg('程序发生错误，请重新添加',null);			
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
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">预售数据添加</div>
	<div class="portlet-body">
	<form action="yushou_add.php" method="post">
		<div class="control-group">
			<label class="control-label">项目名称</label>
			<div class="controls">
				<input type="text" name="pro_name" class="add-in w600" value="" />
			</div>
		</div> 
		<div class="control-group">
			<label class="control-label">预售证号</label>
			<div class="controls">
				<input type="text" name="yushou_id" class="add-in w600" value="" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">发证日期</label>
			<div class="controls">
				<input type="text" name="fa_time" class="add-in" onClick="WdatePicker()" value="" />时间格式例如：2014-06-06
			</div>
		</div>  
		<div class="control-group">
			<label class="control-label">预售楼栋</label>
			<div class="controls">
				<input type="text" name="yushou_lou" class="add-in w600" value="" />
			</div>
		</div> 
		<div class="control-group">
			<label class="control-label">预售套数</label>
			<div class="controls">
				<input type="text" name="yushou_tao" class="add-in" value="" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">预售面积(㎡)</label>
			<div class="controls">
				<input type="text" name="yushou_mianji" class="add-in" value="" />默认保留2位小数
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label">用途</label>
			<div class="controls">
				<input type="text" name="yushou_yongtu" class="add-in" value="" />住宅/商住楼/商业用房等
			</div>
		</div>			
        <div class="control-group">
			<label class="control-label">区域</label>
			<div class="controls">
				<select name="city_name" class="select-city">
                    <?php 
					$q = "SELECT city_name FROM f_city ORDER BY city_id ASC";		
					$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
					if (@mysqli_num_rows($r) > 0) {
						while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
							echo '<option value="'.$row[0].'">'.$row[0].'</option>';							
						}
						mysqli_free_result ($r);
					} else {
						echo '<option>暂无区域，请先添加一个新区域</option>';
					}
					mysqli_close($conn);
					?>
				</select>
			</div>
		</div>		
        <div class="control-group">
			<label class="control-label">开发商</label>
			<div class="controls">
				<input type="text" name="qiye_name" class="add-in w600" value="" />
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