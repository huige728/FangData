<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '预售信息';
include ('../header.php');
require (MYSQL);
$display = $yushou_fenye; // 设置每一页显示的记录数
if (isset($_GET['p']) && is_numeric($_GET['p'])) {
	$pages = $_GET['p'];
} else {
	$q = "SELECT COUNT(id) FROM f_yushou";
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

$q = "SELECT * FROM f_yushou ORDER BY id DESC LIMIT $start, $display";
	
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">预售信息</div>
	<div class="portlet-body">
    	<div class="datePickerBlock">发证日期
            <input id="start_time" name="start_time" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />至<input id="end_time" name="end_time" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />
            <input class="btn" type="submit" id="submit" value="查询" />
        </div>
        <table id="datatable" class="tableStyle">
        <thead>
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
         <tbody>
		 <?php 	 
			while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			echo '<tr>
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
		echo '<a class="disabled" href="yushou.php?s=' . ($start - $display) . '&p=' . $pages . '">&lt;</a> ';
	}            
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="yushou.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	if ($current_page != $pages) {
		echo '<a class="disabled" href="yushou.php?s=' . ($start + $display) . '&p=' . $pages . '">&gt;</a>';
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
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/WdatePicker.js"></script>
<script>
$(function () {
    $("#submit").click(function(){	
		$.post("yushou_ajax.php", { 
				start_time :  $("#start_time").val() , 
				end_time :  $("#end_time").val() 				
			}, function (data, textStatus){
				$("#datatable").empty(); // 清空datatable的元素内容，以便重新添加新的HTML
				$("#datatable").html(data); // 把返回的数据添加到页面上
			}
		);
		return false;	
	});
});	
</script>
<?php
include ('../footer.php');
?>