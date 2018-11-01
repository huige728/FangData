<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '土地搜索结果-搜索';
include ('../header.php');
require (MYSQL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);

	if (!empty($trimmed['start_time1'])) {
		$start_time1 = mysqli_real_escape_string ($conn, $trimmed['start_time1']);
	} else {
		$start_time1 = '';
	}
	
	if (!empty($trimmed['end_time1'])) {
		$end_time1 = mysqli_real_escape_string ($conn, $trimmed['end_time1']);
	} else {
		$end_time1 = '';
	}

	

	if($start_time1 && $end_time1){
		
		$sql = " fa_time >='".$start_time1."' AND fa_time<='".$end_time1."' ";
		
	}else if($start_time1){   
	
		$sql = " fa_time ='".$start_time1."' ";
		
	}else if($end_time1){
		
		$sql = " fa_time ='".$end_time1."' ";
		
	}else{
		ShowMsg('请按正确格式请输入时间',null);		
	}

$q = "SELECT id, tudi_id, city_name, yongtu, fa_time, chengjiao_time, mianji_m, mianji_p, guihua_p, qipaijia, is_cheng, chengjiazongjia FROM f_tudi WHERE ".$sql." ORDER BY id";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));

$q2 = "SELECT SUM(mianji_m), SUM(mianji_p), SUM(guihua_p), SUM(chengjiazongjia) FROM f_tudi WHERE ".$sql." ";
$r2 = mysqli_query ($conn, $q2) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));	

	
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
				<th>土地用途</th>
                <th>发布日期</th>
                <th>成交日期</th>
				<th>占地面积（亩）</th>
				<th>占地面积（㎡）</th>
				<th>总规划建筑面积（㎡）</th>
				<th>起拍价（万/亩）</th>
                <th>交易状态</th>
				<th>成交总地价（万元）</th>
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
						<td>' . $row['yongtu'] . '</td>
						<td>' . $row['fa_time'] . '</td>
						<td>' . $row['chengjiao_time'] . '</td>
						<td>' . $row['mianji_m'] . '</td>
						<td>' . $row['mianji_p'] . '</td>
						<td>' . $row['guihua_p'] . '</td>
						<td>' . $row['qipaijia'] . '</td>
						<td>' . $row['is_cheng'] . '</td>
						<td>' . $row['chengjiazongjia'] . '</td>
						<td><a href="tudi_show.php?id=' . $row['id'] . '">详情</a></td>
						<td><a href="tudi_edit.php?id=' . $row['id'] . '">Edit</a></td>
					</tr>
					';       
				}
				
				
				$row2 = mysqli_fetch_array ($r2, MYSQLI_NUM);
				echo '<tr><td colspan="13">

						查询数据统计｛
						总占地面积（亩）：' .$row2[0]. '；
						总占地面积（㎡）：' .$row2[1]. '；
						总规划建筑面积（㎡）：' .$row2[2]. '；
						成交总地价（万元）：' .$row2[3]. '；｝				
				
					 </td></tr>';				

				mysqli_free_result ($r);
				mysqli_close($conn);						

				
				
				
			} else {
				echo '<tr><td colspan="13">No search results were found.</td></tr>';
			} 
		?>	
		
        </tbody>
        </table>	
    </div>
</div>
 
<?php
include ('../footer.php');
?>