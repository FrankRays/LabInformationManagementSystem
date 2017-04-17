<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查询登记表</title>
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<script language="JavaScript" src="../js/ajaxDiv_2.js"></script> <!--与动态DIV相关的js脚本-->

</head>
<body>


<?php
	include("db_conn.inc");
	include("valid_time_range.inc");
	/*----------------------------------------------------
	引入后提供的变量如下:
	$first_week_date				自定义的第一周时间
	$date_year						自定义时间中的年
	$date_month						自定义时间中的月
	$date_month_int					数字化后的月份
	$date_day						自定义时间中的日
	$now_week						计算后得到的周次
	$valid_time_range_begin_date	有效时间区间的开始日期
	$valid_time_range_end_date		有效时间区间的结束日期
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/
	include("session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	//获取传过来的参数（教师姓名还有课程名字）
	$teachername = $_GET['teacher_name'];
	$coursename = $_GET['course_name'];
	//
	//这个的功能主要是输出本学期---老师跟课程对应的所有相关的实验室安排信息
	//
	/*$sql = "SELECT DISTINCT a_rname AS 教师姓名 , a_cname AS 课程 , a_ctype AS 课程类别 , a_sid AS 实验编号 , a_sname AS 实验项目 , a_sweek AS 周次 , a_sdate AS 星期 , a_sclass AS 节次 , a_stype AS 实验类型 , a_resources AS 耗材需求 , a_learntime AS 计划学时 , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 , a_system AS 系统需求 , a_software AS 软件 , a_room AS 实验室安排 FROM `apply` WHERE a_rname='$teachername' AND a_cname='$coursename' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
	$result = mysql_query ( $sql )
	  or die ( "不能查询指定的数据库表：" . mysql_error() );
	  
	$row_num = mysql_num_rows($result);
	$col_num = mysql_num_fields( $result );
	
	if($row_num==0) echo "找不到相关记录,请重新搜寻";
	
	else
	{	
		echo "<p align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>".$teachername."老师的".$coursename."课程相关的实验课信息登记表如下"."</p>";
		
		
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
	}*/
		$sql = "SELECT a_id, a_rname , a_cname , a_ctype AS 课程类别, a_sid AS 实验编号 , a_sname AS 实验项目 , a_stype, a_grade, a_major, a_class, a_people, a_learntime , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求  FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename'  ORDER BY `a_sid`";
		//print_r($sql);die();
		$result = mysql_query ( $sql )or die ( "不能查询指定的数据库表1111：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);
		$col_num = mysql_num_fields( $result );
		
		if($row_num==0) echo "找不到相关记录,请重新搜寻";
		
		else{
		echo "<p align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>".$teachername."老师的".$coursename."课程相关的实验课信息登记表如下"."</p>";
		
		echo "<table cellpadding=\"1\" cellspacing=\"0\"  border=\"2\" bordercolor=\"#000000;\">\n";
		echo "<tr bgcolor=\"#FFFFFF\">\n";
		//
		echo "<th>老师名称</th>\n";
		echo "<th>课程名称</th>\n";
		echo "<th>课程类别</th>\n";
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
			$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次,a_room AS 实验安排 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek`",$row[4],$row[0]);
			//print_r($sql_tid); 
			$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表2：" . mysql_error() );
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
			}
			$hebing++;//用于老师名称显示合并（判断）
			for($j=4;$j<7;$j++)//编号、项目、类型
				{
					if($j==6)
					{
						echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";	
					}
					else//针对编号和项目处理
					{
						if($cont==0)
						{
						echo "<td align=\"center\" id=\"id$j\" rowspan='$row_num_tid'>{$row[$j]}</td>\n";
						//$cont++;
						}
					}
				}
			for($t_j=0;$t_j<3;$t_j++)//周次、星期、节次
				{
					$id_t = $t_j+7;
					echo "<td align=\"center\" id=\"id$id_t\">{$row_tid[$t_j]}</td>\n";
				}
			for ( $j = 7; $j < mysql_num_fields($result); $j++ ) //从第4列开始
		  	{
				//id从10开始
				$j_id = $j+3;
				if($j==11)//对计划学时特别处理
					{
						if($cont==0)//当变量为0的时候则合并单元格
						{
						echo "<td align=\"center\" id=\"id$j_id\" rowspan='$row_num_tid'>{$row[$j]}</td>\n";
						$cont++;
						}

					}
					else
						echo "<td align=\"center\" id=\"id$j_id\">{$row[$j]}</td>\n";		    	
		    }
			echo "<td align=\"center\" id=\"id18\">{$row_tid[3]}</td>\n";//房号输出
			echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行时间段
			}//多个时间段输出
			$row = mysql_fetch_row ( $result );				//获取多行数据的一行登记表
		}
		echo "</table>\n";
		//echo "<br /><br />";
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
  //coalesce_row(document.all.id4,1,0,'null')     
  //coalesce_row(document.all.id5,1,0,'null')   
  //coalesce_row(document.all.id2,1,0,'null')   
  </script>
<!--单元格竖合并输出END-->


</body>
</html>