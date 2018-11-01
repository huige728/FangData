<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
require (MYSQL);
$start_time = $_POST['start_time'] ? $_POST['start_time'] : '';
$end_time = $_POST['end_time'] ? $_POST['end_time'] : '';

if($start_time && $end_time)
{
	$sql = " fa_time >='".$start_time."' AND fa_time<='".$end_time."' ";
}else if($start_time)
{    
	$sql = " fa_time ='".$start_time."' ";
}else if($end_time)
{
	$sql = " fa_time ='".$end_time."' ";
}else
{
    exit('请按正确格式请输入时间');
}
$q = "SELECT * FROM f_yushou WHERE ".$sql." ORDER BY id DESC";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));

$q2 = "SELECT SUM(yushou_tao), SUM(yushou_mianji) FROM f_yushou WHERE ".$sql." ";
$r2 = mysqli_query ($conn, $q2) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));

if (@mysqli_num_rows($r) > 0) {
    $str = '<thead>
				<tr>
					<th>项目名称</th>
					<th>预售证号</th>
					<th>发证日期</th>
					<th>预售楼栋</th>
					<th>预售套数</th>
					<th>预售面积(㎡)</th>
					<th>用途</th>
					<th>区域</th>
					<th>开发商</th>
					<th>编辑</th>               
				</tr>
			 </thead>
			 <tbody>';
	while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
	$str .= '<tr>
				<td>' . $row['pro_name'] . '</td>
				<td>' . $row['yushou_id'] . '</td>
				<td>' . $row['fa_time'] . '</td>
				<td>' . $row['yushou_lou'] . '</td>
				<td>' . $row['yushou_tao'] . '</td>
				<td>' . $row['yushou_mianji'] . '</td>
				<td>' . $row['yushou_yongtu'] . '</td>
				<td>' . $row['city_name'] . '</td>
				<td>' . $row['qiye_name'] . '</td>
				<td><a href="yushou_edit.php?id=' . $row['id'] . '">Edit</a></td>
			</tr>';      
	}	
	
	$row2 = mysqli_fetch_array ($r2, MYSQLI_NUM);
	$str .= "<tr><td colspan=\"10\">查询数据统计｛预售总套数：$row2[0]；预售总面积：$row2[1]；｝</td></tr></tbody>";
	echo $str;
	mysqli_free_result ($r);
	mysqli_free_result ($r2);
} else {
    echo '<thead>
			<tr>
				<th>项目名称</th>
				<th>预售证号</th>
				<th>发证日期</th>
				<th>预售楼栋</th>
				<th>预售套数</th>
				<th>预售面积(㎡)</th>
				<th>区域</th>
				<th>开发企业名称</th>
				<th>编辑</th>                
			</tr>
		  </thead>';
}
mysqli_close($conn);
?>