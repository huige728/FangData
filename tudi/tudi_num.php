<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '土地搜索结果-搜索';
include ('../header.php');
require (MYSQL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);
	$errors = array();
	if (!empty($trimmed['num'])) {	
		$tudi_id = mysqli_real_escape_string($conn, $trimmed['num']);
	} else {
		$errors[] = '地块编号不能为空';
	}

	if (empty($errors)) {
		$q = "SELECT id, tudi_id, city_name, weizhi, fa_time, chengjiao_time, mianji_m, qipaijia, is_cheng FROM f_tudi WHERE tudi_id = '".$tudi_id."' ORDER BY fa_time";
		$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));			
	}else {
			foreach ($errors as $msg) {

			}
		//echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>';
		ShowMsg("$msg",null);			
	}		

	
} 


?>

<div class="portlet wrap mT50">
	<div class="portlet-title">土地搜索结果</div>
	<div class="portlet-body">
        <table id="datatable" class="tableStyle ">
        <thead>
            <tr>
                <th>地块编号</th>
				<th>区域</th>
				<th>地块位置</th>
                <th>发布日期</th>
                <th>成交日期</th>
				<th>占地面积（亩）</th>
				<th>起拍价（万/亩）</th>
                <th>交易状态</th>
                <th>查看详情</th>
                <th>编辑</th>
            </tr>
        </thead>
        <tbody>
		<?php
			if (@mysqli_num_rows($r) > 0) {
				while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
				echo '<tr>
						<td>' . $row['tudi_id'] . '</td>
						<td>' . $row['city_name'] . '</td>
						<td>' . $row['weizhi'] . '</td>
						<td>' . $row['fa_time'] . '</td>
						<td>' . $row['chengjiao_time'] . '</td>
						<td>' . $row['mianji_m'] . '</td>
						<td>' . $row['qipaijia'] . '</td>
						<td>' . $row['is_cheng'] . '</td>
						<td><a href="tudi_show.php?id=' . $row['id'] . '">详情</a></td>
						<td><a href="tudi_edit.php?id=' . $row['id'] . '">Edit</a></td>
					</tr><tr><td colspan="10"></td></tr>
					';       
				}
				mysqli_free_result ($r);
				mysqli_close($conn);						

			} else {
				echo '<tr><td colspan="10">No search results were found.</td></tr>';
			} 
		?>
		

        </tbody>
        </table>	
    </div>
</div>
 
<?php
include ('../footer.php');
?>