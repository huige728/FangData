<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '房屋性质添加';
include ('../header.php');
require (MYSQL);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);
	$errors = array();
	if(empty($trimmed['wuye_name'])) {
		$errors[] = '房屋性质不能为空';
	} else {
		$c = mysqli_real_escape_string ($conn, $trimmed['wuye_name']);
	}

	
	if (empty($errors)) {
		$q = 'INSERT INTO f_wuye (wuye_name) VALUES (?)';
		$stmt = mysqli_prepare($conn, $q);
		mysqli_stmt_bind_param($stmt, 's', $c);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) == 1) {
			//echo '<p>已经添加成功</p>';
			ShowMsg('已经添加成功','wuye_add.php');
			$_POST = array();	
		} else {
			echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>'; 
			//ShowMsg('程序发生错误，请重新添加',null);
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
	<div class="portlet-title">房屋性质添加</div>
	<div class="portlet-body">
		<form action="wuye_add.php" method="post">
			<div class="control-group">
				<label class="control-label">请添加一个新房屋性质</label>
				<div class="controls">
					<input type="text" name="wuye_name" class="add-in" value="" />例子：住宅、非住宅、公寓、写字楼、商铺等
				</div>
			</div> 
			<div class="form-actions">
				<button class="btn" name="submit" type="submit">提交</button>
			</div>   
		</form>
    </div>
</div>

<div class="portlet wrap mT50">
	<div class="portlet-title">已有房屋性质</div>
	<div class="portlet-body">
        <table id="datatable" class="tableStyle">
			<thead>
				<tr>
					<th>ID</th>
					<th>房屋性质</th>
					<th>修改</th>
				</tr>
			 </thead>
			 <tbody>
				<?php 
				$q = "SELECT wuye_id, wuye_name FROM f_wuye ORDER BY wuye_id ASC";		
				$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
				if (@mysqli_num_rows($r) > 0) {
					while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
					echo "<tr>
								<td>$row[0]</td>
								<td>$row[1]</td>
								<td><a href=\"wuye_edit.php?id=$row[0]\">Edit</a></td>
							</tr>"; 
					}
					mysqli_free_result ($r);
				} else {
					echo '<option>暂无区域，请添加一个新房屋性质</option>';
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