<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
require (MYSQL);

$city_name = $_POST['city_name'] ? $_POST['city_name'] : '';
$wuye_name = $_POST['wuye_name'] ? $_POST['wuye_name'] : '';
$start_time = $_POST['start_time'] ? $_POST['start_time'] : '';
$end_time = $_POST['end_time'] ? $_POST['end_time'] : '';

if($start_time && $end_time){
	$q = "SELECT fang_time, area, taoshu FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND city_name='".$city_name."' AND fang_time >='".$start_time."' AND fang_time<='".$end_time."' ORDER BY fang_time";
}else if($start_time)
{    
	$q = "SELECT fang_time, area, taoshu FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND city_name='".$city_name."' AND fang_time ='".$start_time."' ORDER BY fang_time";
}else if($end_time)
{
	$q = "SELECT fang_time, area, taoshu FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND city_name='".$city_name."' AND fang_time ='".$end_time."' ORDER BY fang_time";	
}else
{
	$q = "SELECT fang_time, area, taoshu FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND city_name='".$city_name."' ORDER BY fang_time DESC LIMIT 7";		
}

$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
if (@mysqli_num_rows($r) > 0) {
    $str = '<table id="datatable" class="tableStyle"><thead>
				<tr>
					<th></th>
					<th>成交面积（㎡）</th>
					<th>成交套数（套）</th>
					
				</tr>
			 </thead>
			 <tbody>';
    while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
	$str .= "<tr>
				<td>$row[0]</td>
				<td>$row[1]</td>
				<td>$row[2]</td>
				
			</tr>";        
    }
    $str .= '</tbody></table>';
    echo $str;
    mysqli_free_result ($r);
} else {
    echo 'No search results were found.';
}
mysqli_close($conn);
?>