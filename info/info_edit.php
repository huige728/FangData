<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '竞品监控信息修改';
include ('../header.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {         // 接收来自info.php传递过来的id
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
	if (!empty($trimmed['info_name'])) {
		$info_name = mysqli_real_escape_string ($conn, $trimmed['info_name']);
	} else {
		$errors[] = '地块编号不能为空';
	}	
	if(empty($trimmed['city_name'])) {
		$errors[] = '请选择区域';
	} else {
		$city_name = mysqli_real_escape_string ($conn, $trimmed['city_name']);
	}		
	if(empty($trimmed['fa_time'])) {
		$errors[] = '发生日期不能为空';
	} else {
		$fa_time = mysqli_real_escape_string ($conn, $trimmed['fa_time']);
	}	
	//出让须知
	$info = (!empty($trimmed['info'])) ? mysqli_real_escape_string ($conn, htmlspecialchars($trimmed['info'])) : NULL;


	if (empty($errors)) {

		$q = "UPDATE f_info SET info_name='$info_name', city_name='$city_name', fa_time='$fa_time', info='$info' WHERE id=$id LIMIT 1";
		$r = @mysqli_query ($conn, $q);
		if (mysqli_affected_rows($conn) == 1) { 
			ShowMsg('这条数据已经修改成功','info.php?id='.$id);				
		} else {
			//ShowMsg('由于系统错误，您暂时无法编辑，请联系管理员',null);	
			echo '<p>' . mysqli_error($conn) . '<br />Query: ' . $q . '</p>'; // Debugging message.
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

$q = "SELECT * FROM f_info WHERE id=$id LIMIT 1";
$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">土地数据修改</div>
	<div class="portlet-body">
	<form action="info_edit.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>" />
		<div class="control-group">
			<label class="control-label">项目名称</label>
			<div class="controls">
				<input type="text" name="info_name" class="add-in" value="<?php echo $row['info_name']?>" />例子：DAEJ2014054
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
			<label class="control-label">发生日期</label>
			<div class="controls">
				<input type="text" name="fa_time" class="add-in" onClick="WdatePicker()" value="<?php echo $row['fa_time']?>" />时间格式例如：2014-12-12
			</div>
		</div> 

	
        <div class="control-group">
			<label class="control-label">内容</label>
			<div id="editor" class="controls">
		
			</div>		
			<textarea id="xuzhi_text" name="info" style="display:none"><?php echo htmlspecialchars_decode($row['info'])?></textarea>
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