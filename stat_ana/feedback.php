<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
 <!--
//屏蔽table.length的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>反馈信息表</title>
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<!-----------------------------------自动完成功能BEGIN---------------------------------->

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script>
<script type="text/javascript">
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			
			$.post("../course_register/jq_Tname_select.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});	
					
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
</script>

<style type="text/css">
	
	h3 {
		margin: 0px;
		padding: 0px;	
	}

	.suggestionsBox {
		position: relative;
		left: 30px;
		margin: 10px 0px 0px 0px;
		width: 300px;
		background-color: #212427;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 2px solid #000;	
		color: #fff;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}
</style>

<!-----------------------------------自动完成功能END------------------------------------>


</head>
<body>

<?php
	include("../common/db_conn.inc");
	
	include("../common/func_getAdminByRooms.php");
	/*-----------------------------------------------
	引入后提供函数:getAdminsByRooms($rooms);
	通过单个或多个房间名称获取单个或多个负责人名称并输出
	-----------------------------------------------*/
	
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
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$usercode = $_SESSION["u_type"];         //获取权限代码

	if($usercode == '1')
	{
		$teachername = $_SESSION["u_name"];
	}
	
	if($usercode == '4' or $usercode == '5')
	{
		//$teachername = '陈倩'; 管理员看到的默认教师名
		
		//(2010-06-06提交回车修改)if(isset($_POST['btnSubmit']))  //针对“管理员”按下“查看”按钮时所做的处理
		if(isset($_POST['teachername']))  //针对“管理员”按下“查看”按钮时所做的处理
		{
			$teachername = $_POST['teachername'];
		}



		echo "请先输入要查看哪位老师的反馈信息";
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
		
	}


	
	//$sql="SELECT DISTINCT a_cname FROM `apply1` WHERE a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定教师的课程信息
	$sql="SELECT DISTINCT a_cname, a_grade, a_major, a_class FROM `apply1` WHERE a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定教师的课程信息
	
	$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
	$course_num = mysql_num_rows($result);
	//echo $course_num;
	for($i=0; $i<$course_num ; $i++)
	{
		$row = mysql_fetch_array ( $result );
		$course_arr[] = $row['a_cname'];
		$grade_arr[] = $row['a_grade'];
		$major_arr[] = $row['a_major'];
		$class_arr[] = $row['a_class'];
		
	}







/*	if($usercode == '1')//教师权限直接用其真实姓名
		{
			$teachername = $_SESSION["u_name"];	//获取真实名称
		}
	
		else if ($usercode == '3')//管理员权限要先获取教师名字
		{
			$teachername = $_POST['form_rname'];
		}
*/


/*函数feedbackTable())*/
/*功能：根据教师姓名与课程姓名输出反馈信息表*/
/*传入：教师名、课程名*/
/*传出：反馈信息表*/
/*作者：陈灿*///

	/**
	* 2017-04-20增加$grade(年级), $major(专业), $class(班级)三个参数
	*/
	function feedbackTable($teachername,$coursename,$valid_time_range_begin_date,$valid_time_range_end_date, $grade, $major, $class)
	{
		
		$course = $grade.$major.$class;
		/*
		$sql = "SELECT DISTINCT apply.a_rname , apply.a_cname , apply.a_sid AS 实验编号 , apply.a_sname AS 实验项目 , apply.a_sweek AS 周次 , apply.a_sdate AS 星期 , apply.a_sclass AS 节次 , apply.a_room AS 实验室安排 ,room.r_admin AS 实验室负责人 FROM `apply` left outer join `room` on room.r_name=apply.a_cdirection WHERE apply.a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND apply.a_rname='$teachername' AND apply.a_cname='$coursename'  ORDER BY `a_cname`, `a_sid` ,`a_sweek`";
		 */
		//2017-04-20新增查询年级、专业和班级三个字段并修改排序顺序
		$sql = "SELECT a_id, a_rname , a_cname , a_sid AS 实验编号 , a_sname AS 实验项目 FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename' AND a_grade='$grade' AND a_major='$major' AND a_class='$class'  ORDER BY `a_cname`,`a_sid`";
		
		
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		//print_r($sql); 
		$row_num = mysql_num_rows($result);
		$col_num = mysql_num_fields( $result );
		//echo $row_num;//输出查询条数
		if($row_num==0) 
			echo "找不到相关记录,请重新搜寻";
		
		else{
		
		
		echo "<table width=\"100%\" cellpadding=\"1\"  border=\"2\" bordercolor=\"#000000;\">\n";
		echo "<tr bgcolor=\"#FFFFFF\">\n";
		//2009-12-6
		echo "<th>教师</th>\n";
		echo "<th>课程</th>\n";
		echo "<th>班级</th>\n";//2017-04-20新增班级,用于区分不同课程
		echo "<th>实验编号</th>\n";
		echo "<th>实验项目</th>\n";
		echo "<th>周次</th>\n";
		echo "<th>星期</th>\n";
		echo "<th>节次</th>\n";
		echo "<th>实验室安排</th>\n";
		echo "<th>实验室负责人</th>";//2009-12-6（广强）
		
		echo "</tr>\n";
		$row = mysql_fetch_row ( $result );//获取影响多行中的一行
		$hebing = 0;//用于老师名称显示合并（判断）
		

		
		
		for ( $i = 0; $i < $row_num; $i++ ) 
		{
			$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次,a_room AS 实验安排 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek`",$row[3],$row[0]);
			//print_r($sql_tid); 
			$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$row_num_tid = mysql_num_rows($result_tid);			//获取影响行的数目
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行
			//for($num_tid=0;$num_tid<$row_num_tid;$num_tid++) //$row_num_tid
			//{
			echo "<tr bgcolor=\"#FFFFFF\">\n";
		  
			//echo "<td align=\"center\" id=\"id1\">$teachername</td>";
			//查询整个关于老师跟课程的所有时间段的条数，作为老师名称显示表格的合并
			$sql_rowspan = "SELECT a_id FROM `time`  WHERE a_id IN (SELECT a_id FROM `apply1` WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename')";
			$rs_rowspan = mysql_query($sql_rowspan) or die ("查询数据库出错：" . mysql_error() );
			$rowspan = mysql_num_rows($rs_rowspan);//获取影响的所有时间段的条数
			//echo $hebing."----";
			if ($hebing==0) echo "<td rowspan='$rowspan'>$teachername</td>";//根据老师名称、课程名称
			///输出登记表
			$hebing++;//用于老师名称显示合并（判断）
			$columns = mysql_num_fields($result);
			for($j=2;$j<$columns;$j++)
				{
					//2017-04-20显示班级
					if ($j == 3) {
						echo "<td align='center'>${course}</td>";
					}
					
				echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
				}
			//输出时间段
		   for($m=0;$m<4;$m++)
				{
					$m_j=$m+5;
					echo "<td align=\"center\" id=\"id$m_j\">{$row_tid[$m]}</td>\n";
				}
			/*-------------------------负责人内容列的输出处理BEGIN-------------------------*/
			 echo "<td align=\"center\" id=\"id$j\">";
			  getAdminsByRooms($row_tid[($m-1)]);
			  //echo "房号：".$row[($j-1)];
			echo "</td>\n";		  
			/*-------------------------负责人内容列的输出处理END---------------------------*/
		
			echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行
			//}
			$row = mysql_fetch_row ( $result );
		}
		echo "</table>\n";
		echo "<br /><br />";
		}//end else
	}





?>

<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">反馈信息表</div>

<?php

	for($i=0; $i<$course_num ; $i++)
	{
		feedbackTable($teachername,$course_arr[$i],$valid_time_range_begin_date,$valid_time_range_end_date, $grade_arr[$i], $major_arr[$i], $class_arr[$i]);
	}

?>



<!--单元格竖合并输出BEGIN-->
  <script   language="JavaScript">   
  
  //var   textnum   =   1;   
  function   coalesce_row(obj,s,n,text){   
  var   text   
  table   =   obj;   
  //alert(s)   
  for   (i=n;   i<table.length;   i++){   
  if   (table(i).innerHTML   ==   text && text!='&nbsp;'){   
  s   =   s   +   1   
  table(i-1).rowSpan   =   s   
  table(i).removeNode(true);   
  coalesce_row(obj,s,i,table(i-1).innerHTML)   
  return   this;   
  }else{   
  s   =   1   
  }   
  text   =   table(i).innerHTML   
  }   
  }   
  
  //coalesce_row(document.all.id1,1,0,'null');
  coalesce_row(document.all.id2,1,0,'null');
  coalesce_row(document.all.id3,1,0,'null');
  coalesce_row(document.all.id4,1,0,'null');
  //coalesce_row(document.all.id8,1,0,'null');
 
  <?php
  	/*暂时不使用合并
  	for ($i=1; $i<20; $i++)  //将记录结果默认为50行，待改进...
  	{
  		echo "coalesce_row(document.all.id{$i},1,0,'null')\n";
  	}
    */
  ?>

  </script>
<!--单元格竖合并输出END-->



</body>
</html>

