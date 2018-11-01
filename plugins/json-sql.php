<?php

//本文件功能是将json数据导入数据库，
//由于抓取过爱房网数据，才写了以下这段代码。





//定义个常量，用来授权调用inc里面的文件，注释掉将关闭本功能
//define('FEI_GE',true);

require ('../inc/config.php');
require (MYSQL);//连接数据库
$data ='';//添加json数据

$array = json_decode($data, true);

$values = array();  

foreach ($array as $k => $v) {
	//echo "('" . $k . "', '" . $v . "')";
	//echo $v."<br>";
	//echo $v['date'].$v['num'].$v['area']."<br>";
	$values[] = "'" . $v['date'] . "', " . $v['num'] . ", " . $v['area'] . "";//日期、套数、面积
} 

for ($i=0; $i<count($values); $i++) {

	$q = "INSERT INTO f_chengjiao (city_name, fang_time, taoshu, area, wuye_name, os_time) VALUES ('湾里区', ".$values[$i].",'住宅', NOW())";
	$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));

	if (mysqli_affected_rows($conn) == 1) {
		echo '一共是'.count($values).'条数据，目前第'.$i.'已经添加成功<br>';
	} else {
		echo '程序发生错误，请重新添加'. $q; 			
	}
}


?>