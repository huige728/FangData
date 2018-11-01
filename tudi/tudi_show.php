<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '土地信息-详情页';
include ('../header.php');
require (MYSQL);
$row = FALSE;
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
	$id = $_GET['id'];
	$q = "SELECT tudi_id, city_name, weizhi, yongtu, rongjilv, midu, lvdi, fa_time, chengjiao_time, jiezhi_time, baozhengjin, mianji_m, mianji_p, guihua_p, qipaijia, chengjiaodanjia, chengjiazongjia, loumiandijia, yijialv, jingderen, qishijia, dituweizhi, xuzhi, is_cheng FROM f_tudi WHERE id=$id LIMIT 1";
	$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
	$num = mysqli_num_rows($r);
	if ($num > 0) {
		$row = mysqli_fetch_array ($r, MYSQLI_ASSOC) ;
		mysqli_free_result ($r);
	} else {
		echo '没有找到这条数据，请确定你输入了这条数据';
	}
} 
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">土地信息-详情页</div>
	<div class="portlet-body">
        <table class="tableStyle ">
        <thead>
            <tr>
                <th>地块编号</th>
				<th>区域</th>
                <th>地块位置</th>
                <th>土地用途</th>
                <th>容积率(FAR)</th>
                <th>建筑密度（D）</th>
                <th>绿地率(GAR)</th>
            </tr>
         </thead>
         <tbody>
            <tr>
                <td><?php echo $row['tudi_id']?></td>
                <td><?php echo $row['city_name']?></td>
				<td><?php echo $row['weizhi']?></td>
                <td><?php echo $row['yongtu']?></td>
				<td><?php echo $row['rongjilv']?></td>
                <td><?php echo $row['midu']?></td>
                <td><?php echo $row['lvdi']?></td>
			<tr>
        </tbody>
        </table> 
        <table class="tableStyle mT50">
        <thead>
            <tr>
                <th>发布日期</th>
                <th>成交日期</th>
				<th>保证金截止时间</th>
				<th>竞买保证金（万元）</th>
                <th>占地面积（亩）</th>
				<th>占地面积（㎡）</th>
                <th>规划建筑面积（㎡）</th>
                <th>起拍价（万/亩）</th>
				<th>起始价（万元）</th>				
            </tr>
         </thead>
         <tbody>
            <tr>
                <td><?php echo $row['fa_time']?></td>
                <td><?php echo $row['chengjiao_time']?></td>
				<td><?php echo $row['jiezhi_time']?></td>
				<td><?php echo $row['baozhengjin']?></td>
				<td><?php echo $row['mianji_m']?></td>
                <td><?php echo $row['mianji_p']?></td>
				<td><?php echo $row['guihua_p']?></td>
                <td><?php echo $row['qipaijia']?></td>
				<td><?php echo $row['qishijia']?></td>
			<tr>
        </tbody>
        </table> 




		
        <table class="tableStyle mT50">
        <thead>
            <tr>           				
                <th>成交单价（万/亩）</th>
                <th>成交总地价（万元）</th>
                <th>楼面地价</th>
				<th>溢价率</th>
				<th>竞得人</th>
				<th>交易状态</th>
            </tr>
         </thead>
         <tbody>
            <tr> 

                <td>
				<?php 
				if($row['chengjiaodanjia']==0){
					echo '暂无交易，没有数据';
				} else {
					echo $row['chengjiaodanjia'];
				}			
				?>				
				</td>
                <td>
				<?php 
				if($row['chengjiazongjia']==0){
					echo '暂无交易，没有数据';
				} else {
					echo $row['chengjiazongjia'];
				}							
				?>
				</td>
                <td>
				<?php 
				if($row['loumiandijia']==0){
					echo '暂无交易，没有数据';
				} else {
					echo $row['loumiandijia'];
				}				
				?>
				</td>
                <td>
				<?php 
				if(empty($row['yijialv'])){
					echo '暂无交易，没有数据';
				} else {
					echo $row['yijialv'];
				}				
				?>
				</td>
                <td>
				<?php 
				if(empty($row['jingderen'])){
					echo '暂无交易，没有数据';
				} else {
					echo $row['jingderen'];
				}				
				?>
				</td>
                <td>
				<?php echo $row['is_cheng'];?>	
				</td>				
				
			<tr>
        </tbody>
        </table>
		<div class="tableBox mT50 clearfix">
			<div class="tableBox-L l">
<?php 
if(!empty($row['dituweizhi'])){
	echo '<img src="'. $row['dituweizhi'] .'" />';
} else {
	echo 'No pictures found';
}
?>
			</div>
			<div class="tableBox-R"><?php echo htmlspecialchars_decode($row['xuzhi'])?></div>
		</div>
    </div>
</div>
<?php
mysqli_close($conn);
include ('../footer.php');
?>