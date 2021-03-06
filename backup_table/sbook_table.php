<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>人时分析表</title>
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

</head>
<body>

<?php
	include("../common/db_conn.inc");
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
	
	include("../common/excel.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";
	$usercode = $_SESSION["u_type"];		  //获取权限代

	echo"&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"method1('content');\">= 导出到Excel =</a>
<br />
<span id=\"content\">";
	
	if($usercode==2)
	{
		echo "<div align=\"center\" style=\"font-size:14px; color:#1E7ACE; font-weight:bold;\">{$table_title}您负责的实验室相关的实验教材（讲义）、指导书一览表</div>";
	}
	else
	{
		echo "<div align=\"center\" style=\"font-size:14px; color:#1E7ACE; font-weight:bold;\">{$table_title}实验教材（讲义）、指导书一览表</div>";
	}
	
	echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";

	echo "<tr>\n";
	  echo "<th>序号</th>\n";
	  echo "<th>老师名称</th>\n";
	  echo "<th>课程名称</th>\n";
	  echo "<th>实验教材（讲义）、指导书</th>\n";
	  echo "<th>面向专业</th>\n";  
	  echo "<th>备注</th>\n";
	echo "</tr>\n";
	
	$serial = 1;//前面的序号列
	
	if($usercode==2)//负责人
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
		for($room_num=0;$room_num<count($room_arr);$room_num++)//循环输出相关课程信息表
		{
			$room = $room_arr[$room_num];
			$sql_like .= "a_room LIKE '%".$room."%' OR ";
		}
		$sql_like = substr($sql_like,0,strlen($sql_like)-4);//去掉最后四个字符，即OR和两个空格
			//查询
			$sql = "SELECT a_rname ,  a_cname , a_sbook , c_major FROM apply1 , course  WHERE  a_id IN (SELECT a_id FROM `time` WHERE  ".$sql_like.") AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' AND course.c_cname=apply1.a_cname GROUP BY a_rname , a_cname order by a_rname";
			$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );	  
			$row_num = mysql_num_rows($result);       //获取影响行的数目
			$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目
			$row = mysql_fetch_array ( $result );
			//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
			for ( $i = 0; $i < $row_num; $i++ )
			{
			    echo "<tr>\n";
				if($row!=null) echo "<td align=\"center\">$serial</td>\n";
				echo "<td align=\"center\">{$row[0]}</td>\n";	
				echo "<td align=\"center\">{$row[1]}</td>\n";
				echo "<td align=\"center\">{$row[2]}</td>\n";
				echo "<td align=\"center\">{$row[3]}</td>\n";
				//echo "<td align=\"center\">{$row[6]}</td>\n";
				//echo "<td align=\"center\">{$row[7]}</td>\n";
				echo "<td align=\"center\">&nbsp;</td>\n";
				echo "</tr>\n";
			    $row = mysql_fetch_array ( $result );
			    $serial++;
			}
	}
	else
	{
		//查询
		$sql = "SELECT a_rname ,  a_cname , a_sbook , c_major FROM apply1 , course  WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' AND course.c_cname=apply1.a_cname GROUP BY a_rname , a_cname order by a_rname";
		$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );	  
		$row_num = mysql_num_rows($result);       //获取影响行的数目
		$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目
		$row = mysql_fetch_array ( $result );
		//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
		for ( $i = 0; $i < $row_num; $i++ )
		{
		  echo "<tr>\n";
			if($row!=null) echo "<td align=\"center\">$serial</td>\n";
			echo "<td align=\"center\">{$row[0]}</td>\n";	
			echo "<td align=\"center\">{$row[1]}</td>\n";
			echo "<td align=\"center\">{$row[2]}</td>\n";
			echo "<td align=\"center\">{$row[3]}</td>\n";
			//echo "<td align=\"center\">{$row[6]}</td>\n";
			//echo "<td align=\"center\">{$row[7]}</td>\n";
			echo "<td align=\"center\">&nbsp;</td>\n";
			echo "</tr>\n";
		  $row = mysql_fetch_array ( $result );
		  $serial++;
		}
	}
	
	echo "</table>\n";
	/*----------------------------------------输出单个表END----------------------------------------*/	
echo "</span>";


?>
</body>
</html>