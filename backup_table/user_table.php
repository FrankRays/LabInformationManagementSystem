<?php
include("../common/session.inc");
include("../common/db_conn.inc");
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
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<title>实验室工作人员情况表</title>

</head>
<body>
<?php
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
?>
&nbsp;&nbsp;
<a href="#" onclick="method1('content');">= 导出到Excel =</a>
<span id="content">
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?php echo $table_title; if($usercode==2) echo "您负责的实验室相关的实验室工作人员情况表"; else echo "实验室工作人员情况表";?></div>

<table  width="90%" border="2" cellspacing="0" cellpadding="0" bordercolor="#000000">

  <tr align="center">
    <th>序号</th>
    <th>姓名</th>
    <th>性别</th>
	<th>出生年月</th>
	<th>职务</th>
	<th>职称</th>
	<th>职称评定时间</th>
	<th>学历</th>
	<th>学位</th>
	<th>毕业院校</th>
	<th>所学专业</th>
	<th>毕业时间</th>
	<th>参加工作时间</th>
	<th>理工工作时间</th>
	<th>教龄</th>
	<th>任用情况</th>
  </tr>
  <?php
  
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
			$j=0;//序号
			$sql="select * from user WHERE u_type='1' AND u_name IN (SELECT DISTINCT a_rname FROM apply1 WHERE  a_id IN (SELECT a_id FROM `time` WHERE  ".$sql_like.") AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')";//从用户表输出所有老师、负责人的情况
			//echo $sql;
			$result=mysql_query( $sql );
			$num_rows=mysql_num_rows( $result );
		    for($i=0; $i<$num_rows; $i++)
		    {  
			   $array = mysql_fetch_array($result);
			   $j++;
			   ?>
			   <tr align="center">
				<td height="25" ><?php echo $j; ?></td>
				<td>&nbsp;<?php echo $array["u_name"]; ?></td>
				<td><?php echo $array["u_gender"]; ?></td>
				<td><?php echo $array["u_birthday"]; ?></td>
				<td><?php echo $array["u_duty"]; ?></td>
				<td><?php echo $array["u_dutyname"]; ?></td>
				<td><?php echo $array["u_dutytime"]; ?></td>
				<td><?php echo $array["u_xueli"]; ?></td>
				<td><?php echo $array["u_degree"]; ?></td>
				<td><?php echo $array["u_graduate"]; ?></td>
				<td><?php echo $array["u_speciality"]; ?></td>
				<td><?php echo $array["u_graduatetime"]; ?></td>
				<td><?php echo $array["u_worktime"]; ?></td>
				<td><?php echo $array["u_seminarytime"]; ?></td>
				<td><?php echo $array["u_workage"]; ?></td>
				<td><?php echo $array["u_appoint"]; ?></td>
			   </tr>
			   <?php
			}
  }
  else//其他用户
  {
	  $sql="select * from user where u_type='1' and u_name IN (SELECT a_rname FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')";//从用户表输出所有老师、负责人的情况
	  $result=mysql_query( $sql );
	  $num_rows=mysql_num_rows( $result );
	  
	  if($num_rows<1) 
		 echo '<tr align=center><td height=25>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>没有找到相关资料</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								<td>---</td>
								</tr>'; 
	   else
	   { 
		   $j=0;
		   for($i=0; $i<$num_rows; $i++)
		   {  
			   $array=mysql_fetch_array( $result ); $j++;
		 
	  ?>
	  <tr align="center">
		<td height="25" ><?php echo $j; ?></td>
		<td>&nbsp;<?php echo $array["u_name"]; ?></td>
		<td><?php echo $array["u_gender"]; ?></td>
		<td><?php echo $array["u_birthday"]; ?></td>
		<td><?php echo $array["u_duty"]; ?></td>
		<td><?php echo $array["u_dutyname"]; ?></td>
		<td><?php echo $array["u_dutytime"]; ?></td>
		<td><?php echo $array["u_xueli"]; ?></td>
		<td><?php echo $array["u_degree"]; ?></td>
		<td><?php echo $array["u_graduate"]; ?></td>
		<td><?php echo $array["u_speciality"]; ?></td>
		<td><?php echo $array["u_graduatetime"]; ?></td>
		<td><?php echo $array["u_worktime"]; ?></td>
		<td><?php echo $array["u_seminarytime"]; ?></td>
		<td><?php echo $array["u_workage"]; ?></td>
		<td><?php echo $array["u_appoint"]; ?></td>
	  </tr>
	  <?php }
		}
  }
?>
</table>
</span>
<p>
  <?php 
  mysql_close( $conn );
  include("excel.inc");?>
</p>
</body>
</html>
