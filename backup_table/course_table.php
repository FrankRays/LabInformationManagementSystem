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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>课程信息报表</title>
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<!-- script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<!-----------------------------------自动完成功能BEGIN---------------------------------->

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script>


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
	 include("excel.inc");
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

	$usercode = $_SESSION["u_type"];		  //获取权限代
	if($usercode==2)//对负责人
	{
		$admin_name = $_SESSION["u_name"];	
		$sql_roomnumber = "SELECT r_number FROM `room` WHERE  r_admin LIKE '%".$admin_name."%' AND r_state=1";
		$rs_roomnumber =mysql_query( $sql_roomnumber ) or die("不能查询指定的数据库表：" . mysql_error());
		$roomnumber_num = mysql_num_rows($rs_roomnumber);
		for($i=0;$i<$roomnumber_num;$i++)
		{
			$row_roomnumber = mysql_fetch_array( $rs_roomnumber );
			$room_arr[] = $row_roomnumber['r_number'];
		}
	}
	else//其他用户
	{
		//获得本学期所有填写的登记表课程名称
		//2017-04-21添加按老师名字排序
		$sql="SELECT DISTINCT a_cname FROM `apply1` WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY a_rname";  //统计指定课程信息
		$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		$course_num = mysql_num_rows($result);
		//echo $course_num;
		for($i=0; $i<$course_num ; $i++)
		{
			$row = mysql_fetch_array ( $result );
			$course_arr[] = $row['a_cname'];
			
		}
	}
?>
&nbsp;&nbsp;
<a href="#" onclick="method1('content');">= 导出到Excel =</a>
<span id="content">
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?php echo $table_title; if($usercode==2) echo "您负责的实验室相关的实验课开出情况表"; else echo "实验课开出情况表";?></div>

<?php
	echo "<table  width=\"100%\" cellpadding=\"1\"  border=\"2\" bordercolor=\"#000000;\">\n";
		echo "<tr bgcolor=\"#FFFFFF\">\n";
		echo "<th>序号</th>\n";
		echo "<th>课程名称</th>\n";
		echo "<th>课程类别</th>\n";
		echo "<th>实验教材</th>\n";
		echo "<th>实验项目</th>\n";
		echo "<th>实验类型</th>\n";
		//echo "<th>实验要求</th>\n";
		//echo "<th>每组人数</th>\n";
		echo "<th>计划学时</th>\n";
		echo "<th>实际学时</th>\n";
		echo "<th>年级</th>\n";
		echo "<th>专业</th>\n";
		echo "<th>班别</th>\n";
		echo "<th>人数</th>\n";
		echo "<th>指导老师</th>\n";
		echo "<th>实验室名称</th>";//2009-12-22（广强）`a_cname` , `a_sid` ,
		echo "</tr>\n";
		$xuhao = 1;
		if($usercode==2)//对负责人
		{
			for($room_num=0;$room_num<count($room_arr);$room_num++)//循环输出相关课程信息表
			{
				$room = $room_arr[$room_num];
				$sql_like .= "a_room LIKE '%".$room."%' OR ";
			}
			$sql_like = substr($sql_like,0,strlen($sql_like)-4);//去掉最后四个字符，即OR和两个空格
				$sql = "SELECT a_id, a_cname , a_ctype , a_sbook , a_sname , a_stype , a_learntime , a_stime , a_grade , a_major , a_class , a_people , a_rname ,a_cdirection  FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE  ".$sql_like.") AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY  `a_rname`, a_cname, a_ctype, a_grade, a_major, a_class";
				$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
				$row_num = mysql_num_rows($result);
				$col_num = mysql_num_fields( $result );
				$row = mysql_fetch_row ( $result );//获取影响多行中的一行
				for($num=0;$num<$row_num;$num++)
				{
					echo "<tr>";
					echo "<td align=\"center\">".$xuhao."</td>";
					for($j=1;$j<mysql_num_fields($result);$j++)
						{
							echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
							//echo "<td>{$row[$j]}</td>";
						}
					$xuhao++;
					echo "</tr>";
					$row = mysql_fetch_row ( $result );//获取影响多行中的一行
				}
		}
		else//其他用户
		{
			for($i=0; $i<$course_num ; $i++)
			{
			$coursename = $course_arr[$i];
			
			$sql = "SELECT a_id, a_cname , a_ctype , a_sbook , a_sname , a_stype , a_learntime , a_stime , a_grade , a_major , a_class , a_people , a_rname ,a_cdirection  FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_cname='$coursename'  ORDER BY  `a_rname`, a_cname, a_ctype, a_grade, a_major, a_class ";
			
			$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$row_num = mysql_num_rows($result);
			$col_num = mysql_num_fields( $result );
			if($row_num==0)
			{
				//echo "<tr>";
				echo "找不到相关记录,请重新搜寻";
				//echo "</tr>";
			}
			
			else{
				$row = mysql_fetch_row ( $result );//获取影响多行中的一行
				for($num=0;$num<$row_num;$num++)
				{
					echo "<tr>";
					echo "<td align=\"center\">".$xuhao."</td>";
					for($j=1;$j<mysql_num_fields($result);$j++)
					{
						echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
						//echo "<td>{$row[$j]}</td>";
					}
					$xuhao++;
					echo "</tr>";
					$row = mysql_fetch_row ( $result );//获取影响多行中的一行
				}
				}//end else
			}	
		}//end else其他用户
	echo "</table>\n";
	echo "<br /><br />";
  mysql_close( $conn );
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
  
  coalesce_row(document.all.id1,1,0,'null');
  coalesce_row(document.all.id2,1,0,'null');
  coalesce_row(document.all.id3,1,0,'null');
  //coalesce_row(document.all.id4,1,0,'null');
  coalesce_row(document.all.id11,1,0,'null');
  //coalesce_row(document.all.id12,1,0,'null');

  </script>
<!--单元格竖合并输出END-->



</body>
</html>

