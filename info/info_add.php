<?php
//定义个常量，用来授权调用inc里面的文件
define('FEI_GE',true);
require ('../inc/config.php');
$page_title = '竞品监控信息添加';
include ('../header.php');
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
		$q = "INSERT INTO f_info (info_name, city_name, fa_time, info) VALUES ('$info_name', '$city_name', '$fa_time', '$info')";
		$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
		if (mysqli_affected_rows($conn) == 1) {
			ShowMsg('这条数据已经添加成功','info_add.php');
			$_POST = array();	
		} else {
			echo '<p style="font-weight: bold; color: #C00">程序发生错误，请重新添加</p>';
			//ShowMsg('程序发生错误，请重新添加',null);			
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
?>
<div class="portlet wrap mT50">
	<div class="portlet-title">竞品监控信息添加（如需上传图片，可以上传到图床等，可以产生引用的链接）</div>
	<div class="portlet-body">
	<form action="info_add.php" method="post">
		<div class="control-group">
			<label class="control-label">项目名称</label>
			<div class="controls">
				<input type="text" name="info_name" class="add-in w600" value="" />
			</div>
		</div> 		
        <div class="control-group">
			<label class="control-label">区域</label>
			<div class="controls">
				<select name="city_name" class="select-city">
                    <?php 
					$q = "SELECT city_name FROM f_city ORDER BY city_id ASC";		
					$r = mysqli_query ($conn, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($conn));
					if (@mysqli_num_rows($r) > 0) {
						while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
							echo '<option value="'.$row[0].'">'.$row[0].'</option>';							
						}
						mysqli_free_result ($r);
					} else {
						echo '<option>暂无区域，请先添加一个新区域</option>';
					}
					mysqli_close($conn);
					?>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">发生日期</label>
			<div class="controls">
				<input type="text" name="fa_time" class="add-in" onClick="WdatePicker()" value="" />时间格式例如：2014-06-06
			</div>
		</div> 

        <div class="control-group">
			<label class="control-label">内容</label>
			<div id="editor" class="controls">

			</div>
			<textarea id="xuzhi_text" name="info" style="display:none"></textarea>
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
	'image',  // 插入图片
    'table',  // 表格
    'undo'  // 撤销
]
editor.customConfig.zIndex = 0
editor.customConfig.onchange = function (html) {
	// 监控变化，同步更新到 textarea
	$xuzhi_text.val(html)
}
editor.create()
// 初始化 textarea 的值
$xuzhi_text.val(editor.txt.html())


</script>
<?php
include ('../footer.php');
?>