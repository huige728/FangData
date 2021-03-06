<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '各区域成交对比';
include ('../header.php');
require (MYSQL);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">各区域【一段时间内】成交对比（默认出现每个区域最后更新的一条数据）</div>
	<div class="portlet-body">

    	<div class="datePickerBlock">
		房屋性质	
			<select id="wuye_name" name="wuye_name" class="select-city">
				<?php 
				$q2 = "SELECT wuye_name FROM f_wuye ORDER BY wuye_id ASC";		
				$r2 = mysqli_query ($conn, $q2) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
				if (@mysqli_num_rows($r2) > 0) {
					while ($row2 = mysqli_fetch_array ($r2, MYSQLI_NUM)) {
						echo '<option value="'.$row2[0].'">'.$row2[0].'</option>';							
					}
					mysqli_free_result ($r2);
				} else {
					echo '<option>暂无房屋性质，请先添加一个房屋性质</option>';
				}
				
				?>
			</select>		
		
		自定义时间范围	
            <input id="start_time" name="start_time" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />至<input id="end_time" name="end_time" class="hasDatePicker" onClick="WdatePicker()" type="text" size="16" value="" />
			<input class="btn" type="submit" id="submit" value="查询" />
        </div>

		<div id="container" style="margin:10px 0;"></div>
        <table id="datatable" class="tableStyle">
        <thead>
            <tr>
                <th>区域</th>
				<th>时间</th>
                <th>成交面积（㎡）</th>
                <th>成交套数（套）</th>
				<th>编辑</th>
            </tr>
         </thead>
         <tbody>
		 <?php 	 

			$q = "select * from (select * from f_chengjiao order by fang_time desc) as a group by city_name ORDER BY id ASC";			
			
			$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
			while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
				
			echo '<tr>
					<td>' . $row['city_name'] . '</td>
					<td>' . $row['fang_time'] . '</td>
					<td>' . $row['area'] . '</td>
					<td>' . $row['taoshu'] . '</td>
					<td><a href="shuji_edit.php?id=' . $row['id'] . '">Edit</a></td>
				</tr>
				';
	
			}
			mysqli_free_result ($r);
			mysqli_close($conn);		
		 ?>
        </tbody>
        </table>           
    </div>
</div>

<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/WdatePicker.js"></script>
<script type="text/javascript" src="../js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="../js/highcharts/data.js"></script>
<script type="text/javascript" src="../js/highcharts/highcharts-zh_CN.js"></script>
<script type="text/javascript">

$(function () {
    $("#submit").click(function(){	
		$.post("shuji_area_ajax.php", { 
				wuye_name :  $("#wuye_name").val() , 
				start_time :  $("#start_time").val() , 
				end_time :  $("#end_time").val()  
			}, function (data, textStatus){
				$("#container").show();
				$("#datatable").empty(); // 清空datatable的元素内容，以便重新添加新的HTML
				$("#datatable").html(data); // 把返回的数据添加到页面上
			
			
$("#container").highcharts({
	data: {
		table: 'datatable',
	},	
	chart: {
		zoomType: 'xy'
	},
	title: {
		text: '销售统计',
		style: {
			color: '#e02222'
		}
	},
	subtitle: {
		text: '来源：房管局'
	},
	yAxis: [{ // Primary yAxis
		labels: {
			format: '{value}套',
			style: {
				color: Highcharts.getOptions().colors[1]
			}
		},
		title: {
			text: '成交套数（套）',
			style: {
				color: Highcharts.getOptions().colors[1]
			}
		}
	}, { // Secondary yAxis
		title: {
			text: '成交面积（m²）',
			style: {
				color: Highcharts.getOptions().colors[0]
			}
		},
		labels: {
			formatter: function() {
				return (this.value/10000)+'万' ;
			},
			style: {
				color: Highcharts.getOptions().colors[0]
			}
		},
		opposite: true
	}],
	credits: {//关闭版权链接
		enabled: false
	},
	tooltip: {
		shared: true
	},
	series: [{
		name: '成交面积（m²）',
		color: '#4572A7',
		type: 'column',
		yAxis: 1,
		tooltip: {
			valueSuffix: ' ㎡'
		}
	}, {
		name: '成交套数（套）',
		color: '#e02222',
		type: 'spline',
		tooltip: {
			valueSuffix: ' 套'
		}
	}]    			
});			
			
		
			
			}
		);
		return false;	
	});
});	
</script>

<?php
include ('../footer.php');
?>