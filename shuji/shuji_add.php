<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '区域成交数据添加';
include ('../header.php');
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
		$q = 'INSERT INTO f_chengjiao (city_id, area, taoshu, fang_time, os_time) VALUES (?, ?, ?, ?, NOW())';
		$stmt = mysqli_prepare($conn, $q);
		mysqli_stmt_bind_param($stmt, 'idis', $c, $a, $t, $w);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) == 1) {
			//echo '<p>这条数据已经添加成功</p>';
			ShowMsg('这条数据已经添加成功','zhuzhai_add.php');
			$_POST = array();	
		} else {
			//echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>'; 
			ShowMsg('程序发生错误，请重新添加',null);
		}	
		mysqli_stmt_close($stmt);	
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
	<div class="portlet-title">区域成交数据添加</div>
	<div class="portlet-body">
	<form action="zhuzhai_add.php" method="post">
		<div class="control-group">
			<label class="control-label">时间</label>
			<div class="controls">
				<input type="text" name="wdate" class="add-in" onClick="WdatePicker()" value="" />时间格式例如：2014-06-06
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
					
					?>
				</select>
			</div>
		</div>
        <div class="control-group">
			<label class="control-label">房屋性质</label>
			<div class="controls">
				<select name="wuye_name" class="select-city">
                    <?php 
					$q2 = "SELECT wuye_name FROM f_wuye ORDER BY wuye_id ASC";		
					$r2 = mysqli_query ($conn, $q2) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
					if (@mysqli_num_rows($r2) > 0) {
						while ($row2 = mysqli_fetch_array ($r2, MYSQLI_NUM)) {
							echo '<option value="'.$row2[0].'">'.$row2[0].'</option>';							
						}
						mysqli_free_result ($r2);
					} else {
						echo '<option>暂无房屋性质，请先添加一个房屋性质</option>';
					}
					mysqli_close($conn);
					?>
				</select>
			</div>
		</div>		
		
        <div class="control-group">
			<label class="control-label">套数</label>
			<div class="controls">
				<input type="text" name="taoshu" class="add-in" value="" />套
			</div>
		</div> 
        <div class="control-group">
			<label class="control-label">面积</label>
			<div class="controls">
				<input type="text" name="area" class="add-in" value="" />平方(默认保留2位小数)
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