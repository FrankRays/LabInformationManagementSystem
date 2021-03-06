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
		cursor:pointer;
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
	if($n<2) //对无权访问的进行错误页面显示处理（只有老师没有权利访问）
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";

	//if(isset($_POST['btnSubmit']))  //针对“管理员”按下“查看”按钮时所做的处理
	//2010-06-06(修改提交按钮回车)
	if($_POST['teachername'])  //针对“管理员”按下“查看”按钮时所做的处理
	{
		$teachername = $_POST['teachername'];
	}



	//echo "请先输入要查看哪位老师的登记表信息";
	/*echo '<form action="" method="post">';
	echo '请先输入要查看的老师的登记表信息 <input name="teachername" type="text" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();"/>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input name="btnSubmit" type="submit" value="查看" />';
	
    echo '<!--以下是自动提示的显示空间-->';
    echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
    echo '<img src="../course_register/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />';
    echo '<div class="suggestionList" id="autoSuggestionsList">';
    echo '&nbsp;';
	echo '</div>';
	echo '</div>';  
	
	echo '</form>';*/
		
		echo '<form action="" method="post">';
		echo "请先输入要查看教师名称：";
		echo '<input name="teachername" type="text" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();"/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<input name="btnSubmit" type="submit" value="查看" />';
		
	    echo '<!--以下是自动提示的显示空间-->';
	    echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
	    echo '<img src="../course_register/upArrow.png" style="position: relative; top: -12px; left: 200px;" alt="upArrow" />';
	    echo '<div class="suggestionList" id="autoSuggestionsList">';
	    echo '&nbsp;';
		echo '</div>';
		echo '</div>';  
		
		echo '</form>';
		



	//@$sql="SELECT DISTINCT a_cname,a_major,a_grade FROM `apply1` WHERE a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定教师的课程信息
	//2017-04-20区分条件增加班级
	@$sql="SELECT DISTINCT a_cname, a_grade, a_major, a_class FROM `apply1` WHERE a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定教师的课程信息
	
	$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
	$course_num = mysql_num_rows($result);
	for($i=0; $i<$course_num ; $i++)
	{
		$row = mysql_fetch_array ( $result );
		$course_arr[] = $row['a_cname'];   //课程名称
		$major_arr[] = $row['a_major'];  //专业
		$grade_arr[] = $row['a_grade'];  //年级
		$class_arr[] = $row['a_class']; //班级
	}


/*函数courseRegisterTable()*/
/*功能：根据教师姓名与课程姓名输出实验课信息登记表*/
/*传入：教师名、课程名*/
/*传出：单个实验课信息登记表*/
/*作者：陈灿*/


	function courseRegisterTable($teachername,$coursename,$majorname,$grade,$valid_time_range_begin_date,$valid_time_range_end_date, $class)
	{ 
		$sql = "SELECT a_id, a_rname , a_cname , a_ctype AS 课程类别,a_sbook AS 实验教材, a_sid AS 实验编号 , a_sname AS 实验项目 , a_stype, a_grade, a_major, a_class, a_people, a_learntime , a_stime , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求  FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename' AND a_major='$majorname' AND a_grade='$grade' AND a_class='$class' ORDER BY  'a_grade' ASC,'a_cname','a_major','a_sid'";
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);
		$col_num = mysql_num_fields( $result );
		
		if($row_num==0) 
			echo "找不到相关记录,请重新搜寻";
		
		else
		{
		echo "<table cellpadding=\"1\" cellspacing=\"0\"  border=\"2\" bordercolor=\"#000000;\">\n";
		echo "<tr bgcolor=\"#FFFFFF\">\n";
		//
		echo "<th>老师名称</th>\n";
		echo "<th>课程名称</th>\n";
		echo "<th>课程类别</th>\n";
		echo "<th>实验教材</th>\n";
		echo "<th>实验编号</th>\n";
		echo "<th>实验项目</th>\n";
		echo "<th>实验类型</th>\n";
		echo "<th>周次</th>\n";
		echo "<th>星期</th>\n";
		echo "<th>节次</th>\n";
		echo "<th>年级</th>\n";
		echo "<th>专业</th>\n";
		echo "<th>班别</th>\n";
		echo "<th>人数</th>\n";
		echo "<th>计划学时</th>\n";
		echo "<th>实际学时</th>\n";
		echo "<th>耗材需求</th>\n";
		echo "<th>系统需求</th>\n";
		echo "<th>软件需求</th>\n";
		echo "<th>实验室安排</th>\n";
		echo "</tr>\n";
		//
		$row = mysql_fetch_row ( $result );
		$hebing = 0;//用于老师名称显示合并（判断）
		for ( $i = 0; $i < $row_num; $i++ ) 
		{
			//一个实验有多个时间段处理
			$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次,a_room AS 实验安排 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek`",$row[5],$row[0]);
			//print_r($sql_tid); 
			$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$row_num_tid = mysql_num_rows($result_tid);			//获取影响行的数目
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行
			$cont = 0;
			for($num_tid=0;$num_tid<$row_num_tid;$num_tid++)//多个时间段输出
			{
			echo "<tr bgcolor=\"#FFFFFF\">\n";
			//查询整个关于老师跟课程的所有时间段的条数，作为老师名称显示表格的合并
			$sql_rowspan = "SELECT a_id FROM `time`  WHERE a_id IN (SELECT a_id FROM `apply1` WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename')";
			$rs_rowspan = mysql_query($sql_rowspan) or die ("查询数据库出错：" . mysql_error() );
			$rowspan = mysql_num_rows($rs_rowspan);//获取影响的所有时间段的条数
			//echo $hebing."----";
			if ($hebing==0) 
			{
		  		echo "<td rowspan='$rowspan' align=\"center\">$teachername</td>";
		  		$new_i = $i+2;
		  		echo "<td rowspan='$rowspan' align=\"center\">{$row[2]}</td>";
		  		$new_i++;
		  		echo "<td rowspan='$rowspan' align=\"center\">{$row[3]}</td>";
				$new_i++;
		  		echo "<td rowspan='$rowspan' align=\"center\">{$row[4]}</td>";
			}
			$hebing++;//用于老师名称显示合并（判断）
			for($j=5;$j<8;$j++)//编号、项目、类型
				{
					echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
				}
			for($t_j=0;$t_j<3;$t_j++)//周次、星期、节次
				{
					$id_t = $t_j+8;
					echo "<td align=\"center\" id=\"id$id_t\">{$row_tid[$t_j]}</td>\n";
				}
			for ( $j = 8; $j < mysql_num_fields($result); $j++ ) //从第4列开始
		  	{
				//id从10开始
				//9代表专业
				$j_id = $j+3;
				if($j==12 or $j==13)//对计划学时特别处理
					{
						if($cont==0)//当变量为0的时候则合并单元格
						{
						echo "<td align=\"center\" id=\"id$j_id\" rowspan='$row_num_tid'>{$row[$j]}</td>\n";
						
						}

					}
					else
						echo "<td align=\"center\" id=\"id$j_id\">{$row[$j]}</td>\n";		    	
		    }
			$cont++;
			echo "<td align=\"center\" id=\"id18\">{$row_tid[3]}</td>\n";//房号输出
			echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行时间段
			}//多个时间段输出
			$row = mysql_fetch_row ( $result );				//获取多行数据的一行登记表
		}
		echo "</table>\n";
		echo "<br /><br />";
		}//end else
	}

?>
<a href="#" onclick="method1('content');">= 导出到Excel =</a>

<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">查到的<?= $table_title ?>相关的实验课信息登记表如下</p>


<span id="content">  

	<?php
	
		for($i=0; $i<$course_num ; $i++)
		{
			courseRegisterTable($teachername,$course_arr[$i],$major_arr[$i],$grade_arr[$i],$valid_time_range_begin_date,$valid_time_range_end_date,$class_arr[$i]);
		}
	
	?>

</span>
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
  //coalesce_row(document.all.id3,1,0,'null')
  coalesce_row(document.all.id4,1,0,'null')
 coalesce_row(document.all.id5,1,0,'null')
 // coalesce_row(document.all.id12,1,0,'null')
  //coalesce_row(document.all.id8,1,0,'null')
  </script>
<!--单元格竖合并输出END-->

</body>
</html>