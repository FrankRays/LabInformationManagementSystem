<?php
include("../common/session.inc");
if($n<3) 
	echo "<script language='javascript'>alert('你无权进行此操作！');location.href='index.html';</script>";
include("../common/db_conn.inc");
//时间调用
include("../common/valid_time_range.inc");
/*----------------------------------------------------
	引入后提供的变量如下:
	$first_week_date				自定义的第一周时间
	$date_year						自定义时间中的年
	$date_month						自定义时间中的月
	$date_month_int					数字化后的月份
	$date_day						自定义时间中的日
	$now_week						计算后得到的周次
	$now_week_output				显示在top区域的周次文字
	$valid_time_range_begin_date	有效时间区间的开始日期
	$valid_time_range_end_date		有效时间区间的结束日期
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/
//调用发送邮件类
//include("mail.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
<!--
//未知的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<title>邮件发送</title>
</head>

<body>
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?= $table_title?>反馈表邮件发送</div>
<form action="" method="post" >
<table>
<div>请选择要发送的用户：</div>
<?php 
//获取所有本学期填写登记表老师名单
$sql_tea = "SELECT DISTINCT a_rname FROM `apply1` WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY `a_rname`";
$rs_tea = mysql_query($sql_tea) or die("SQL语句执行失败：".mysql_error());
$num_tea = mysql_num_rows($rs_tea);	//影响行数
for($i=0;$i<$num_tea;$i++)
{
	$row_tea = mysql_fetch_row($rs_tea);
	if($i%10 == 0)
	{
		echo "<tr>";
	}
	echo '<td><input name="a[]" type="checkbox" value="'.$row_tea[0].'"/>'.$row_tea[0].'</td>';
	if(($i+1)%10 == 0)
		echo '</tr>';
}
?>
</table>
<div><input name="submit" type="submit" value="send">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="reset" type="reset" value="reset"></div>
</form>
<?php

//生成表格文件？

if($_POST["submit"] != "")//提交处理
	{
		if($_POST["a"] != "")
		{
			$tea = $_POST["a"];
			$title = $table_title.'实验课程登记表';
			$subject="=?utf-8?b?".base64_encode($title)."?=";
			//生成sql语句，查询当前老师本学期的所有登记表
			for($num=0;$num<count($tea);$num++)
			{
				//获取该老师的邮箱to
				$sql_mail = "SELECT u_email FROM `user` WHERE u_name='".$tea[$num]."' LIMIT 1";
				$rs_mail = mysql_query($sql_mail) or die("SQL语句执行失败：".mysql_error());
				$row_mail = mysql_fetch_row($rs_mail);
				$to = $row_mail[0];
				//
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers .= 'To: '.$to . "\r\n";
				$headers .= 'From: admin@example.com' . "\r\n";
				$headers .= 'Reply-To: '.$to . "\r\n";
				//获取该老师的登记表body
				$sql = "SELECT DISTINCT a_cname FROM `apply1` WHERE a_rname='".$tea[$num]."' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定教师的课程信息
				$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
				$course_num = mysql_num_rows($result);
				for($i=0; $i<$course_num ; $i++)
				{
					$row = mysql_fetch_array ( $result );
					$course_arr[] = $row[0];
				}
				$body = "";//存放该老师在本学期填写的登记表
				for($i=0; $i<$course_num ; $i++)
				{
					$body .= feedbackTable($tea[$num],$course_arr[$i],$valid_time_range_begin_date,$valid_time_range_end_date);
				}
				//echo $body;
				//echo $subject;
				//echo $headers;
				
				if(mail($to,$subject,$body,$headers))
				{
					//echo "<script language='javascript'>alert('发送成功！');</script>";
					echo "<p>给".$tea[$num]."老师发送邮件成功！</p>";
				}
				else
				{
					echo "<p>给".$tea[$num]."老师发送邮件失败！请重新发送···</p>";
				}
				
				unset($course_arr);
			}
		}
		else
		{
			echo "<script language='javascript'>alert('请您选择要发送的用户！');</script>";
		}
	}


//



function feedbackTable($teachername,$coursename,$valid_time_range_begin_date,$valid_time_range_end_date)
	{
		 $sql = "SELECT a_id, a_rname , a_cname , a_sid AS 实验编号 , a_sname AS 实验项目  FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename'  ORDER BY `a_cname`,`a_sid`";
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		$row_num = mysql_num_rows($result);
		$col_num = mysql_num_fields( $result );
		if($row_num==0) 
			echo "找不到相关记录,请重新搜寻";
		
		else{
		
		$table = "";
		$table .= "<table width=\"100%\" cellpadding=\"1\"  border=\"2\" bordercolor=\"#000000;\">\n";
		$table .= "<tr bgcolor=\"#FFFFFF\">\n";
		//2009-12-6
		$table .= "<th>教师</th>\n";
		$table .= "<th>课程</th>\n";
		$table .= "<th>实验编号</th>\n";
		$table .= "<th>实验项目</th>\n";
		$table .= "<th>周次</th>\n";
		$table .= "<th>星期</th>\n";
		$table .= "<th>节次</th>\n";
		$table .= "<th>实验室安排</th>\n";
		$table .= "<th>实验室负责人</th>";//2009-12-6（广强）
		
		$table .= "</tr>\n";
		$row = mysql_fetch_row ( $result );//获取影响多行中的一行
		$hebing = 0;//用于老师名称显示合并（判断）
		for ( $i = 0; $i < $row_num; $i++ ) 
		{
			$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次,a_room AS 实验安排 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek`",$row[3],$row[0]);
			//print_r($sql_tid); 
			$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$row_num_tid = mysql_num_rows($result_tid);			//获取影响行的数目
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行
			for($num_tid=0;$num_tid<$row_num_tid;$num_tid++) //$row_num_tid
			{
			$table .= "<tr bgcolor=\"#FFFFFF\">\n";
			//查询整个关于老师跟课程的所有时间段的条数，作为老师名称显示表格的合并
			$sql_rowspan = "SELECT a_id FROM `time`  WHERE a_id IN (SELECT a_id FROM `apply1` WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename')";
			$rs_rowspan = mysql_query($sql_rowspan) or die ("查询数据库出错：" . mysql_error() );
			$rowspan = mysql_num_rows($rs_rowspan);//获取影响的所有时间段的条数
			//echo $hebing."----";
			if ($hebing==0) 
				$table .= "<td rowspan='$rowspan'>$teachername</td>";//根据老师名称、课程名称
			///输出登记表
			$hebing++;//用于老师名称显示合并（判断）
			for($j=2;$j<mysql_num_fields($result);$j++)
				{
				$table .= "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
				}
			//输出时间段
		   for($m=0;$m<4;$m++)
				{
					$m_j=$m+5;
					$table .= "<td align=\"center\" id=\"id$m_j\">{$row_tid[$m]}</td>\n";
				}
			/*-------------------------负责人内容列的输出处理BEGIN-------------------------*/
			 $table .= "<td align=\"center\" id=\"id$j\">";
			 $table .= getAdminsByRooms($row_tid[($m-1)]);
			  //$table .= "房号：".$row[($j-1)];
			$table .= "</td>\n";		  
			/*-------------------------负责人内容列的输出处理END---------------------------*/
		
			$table .= "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行
			}
			$row = mysql_fetch_row ( $result );
		}
		$table .= "</table>\n";
		$table .= "<br /><br />";
		return $table;
		}//end else
	}


/*----------------------函数***********通过单个或多个房间名称获取单个或多个负责人名称并输出BEGIN----------------------------*/

	function getAdminsByRooms($rooms)
	{
		$rooms_arr = explode(",", $rooms); //生成房间数组
		
		//利用房间数组中的每个元素查询出相应的负责人,并将结果添加到负责人数组中
		foreach ($rooms_arr as $roomname)
		{
			$admin_rs =mysql_query("SELECT r_admin FROM room WHERE r_number = '$roomname' ") or die ( "SQL语句执行失败:" . mysql_error() );	
			
			$admin_rs_row = mysql_fetch_array( $admin_rs );
			
			$admin_arr[] = $admin_rs_row[r_admin] ;//获取权限内容字符串
		}
		
		$admin_arr_unique = @array_unique($admin_arr);//将负责人数组唯一化
		
		$admin_str = implode(",", $admin_arr_unique);//将负责人数组转换为以逗号分隔的字符串
		
		//echo $admin_str;//输出负责人数组
		return $admin_str;
	}
	
	/*----------------------函数***********通过单个或多个房间名称获取单个或多个负责人名称并输出END----------------------------*/

//关闭数据库
mysql_close( $conn );
?>
</body>
</html>
