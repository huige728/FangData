<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '土地信息';
include ('../header.php');
require (MYSQL);
$display = $tudi_fenye; // 设置每一页显示的记录数
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
$q = "SELECT id, tudi_id, city_name, weizhi, fa_time, chengjiao_time, mianji_m, qipaijia, is_cheng FROM f_tudi ORDER BY fa_time DESC LIMIT $start, $display";	
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));

?>

<div class="portlet wrap mT50">
	<div class="portlet-title">搜索</div>
	<div class="portlet-body">
	    <div class="datePickerBlock">
		
		<form action="tudi_num.php" method="post">
            地块编号
			<input name="num" class="hasDatePicker" type="text" size="16" value="" />
			<input class="btn" type="submit" id="submit"  value="查询" />
		</form>	
        </div>
    </div>
</div>

<div class="portlet wrap mT50">
	<div class="portlet-title">搜索--tip：搜索结果不分页，可把搜索结果复制到excel上进行筛选地区</div>
	<div class="portlet-body">
	    <div class="datePickerBlock">
		
		<form action="tudi_time1.php" method="post">
			发布日期
			<input id="start_time1" name="start_time1" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />至<input id="end_time1" name="end_time1" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />
			
			<input class="btn" type="submit" id="submit"  value="查询" />

		</form>	
        </div>
    </div>
</div>

<div class="portlet wrap mT50">
	<div class="portlet-title">搜索--tip：搜索结果不分页，可把搜索结果复制到excel上进行筛选地区</div>
	<div class="portlet-body">
	    <div class="datePickerBlock">
		
		<form action="tudi_time2.php" method="post">
            成交日期
            <input id="start_time2" name="start_time2" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />至<input id="end_time2" name="end_time2" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />	
			<input class="btn" type="submit" id="submit"  value="查询" />
		</form>	
        </div>
    </div>
</div>






<div class="portlet wrap mT50">
	<div class="portlet-title">土地信息</div>
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
		echo '<a class="disabled" href="tudi.php?s=' . ($start - $display) . '&p=' . $pages . '">&lt;</a> ';
	}            
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="tudi.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	if ($current_page != $pages) {
		echo '<a class="disabled" href="tudi.php?s=' . ($start + $display) . '&p=' . $pages . '">&gt;</a>';
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
<script type="text/javascript" src="../js/WdatePicker.js"></script>
<?php
include ('../footer.php');
?>