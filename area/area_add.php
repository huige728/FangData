<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '区域添加';
include ('../header.php');
require (MYSQL);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);
	$errors = array();
	if(empty($trimmed['city_name'])) {
		$errors[] = '区域不能为空';
	} else {
		$c = mysqli_real_escape_string ($conn, $trimmed['city_name']);
	}

	
	if (empty($errors)) {
		$q = 'INSERT INTO f_city (city_name) VALUES (?)';
		$stmt = mysqli_prepare($conn, $q);
		mysqli_stmt_bind_param($stmt, 's', $c);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) == 1) {
			//echo '<p>已经添加成功</p>';
			ShowMsg('已经添加成功','area_add.php');
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
	<div class="portlet-title">区域添加</div>
	<div class="portlet-body">
		<form action="area_add.php" method="post">
			<div class="control-group">
				<label class="control-label">请添加一个新地区</label>
				<div class="controls">
					<input type="text" name="city_name" class="add-in" value="" />
				</div>
			</div> 
			<div class="form-actions">
				<button class="btn" name="submit" type="submit">提交</button>
			</div>   
		</form>
    </div>
</div>

<div class="portlet wrap mT50">
	<div class="portlet-title">已有区域</div>
	<div class="portlet-body">
        <table id="datatable" class="tableStyle">
			<thead>
				<tr>
					<th>ID</th>
					<th>区域</th>
					<th>修改</th>
				</tr>
			 </thead>
			 <tbody>
				<?php 
				$q = "SELECT city_id, city_name FROM f_city ORDER BY city_id ASC";		
				$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
				if (@mysqli_num_rows($r) > 0) {
					while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
					echo "<tr>
								<td>$row[0]</td>
								<td>$row[1]</td>
								<td><a href=\"area_edit.php?id=$row[0]\">Edit</a></td>
							</tr>"; 
					}
					mysqli_free_result ($r);
				} else {
					echo '<option>暂无区域，请添加一个新地区</option>';
				}
				mysqli_close($conn);
				?>			 
			</tbody>
        </table> 
    </div>
</div>
<?php
include ('../footer.php');
?>