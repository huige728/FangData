<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
require (MYSQL);

$wuye_name = $_POST['wuye_name'] ? $_POST['wuye_name'] : '';
$start_time = $_POST['start_time'] ? $_POST['start_time'] : '';
$end_time = $_POST['end_time'] ? $_POST['end_time'] : '';

if($start_time && $end_time){
   $q = "SELECT city_name, IF(area is NULL,0,SUM(area)), IF(taoshu is NULL,0,SUM(taoshu)) FROM f_city AS fc LEFT JOIN (SELECT * FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND fang_time >='".$start_time."' AND fang_time<='".$end_time."') AS fcj USING (city_name) GROUP BY fc.city_id";
}else if($start_time)
{    
    $q = "SELECT city_name, IF(area is NULL,0,SUM(area)), IF(taoshu is NULL,0,SUM(taoshu)) FROM f_city AS fc LEFT JOIN (SELECT * FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND fang_time ='".$start_time."') AS fcj USING (city_name) GROUP BY fc.city_id";
}else if($end_time)
{
    $q = "SELECT city_name, IF(area is NULL,0,SUM(area)), IF(taoshu is NULL,0,SUM(taoshu)) FROM f_city AS fc LEFT JOIN (SELECT * FROM f_chengjiao WHERE wuye_name='".$wuye_name."' AND fang_time ='".$end_time."') AS fcj USING (city_name) GROUP BY fc.city_id";
}else
{
    exit('请输入时间');
}

$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
if (@mysqli_num_rows($r) > 0) {
    $str = '<thead>
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
    $str .= '</tbody>';
    echo $str;
    mysqli_free_result ($r);
} else {
    echo '<thead>
			<tr>
				<th></th>
				<th>成交面积（㎡）</th>
				<th>成交套数（套）</th>                
			</tr>
		  </thead>';
}
mysqli_close($conn);
?>