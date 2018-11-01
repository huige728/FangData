<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '土地数据修改';
include ('../header.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {         // 接收来自tudi.php传递过来的id
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // 接收表单中的id
	$id = $_POST['id'];
} else { //如果这两个条件都不为TRUE，那么将不能继续处理页面，从而显示一条出错消息，并终止脚本的执行
	echo '404';
	include ('../footer.php');
	exit();
}

require (MYSQL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$trimmed = array_map('trim', $_POST);
	$errors = array();
	if (!empty($trimmed['tudi_id'])) {
		$tudi_id = mysqli_real_escape_string ($conn, $trimmed['tudi_id']);
	} else {
		$errors[] = '地块编号不能为空';
	}	
	if(empty($trimmed['city_name'])) {
		$errors[] = '请选择区域';
	} else {
		$city_name = mysqli_real_escape_string ($conn, $trimmed['city_name']);
	}
	if(empty($trimmed['weizhi'])) {
		$errors[] = '地块位置不能为空';
	} else {
		$weizhi = mysqli_real_escape_string ($conn, $trimmed['weizhi']);
	}	
	if(empty($trimmed['yongtu'])) {
		$errors[] = '土地用途不能为空';
	} else {
		$yongtu = mysqli_real_escape_string ($conn, $trimmed['yongtu']);
	}	
	if(empty($trimmed['rongjilv'])) {
		$errors[] = '容积率不能为空';
	} else {
		$rongjilv = mysqli_real_escape_string ($conn, $trimmed['rongjilv']);
	}	
	if(empty($trimmed['midu'])) {
		$errors[] = '建筑密度不能为空';
	} else {
		$midu = mysqli_real_escape_string ($conn, $trimmed['midu']);
	}	
	if(empty($trimmed['lvdi'])) {
		$errors[] = '绿地率不能为空';
	} else {
		$lvdi = mysqli_real_escape_string ($conn, $trimmed['lvdi']);
	}		
	if(empty($trimmed['fa_time'])) {
		$errors[] = '发布日期不能为空';
	} else {
		$fa_time = mysqli_real_escape_string ($conn, $trimmed['fa_time']);
	}
	if(empty($trimmed['chengjiao_time'])) {
		$errors[] = '成交日期不能为空';
	} else {
		$chengjiao_time = mysqli_real_escape_string ($conn, $trimmed['chengjiao_time']);
	}
	if(empty($trimmed['jiezhi_time'])) {
		$errors[] = '保证金截止时间不能为空';
	} else {
		$jiezhi_time = mysqli_real_escape_string ($conn, $trimmed['jiezhi_time']);
	}	
	if (is_numeric($trimmed['baozhengjin']) && ($trimmed['baozhengjin'] > 0)) {
		$baozhengjin = mysqli_real_escape_string ($conn, (float) $trimmed['baozhengjin']);
	} else {
		$errors[] = '请输入一个正确的竞买保证金（万元）';
	}
	if (is_numeric($trimmed['mianji_m']) && ($trimmed['mianji_m'] > 0)) {
		$mianji_m = mysqli_real_escape_string ($conn, (float) $trimmed['mianji_m']);
	} else {
		$errors[] = '请输入一个正确的占地面积（亩）';
	}
	if (is_numeric($trimmed['mianji_p']) && ($trimmed['mianji_p'] > 0)) {
		$mianji_p = mysqli_real_escape_string ($conn, (float) $trimmed['mianji_p']);
	} else {
		$errors[] = '请输入一个正确的占地面积（㎡）';
	}
	if (is_numeric($trimmed['guihua_p']) && ($trimmed['guihua_p'] > 0)) {
		$guihua_p = mysqli_real_escape_string ($conn, (float) $trimmed['guihua_p']);
	} else {
		$errors[] = '请输入一个正确的规划建筑面积（㎡）';
	}
	if (is_numeric($trimmed['qipaijia']) && ($trimmed['qipaijia'] > 0)) {
		$qipaijia = mysqli_real_escape_string ($conn, (float) $trimmed['qipaijia']);
	} else {
		$errors[] = '请输入一个正确的起拍价（万/亩）';
	}
	if (is_numeric($trimmed['qishijia']) && ($trimmed['qishijia'] > 0)) {
		$qishijia = mysqli_real_escape_string ($conn, (float) $trimmed['qishijia']);
	} else {
		$errors[] = '请输入一个正确的起始价（万元）';
	}	
	if (is_numeric($trimmed['chengjiaodanjia']) && ($trimmed['chengjiaodanjia'] > 0)) {
		$chengjiaodanjia = mysqli_real_escape_string ($conn, (float) $trimmed['chengjiaodanjia']);
	} else {
		$errors[] = '请输入一个正确的成交单价（万/亩）';
	}	
	if (is_numeric($trimmed['chengjiazongjia']) && ($trimmed['chengjiazongjia'] > 0)) {
		$chengjiazongjia= mysqli_real_escape_string ($conn, (float) $trimmed['chengjiazongjia']);
	} else {
		$errors[] = '请输入一个正确的成交总地价（万元）';
	}
	if (is_numeric($trimmed['loumiandijia']) && ($trimmed['loumiandijia'] > 0)) {
		$loumiandijia= mysqli_real_escape_string ($conn, (float) $trimmed['loumiandijia']);
	} else {
		$errors[] = '请输入一个正确的楼面地价';
	}
	if(empty($trimmed['yijialv'])) {
		$errors[] = '请输入一个正确的溢价率';
	} else {
		$yijialv = mysqli_real_escape_string ($conn, $trimmed['yijialv']);
	}	
	if(empty($trimmed['jingderen'])) {
		$errors[] = '请输入竞得人';
	} else {
		$jingderen = mysqli_real_escape_string ($conn, $trimmed['jingderen']);
	}	
	if(empty($trimmed['is_cheng'])) {
		$errors[] = '请输入一个交易状态';
	} else {
		$is_cheng = mysqli_real_escape_string ($conn, $trimmed['is_cheng']);
	}
	//地图位置
	$dituweizhi = (!empty($trimmed['dituweizhi'])) ? mysqli_real_escape_string ($conn, $trimmed['dituweizhi']) : NULL;
	
	//出让须知
	$xuzhi = (!empty($trimmed['xuzhi'])) ? mysqli_real_escape_string ($conn, htmlspecialchars($trimmed['xuzhi'])) : NULL;


	if (empty($errors)) {
		$q = "SELECT id FROM f_tudi WHERE tudi_id='$tudi_id' AND id != $id";
		$r = @mysqli_query($conn, $q);
		if (mysqli_num_rows($r) == 0) {
			$q = "UPDATE f_tudi SET tudi_id='$tudi_id', city_name='$city_name', weizhi='$weizhi', yongtu='$yongtu', rongjilv='$rongjilv', midu='$midu', lvdi='$lvdi', fa_time='$fa_time', chengjiao_time='$chengjiao_time', jiezhi_time='$jiezhi_time', baozhengjin='$baozhengjin', mianji_m='$mianji_m', mianji_p='$mianji_p', guihua_p='$guihua_p', qipaijia='$qipaijia', chengjiaodanjia='$chengjiaodanjia', chengjiazongjia='$chengjiazongjia', loumiandijia='$loumiandijia', yijialv='$yijialv', jingderen='$jingderen', qishijia='$qishijia', dituweizhi='$dituweizhi', xuzhi='$xuzhi', is_cheng='$is_cheng' WHERE id=$id LIMIT 1";
			$r = @mysqli_query ($conn, $q);
			if (mysqli_affected_rows($conn) == 1) { 
				ShowMsg('这条数据已经修改成功','tudi_show.php?id='.$id);				
			} else {
				//ShowMsg('由于系统错误，您暂时无法编辑，请联系管理员',null);	
				echo '<p>' . mysqli_error($conn) . '<br />Query: ' . $q . '</p>'; 
			}				
		} else {
			echo '地块编号已经被使用了';
		}		
	}	
	
} 

if ( !empty($errors) && is_array($errors) ) {
	echo '<p style="font-weight: bold; color: #C00">发生下列错误:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '请在试一遍</p>';
}

$q = "SELECT * FROM f_tudi WHERE id=$id LIMIT 1";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">土地数据修改</div>
	<div class="portlet-body">
	<form action="tudi_edit.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>" />
		<div class="control-group">
			<label class="control-label">地块编号</label>
			<div class="controls">
				<input type="text" name="tudi_id" class="add-in" value="<?php echo $row['tudi_id']?>" />例子：DAEJ2014054
			</div>
		</div> 		
        <div class="control-group">
			<label class="control-label">区域</label>
			<div class="controls">
				<select name="city_name" class="select-city">
                    <?php 
					$q2 = "SELECT city_name FROM f_city ORDER BY city_id ASC";		
					$r2 = mysqli_query ($conn, $q2) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
					if (@mysqli_num_rows($r2) > 0) {
						while ($row2 = mysqli_fetch_array ($r2, MYSQLI_NUM)) {
							echo "<option value=\"$row2[0]\"";
							if (isset($row['city_name']) && ($row['city_name'] == $row2[0]) ) echo ' selected="selected"';
							echo ">$row2[0]</option>\n";
						}
						mysqli_free_result ($r2);
					} else {
						echo '<option>暂无区域，请添加一个新地区</option>';
					}
					mysqli_close($conn);
					?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">地块位置</label>
			<div class="controls">
				<input type="text" name="weizhi" class="add-in w600" value="<?php echo $row['weizhi']?>" />
			</div>
		</div> 		
        <div class="control-group">
			<label class="control-label">土地用途</label>
			<div class="controls">
				<input type="text" name="yongtu" class="add-in" value="<?php echo $row['yongtu']?>" />例子：商业、商务、娱乐康体、居住用地
			</div>
		</div> 
        <div class="control-group">
			<label class="control-label">容积率(FAR)</label>
			<div class="controls">
				<input type="text" name="rongjilv" class="add-in" value="<?php echo $row['rongjilv']?>" />例子：1.0＜FAR≤2.273
			</div>
		</div> 
        <div class="control-group">
			<label class="control-label">建筑密度（D）</label>
			<div class="controls">
				<input type="text" name="midu" class="add-in" value="<?php echo $row['midu']?>" />例子：D≤30.46%
			</div>
		</div>
        <div class="control-group">
			<label class="control-label">绿地率(GAR)</label>
			<div class="controls">
				<input type="text" name="lvdi" class="add-in" value="<?php echo $row['lvdi']?>" />例子：GAR≥30%
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">发布日期</label>
			<div class="controls">
				<input type="text" name="fa_time" class="add-in" onClick="WdatePicker()" value="<?php echo $row['fa_time']?>" />时间格式例如：2014-12-12
			</div>
		</div> 
		<div class="control-group">
			<label class="control-label">成交日期</label>
			<div class="controls">
				<input type="text" name="chengjiao_time" class="add-in" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo $row['chengjiao_time']?>" />时间格式例如：2014-12-12 10:00:00
			</div>
		</div> 
		<div class="control-group">
			<label class="control-label">保证金截止时间</label>
			<div class="controls">
				<input type="text" name="jiezhi_time" class="add-in" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo $row['jiezhi_time']?>" />时间格式例如：2014-12-12 15:00:00
			</div>
		</div> 
        <div class="control-group">
			<label class="control-label">竞买保证金（万元）</label>
			<div class="controls">
				<input type="text" name="baozhengjin" class="add-in" value="<?php echo $row['baozhengjin']?>" />直接填写数字，不要带上单位(默认保留2位小数)
			</div>
		</div>
        <div class="control-group">
			<label class="control-label">占地面积（亩）</label>
			<div class="controls">
				<input type="text" name="mianji_m" class="add-in" value="<?php echo $row['mianji_m']?>" />直接填写数字，不要带上单位(默认保留4位小数)
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">占地面积（㎡）</label>
			<div class="controls">
				<input type="text" name="mianji_p" class="add-in" value="<?php echo $row['mianji_p']?>" />直接填写数字，不要带上单位(默认保留4位小数)<span class="cRed">【计算方式：占地面积（亩）*666.667】</span>
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">规划建筑面积（㎡）</label>
			<div class="controls">
				<input type="text" name="guihua_p" class="add-in" value="<?php echo $row['guihua_p']?>" />直接填写数字，不要带上单位(默认保留4位小数)<span class="cRed">【计算方式：占地面积（㎡）*容积率】</span>
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">起拍价（万/亩）</label>
			<div class="controls">
				<input type="text" name="qipaijia" class="add-in" value="<?php echo $row['qipaijia']?>" />直接填写数字，不要带上单位(默认保留2位小数)
			</div>
		</div>
       <div class="control-group">
			<label class="control-label">起始价（万元）</label>
			<div class="controls">
				<input type="text" name="qishijia" class="add-in" value="<?php echo $row['qishijia']?>" />直接填写数字，不要带上单位(默认保留4位小数)<span class="cRed">【计算方式：起拍价（万/亩）*占地面积（亩）】</span>
			</div>
		</div>		
        <div class="control-group">
			<label class="control-label">成交单价（万/亩）</label>
			<div class="controls">
				<input type="text" name="chengjiaodanjia" class="add-in" value="<?php echo $row['chengjiaodanjia']?>" />直接填写数字，不要带上单位(默认保留4位小数)
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">成交总地价（万元）</label>
			<div class="controls">
				<input type="text" name="chengjiazongjia" class="add-in" value="<?php echo $row['chengjiazongjia']?>" />直接填写数字，不要带上单位(默认保留4位小数)
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">楼面地价</label>
			<div class="controls">
				<input type="text" name="loumiandijia" class="add-in" value="<?php echo $row['loumiandijia']?>" />直接填写数字，不要带上单位(默认保留2位小数)<span class="cRed">【计算方式：成交总地价（万元）/规划建筑面积（㎡）*10000】</span>
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">溢价率</label>
			<div class="controls">
				<input type="text" name="yijialv" class="add-in" value="<?php echo $row['yijialv']?>" />百分比数字<span class="cRed">【计算方式：{成交总地价（万元）一起始价（万元）}/起始价（万元）*100】</span>
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">竞得人</label>
			<div class="controls">
				<input type="text" name="jingderen" class="add-in w600" value="<?php echo $row['jingderen']?>" />
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">交易状态</label>
			<div class="controls">
				<input type="text" name="is_cheng" class="add-in" value="<?php echo $row['is_cheng']?>" />公告中、交易不成功、竞价成功、成交、审核未通过、挂牌、拍卖、中止、终止
			</div>
		</div>			
        <div class="control-group">
			<label class="control-label">地图位置</label>
			<div class="controls">
				<input type="text" name="dituweizhi" id="dituweizhi" readonly="readonly" class="add-in w600" value="<?php echo $row['dituweizhi']?>" /> <a href="javascript:;" id="up">上传</a>
			</div>
		</div>	
        <div class="control-group">
			<label class="control-label">出让须知</label>
			<div id="editor" class="controls">
				<!-- <textarea name="xuzhi" cols="150" rows="10" class="add-in"></textarea> -->			
			</div>		
			<textarea id="xuzhi_text" name="xuzhi" style="display:none"><?php echo htmlspecialchars_decode($row['xuzhi'])?></textarea>
		</div>				
        <div class="form-actions">
            <button class="btn" name="submit" type="submit">提交</button>
	   	</div>   
	</form>
    </div>
</div>
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/WdatePicker.js"></script>
<script type="text/javascript" src="../wangEditor/wangEditor.min.js"></script>
<script>
window.onload = function () {
	var up = document.getElementById('up');
	up.onclick = function () {
		centerWindow('upload_image.php','up','200','450');
	};
};

function centerWindow(url,name,height,width) {
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
}



var E = window.wangEditor
var editor = new E('#editor')

var $xuzhi_text = $('#xuzhi_text')


// 自定义菜单配置
editor.customConfig.menus = [
    'head',  // 标题
    'bold',  // 粗体
    'fontSize',  // 字号
    'fontName',  // 字体
    'italic',  // 斜体
    'underline',  // 下划线
    'strikeThrough',  // 删除线
    'foreColor',  // 文字颜色
    'backColor',  // 背景颜色
    'list',  // 列表
    'justify',  // 对齐方式
    'quote',  // 引用
    'table',  // 表格
    'undo'  // 撤销
]
editor.customConfig.zIndex = 0



editor.customConfig.onchange = function (html) {
	// 监控变化，同步更新到 textarea
	$xuzhi_text.val(html)
}



editor.create()

//隐藏数据读取到富文本框中
editor.txt.html($xuzhi_text.val())

// 初始化 textarea 的值
$xuzhi_text.val(editor.txt.html())
</script>
<?php
include ('../footer.php');
?>