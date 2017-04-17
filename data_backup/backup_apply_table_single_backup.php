<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
 <!--
//缺少对象的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<title>备份单个实验课信息登记表</title>

</head>
<body>

<?php
	include("../common/db_conn.inc");
	include("excel.inc");
	
	include("../common/valid_time_range.inc");
	/*----------------------------------------------------
	引入后提供的变量如下:
	$first_week_date				自定义的第一周时间
	$date_year						自定义时间中的年
	$date_month						自定义时间中的月
	$date_month_int					数字化后的月份
	$date_day						自定义时间中的日
	$now_week						计算后得到的周次
	$valid_time_range_begin_date	有效时间区间的开始日期(传入函数用)
	$valid_time_range_end_date		有效时间区间的结束日期(传入函数用)
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/
	
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<2) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";

	if(isset($_POST['btnSubmit']))  //针对“管理员”按下“查看”按钮时所做的处理
	{
		$teachername = $_POST['teachername'];
	}



	echo "请先输入要查看哪位老师的登记表信息";
	echo '<form action="" method="post">';
	echo '<input name="teachername" type="text" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();"/>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input name="btnSubmit" type="submit" value="查看" />';
	
    echo '<!--以下是自动提示的显示空间-->';
    echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
    echo '<img src="../course_register/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />';
    echo '<div class="suggestionList" id="autoSuggestionsList">';
    echo '&nbsp;';
	echo '</div>';
	echo '</div>';  
	
	echo '</form>';



	$sql="SELECT DISTINCT a_cname FROM `apply` WHERE a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定教师的课程信息
	$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
	$course_num = mysql_num_rows($result);
	for($i=0; $i<$course_num ; $i++)
	{
		$row = mysql_fetch_array ( $result );
		$course_arr[] = $row['a_cname'];
		
	}


/*函数courseRegisterTable()*/
/*功能：根据教师姓名与课程姓名输出实验课信息登记表*/
/*传入：教师名、课程名*/
/*传出：单个实验课信息登记表*/
/*作者：陈灿*/


	function courseRegisterTable($teachername,$coursename,$valid_time_range_begin_date,$valid_time_range_end_date)
	{
		
		$sql = "SELECT DISTINCT a_rname AS 教师姓名 , a_cname AS 课程 , a_ctype AS 课程类别 , a_sid AS 实验编号 , a_sname AS 实验项目 , a_sweek AS 周次 , a_sdate AS 星期 , a_sclass AS 节次 , a_stype AS 实验类型 , a_resources AS 耗材需求 , a_learntime AS 计划学时 , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 , a_system AS 系统需求 , a_software AS 软件需求 , a_room AS 实验室安排 FROM `apply` WHERE a_rname='$teachername' AND a_cname='$coursename' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ";
		 
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);
		$col_num = mysql_num_fields( $result );
		
		if($row_num==0) echo "找不到相关记录,请重新搜寻";
		
		else{
		
		
		echo "<table cellpadding=\"1\" cellspacing=\"0\"  border=\"2\" bordercolor=\"#000000;\">\n";
		echo "<tr bgcolor=\"#FFFFFF\">\n";
		for ( $i = 0; $i < $col_num; $i++ ) {
		  $meta = mysql_fetch_field ( $result );
		  echo "<th>{$meta->name}</th>\n";
		}
		echo "</tr>\n";
		$row = mysql_fetch_row ( $result );
		for ( $i = 0; $i < $row_num; $i++ ) 
		{
		  echo "<tr bgcolor=\"#FFFFFF\">\n";
		  
		  if ($i==0) 
		  {
		  	echo "<td rowspan='$row_num' align=\"center\">$teachername</td>";
		  	$new_i = $i+1;
		  	echo "<td rowspan='$row_num' align=\"center\">{$row[$new_i]}</td>";
		  	$new_i++;
		  	echo "<td rowspan='$row_num' align=\"center\">{$row[$new_i]}</td>";
		  }
		  
		  for ( $j = 3; $j < mysql_num_fields($result); $j++ ) //从第二列开始
		  	{
				echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";		    	
		    }
		  echo "</tr>\n";
		  $row = mysql_fetch_row ( $result );
		}
		echo "</table>\n";
		echo "<br /><br />";
		}//end else
	}

?>

<p>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="method1('content');">= 导出到Excel =</a></p>

<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">查到的<?= $table_title ?>相关的实验课信息登记表如下</p>


<span id="content">  

	<?php
	
		for($i=0; $i<$course_num ; $i++)
		{
			courseRegisterTable($teachername,$course_arr[$i],$valid_time_range_begin_date,$valid_time_range_end_date);
		}
	
	?>

</span>


</body>
</html>