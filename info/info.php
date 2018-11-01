<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '竞品监控信息';
include ('../header.php');
require (MYSQL);
$display = $info_fenye; // 设置每一页显示的记录数
if (isset($_GET['p']) && is_numeric($_GET['p'])) {
	$pages = $_GET['p'];
} else {
	$q = "SELECT COUNT(id) FROM f_tudi";
	$r = @mysqli_query ($conn, $q);
	$row = @mysqli_fetch_array($r, MYSQLI_NUM);
	$records = $row[0];
	if ($records > $display) {
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
}
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}
$q = "SELECT id, info_name, city_name, fa_time, fa_time, info FROM f_info ORDER BY fa_time DESC LIMIT $start, $display";	
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));

?>


<div class="portlet wrap mT50">
	<div class="portlet-title">竞品监控信息</div>
	<div class="portlet-body">
        <table id="datatable" class="tableStyle ">
        <thead>
            <tr>
                <th>项目名称</th>
				<th>区域</th>
				<th>发生日期</th>
                <th>内容</th>
                <th>编辑</th>
            </tr>
         </thead>
         <tbody>
		 <?php 	 
			while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			echo '<tr>
					<td>' . $row['info_name'] . '</td>
					<td>' . $row['city_name'] . '</td>
					<td>' . $row['fa_time'] . '</td>
					<td>' . htmlspecialchars_decode($row['info']) . '</td>
					<td><a href="info_edit.php?id=' . $row['id'] . '">Edit</a></td>
				</tr>
				';       
			}
			mysqli_free_result ($r);
			mysqli_close($conn);		
		 ?>
		 <tr>
			 <td colspan="10">
<div class="pageBox">
<?php   
if ($pages > 1) {
	$current_page = ($start/$display) + 1;
	if ($current_page != 1) {
		echo '<a class="disabled" href="info.php?s=' . ($start - $display) . '&p=' . $pages . '">&lt;</a> ';
	}            
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="info.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	if ($current_page != $pages) {
		echo '<a class="disabled" href="info.php?s=' . ($start + $display) . '&p=' . $pages . '">&gt;</a>';
	}
}	
?>                
</div> 				 
			 </td>
		 </tr>
        </tbody>
        </table>	
    </div>
</div>
 
<?php
include ('../footer.php');
?>