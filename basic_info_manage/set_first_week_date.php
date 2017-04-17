<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->

<script language="javascript" src="../js/calendar.js"></script><!--引入日期显示JS控件-->


<?php
	include("../common/db_conn.inc");
	//include("../common/valid_time_range.inc");
	include("../common/get_first_week_date.inc");
	/*----------引入后提供的变量如下:--------------
	$frist_week_date	自定义的第一周时间
	$date_year			自定义时间中的年
	$date_month			自定义时间中的月
	$date_day			自定义时间中的日
	$now_week			计算后得到的周次	
	-----------------------------------------------*/	
	$sql = "SELECT * FROM `date_week` WHERE state=0";//找出没有被启用的第一周日期
	$result = mysql_query($sql);
	$row_num = mysql_num_rows($result);
	//echo $row_num;
?>

<title>设置第一周的日期</title>
</head>

<body>	
<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单内边框DIV_BEGIN-->
        <legend>设置第一周的日期</legend><!--表单内边框标题-->

	<form method="post">
	<p>
	    新建第一周的日期:
		&nbsp;&nbsp;
		<input id="date" readonly size="15" name="first_week_date" value="" />
		&nbsp;&nbsp;
		<input onclick="SelectgetDate(document.getElementById('date'))" type="button" value="选择" class="buttonSubmit" />
		&nbsp;&nbsp;
		<input name="btnSubmit_add" type="submit" value="更新" class="buttonSubmit" />
		</p>
		</form>
		<form method="post">
		<?php echo $term.":"; ?>
		&nbsp;&nbsp;
		<input id="date" readonly size="15" name="first_week_date" value="<?php echo $first_week_date;?>" />
		<!--&nbsp;&nbsp;
		<input onclick="SelectgetDate(document.getElementById('date'))" type="button" value="选择" class="buttonSubmit" />-->
		&nbsp;&nbsp;
		<input name="btnSubmit" type="submit" value="更新" class="buttonSubmit" />(目前第一周的日期)<br />
		</form>
		
		<?php
		for($i=0;$i<$row_num;$i++)
		{
			echo '<form method="post">';
		$row = mysql_fetch_row($result);
		echo $row[0].":";
		echo '&nbsp;&nbsp;&nbsp;&nbsp;<input id="date" readonly size="15" name="first_week_date" value="'.$row[1].'" />';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnSubmit_edit" type="submit" value="更新" class="buttonSubmit" />';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnSubmit_del" type="submit" value="删除" class="buttonSubmit" /></form>';
		}
		?>
	
	
	<?php		
		if ( isset ( $_POST["btnSubmit_add"] ))//在按下提交按钮后执行以下操作(提交按钮的name是btnSubmit)
		{				
			$form_first_week_date = $_POST["first_week_date"];//获取表单提交的日期参数
			//确保新建的日期所属的学年度
			include "set_table_title.php";
			//判断是否重复提交
			$result_date = mysql_query("SELECT first_week_date FROM date_week WHERE first_week_date = '{$form_first_week_date}' ");
			$row_num_date = mysql_num_rows($result_date);
			if($row_num_date == 0)
			{
			//找到启用的第一周日期
			//$sql_update = "SELECT state FROM date_week WHERE state=1";
			//更改state状态1->0
			mysql_query("UPDATE date_week SET state=0 WHERE state=1");
			//mysql_query("UPDATE date_week SET first_week_date = '{$form_first_week_date}' ");


			mysql_query("INSERT INTO `date_week` (`term` ,`first_week_date` ,`state` )VALUES ('$table_title', '$form_first_week_date', '1')");
			//虽然格式有可能是YYYY-M-D，但插入数据库之后会自动转换成YYYY-MM-DD的形式
			
			$update_result = mysql_affected_rows( $conn );//通过判断影响的行数确定是否修改成功			
			if( $update_result!=0 )
			{
				echo "<span style='color:#FF0000'>更新成功！</span>";				
				include("../common/get_first_week_date.inc");//重新获取时间
				echo "更新后本学期第一周的日期为：<span style='color:#FF0000'>";
				echo $first_week_date;
				echo "</span>，当前周次为：<span style='color:#FF0000'>";
				echo $now_week_output;
				echo "</span>";
			}
				else echo "<span style='color:#FF0000'>*没有更新或更新失败</span>";	
			}
			else echo "<span style='color:#FF0000'>日期更新重复，请重新选择！</span>";
		}
		if( isset ( $_POST["btnSubmit_edit"] ))//更新其他以前的日期
		{
			$form_first_week_date = $_POST["first_week_date"];//获取表单提交的日期参数
			//找到启用的第一周日期
			//$sql_update = "SELECT state FROM date_week WHERE state=1";
			//更改state状态1->0
			mysql_query("UPDATE date_week SET state=0 WHERE state=1");
			mysql_query("UPDATE date_week SET state=1 WHERE first_week_date = '{$form_first_week_date}' ");
			$update_result = mysql_affected_rows( $conn );//通过判断影响的行数确定是否修改成功			
			if( $update_result!=0 )
			{
				echo "<span style='color:#FF0000'>更新成功！</span>";				
				include("../common/get_first_week_date.inc");//重新获取时间
				echo "更新后本学期第一周的日期为：<span style='color:#FF0000'>";
				echo $first_week_date;
				echo "</span>，当前周次为：<span style='color:#FF0000'>";
				echo $now_week_output;
				echo "</span>";
			}
				else echo "<span style='color:#FF0000'>*没有更新或更新失败</span>";
		}
		if( isset ( $_POST["btnSubmit_del"] ))//删除以前的日期
		{
			$form_first_week_date = $_POST["first_week_date"];//获取表单提交的日期参数
			mysql_query("DELETE FROM date_week WHERE first_week_date = '{$form_first_week_date}' ");
			$update_result = mysql_affected_rows( $conn );//通过判断影响的行数确定是否修改成功			
			if( $update_result!=0 )
			{
				echo "<span style='color:#FF0000'>删除成功！</span>";						
			}
			else echo "<span style='color:#FF0000'>*删除失败</span>";
		}
	?>
</fieldset>
	<br /><br />
</div>
</body>
</html>