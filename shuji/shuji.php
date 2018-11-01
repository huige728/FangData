<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '各区域住宅成交情况';
include ('../header.php');
require (MYSQL);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">【<?php echo $shuji_city_name ?>】---【<?php echo $shuji_wuye_name ?>】下最新入库的<?php echo $shuji_fenye ?>条数据</div>
	<div class="portlet-body">
	
		<div id="container" style="min-width:800px;height:400px;margin:10px 0;"></div>
        <table id="datatable" class="tableStyle">
        <thead>
            <tr>
                <th></th>
                <th>成交面积（㎡）</th>
                <th>成交套数（套）</th>                
            </tr>
         </thead>
         <tbody>
		 <?php 		

			$result = array('date' => array(), 'area' => array(), 'taoshu' => array());
		 
			$q3 = "SELECT fang_time, area, taoshu FROM f_chengjiao WHERE wuye_name='".$shuji_wuye_name."' AND city_name='".$shuji_city_name."' ORDER BY fang_time DESC LIMIT $shuji_fenye";	
			$r3 = mysqli_query ($conn, $q3) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
			while ($row3 = mysqli_fetch_array ($r3, MYSQLI_NUM)) {
			
			$result['date'][] = $row3[0];
			$result['area'][] = (float)$row3[1];
			$result['taoshu'][] = (int)$row3[2];
			
			echo "<tr>
						<td>$row3[0]</td>
						<td>$row3[1]</td>
						<td>$row3[2]</td>
					</tr>";        
			}			
			mysqli_free_result ($r3);
			mysqli_close($conn);		
		 ?>
	    </tbody>
        </table>   


        
    </div>
</div>
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/highcharts/highcharts.js"></script>
  
<script type="text/javascript">
Highcharts.chart('container', {
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
    xAxis: [{
        categories:<?php echo json_encode(array_reverse($result['date'])) ?>,
        crosshair: true
    }],
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
        data: <?php echo json_encode(array_reverse($result['area'])) ?>,
        tooltip: {
            valueSuffix: ' ㎡'
        }

    }, {
        name: '成交套数（套）',
		color: '#e02222',
        type: 'spline',
        data: <?php echo json_encode(array_reverse($result['taoshu'])) ?>,
        tooltip: {
            valueSuffix: ' 套'
        }
    }]
	
});
</script>
  
<?php
include ('../footer.php');
?>