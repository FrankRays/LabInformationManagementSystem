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
<!-- <script type="text/javascript" src="../js/tablecloth.js"></script> --><!--表格美化JS-->

<!-----------------------------------自动完成功能BEGIN---------------------------------->

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
		for($room_num=0;$room_num<count($room_arr);$room_num++)//循环输出相关课程信息表
		{
			$room = $room_arr[$room_num];
			$sql_like .= "a_room LIKE '%".$room."%' OR ";
		}
		$sql_like = substr($sql_like,0,strlen($sql_like)-4);//去掉最后四个字符，即OR和两个空格

		$sql = "SELECT  DISTINCT  a_cname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE ".$sql_like.") AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
		//获得的课程名字与老师名字放到二维数组
		$result = mysql_query($sql) or die ("执行SQL语句错误：".mysql_error());
		$course_num = mysql_num_rows($result);
		for($i=0; $i<$course_num ; $i++)
		{
			$row = mysql_fetch_array ( $result );
			$course_arr[] = $row['a_cname'];	
		}
		//去掉数组中重复的元素
		//$course_arr = array_unique($course_arr);
		/*for($i=0;$i<count($course_arr);$i++)
		{
			echo $course_arr[$i];
		} */
	}
	else
	{
		//获得本学期所有填写的登记表课程名称
		$sql="SELECT DISTINCT a_cname FROM `apply1` WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //统计指定课程信息
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
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?php echo $table_title; if($usercode==2) echo "您负责的实验室相关的实验项目开出情况汇总表"; else echo "实验项目开出情况汇总表";?></div>

<?php
//根据课程名称、老师名称、时间段
		echo "<table  width=\"100%\" cellpadding=\"1\"  border=\"2\" bordercolor=\"#000000;\">\n";
		echo "<tr bgcolor=\"#FFFFFF\">\n";
		echo "<th rowspan=2>序号</th>\n";
		echo "<th rowspan=2>课程性质</th>\n";
		echo "<th rowspan=2>实验课程名称</th>\n";
		echo "<th rowspan=2>计划实验个数</th>\n";
		echo "<th rowspan=2>实际开出个数</th>\n";//统计
		echo "<th colspan=3>实验类型(个数)</th>\n";//统计
		echo "<th rowspan=2>开出率</th>\n";//统计
		echo "<th rowspan=2>指导教师</th>\n";
		//echo "<th rowspan=2>指导老师</th>\n";
		echo "</tr><tr>\n";
		echo "<th>基础</th>\n";
		echo "<th>综合</th>\n";
		echo "<th>设计</th></tr>\n";
function sname_rs($coursename,$teachername,$xuhao,$valid_time_range_begin_date,$valid_time_range_end_date)
{
		$sql = "SELECT a_id , a_ctype , a_cname , count(a_sid) ,  count(a_sid) ,  a_grade , a_major , a_class , 100*count(a_sid)/count(a_sid) AS kaichu , a_rname  FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_cname='$coursename' AND a_rname='$teachername' GROUP BY `a_rname` ORDER BY  `a_rname` , `a_sid`";
		$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表11：" . mysql_error() );
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
				//
				//统计实验各个类型的数量
				//
				//对实验类型个数如基础5、综合6、设计7统计a_stype='基础型'  AND
				$sql_type_total = "SELECT count(*) FROM `apply1`  WHERE a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_cname='$coursename' AND a_rname='$teachername' ORDER BY  `a_rname` , `a_sid`";
				$sql_type_zonghe = "SELECT count(*) FROM `apply1`  WHERE a_stype='综合型' AND a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_cname='$coursename' AND a_rname='$teachername' ORDER BY  `a_rname` , `a_sid`";
				$sql_type_sheji = "SELECT count(*) FROM `apply1`  WHERE a_stype='设计型' AND a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_cname='$coursename' AND a_rname='$teachername' ORDER BY  `a_rname` , `a_sid`";
				//
				$result_type_total = mysql_query ( $sql_type_total ) or die ( "不能查询指定的数据库表：" . mysql_error() );
				$row_type_total = mysql_fetch_row ( $result_type_total );//获取影响多行中的一行
				//
				$result_type_zonghe = mysql_query ( $sql_type_zonghe ) or die ( "不能查询指定的数据库表：" . mysql_error() );
				$row_type_zonghe = mysql_fetch_row ( $result_type_zonghe );//获取影响多行中的一行
				//
				$result_type_sheji = mysql_query ( $sql_type_sheji ) or die ( "不能查询指定的数据库表：" . mysql_error() );
				$row_type_sheji = mysql_fetch_row ( $result_type_sheji );//获取影响多行中的一行
				for($j=1;$j<mysql_num_fields($result);$j++)
				{
					if($j==5)//基础实验数量
					{
						$row_type = $row_type_total[0]-$row_type_zonghe[0]-$row_type_sheji[0];
						if($row_type==0)
							echo "<td align=\"center\" id=\"id$j\">0</td>\n";
						else
							echo "<td align=\"center\" id=\"id$j\">{$row_type}</td>\n";
					}
					elseif( $j==6)//综合实验个数
					{
						$row_type = $row_type_zonghe[0];
						if($row_type==0)
							echo "<td align=\"center\" id=\"id$j\">0</td>\n";
						else
							echo "<td align=\"center\" id=\"id$j\">{$row_type}</td>\n";
					}
					elseif($j==7)//设计
					{
						$row_type = $row_type_sheji[0];
						if($row_type==0)
							echo "<td align=\"center\" id=\"id$j\">0</td>\n";
						else
							echo "<td align=\"center\" id=\"id$j\">{$row_type}</td>\n";
					}
					elseif($j==8)//利用率
					{
						$kaichulu = (int) $row[$j];//强制转换为整数
						echo "<td align=\"center\" id=\"id$j\">$kaichulu%</td>\n";
					}
					else 
						echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
					//echo "<td>{$row[$j]}</td>";
				}
				
				echo "</tr>";
				$row = mysql_fetch_row ( $result );//获取影响多行中的一行
			}
			}//end else
}
$xuhao=1;
for($i=0; $i<$course_num ; $i++)
{
	if($usercode==2)//负责人
	{
		//for($room_num=0;$room_num<count($room_arr);$room_num++)
		//{
			$coursename = $course_arr[$i];
			//$sql_tea = "SELECT DISTINCT a_rname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE a_room LIKE '%".$room."%') AND  a_cname='$coursename' AND a_date BETWEEN  '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
			$sql_tea = "SELECT DISTINCT a_rname FROM `apply1` WHERE  a_cname='$coursename' AND a_date BETWEEN  '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
			$result_tea = mysql_query ( $sql_tea ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$teacher_num = mysql_num_rows($result_tea);
			//echo $course_num;
			for($j=0; $j<$teacher_num ; $j++)
			{
				$row = mysql_fetch_array ( $result_tea );
				//$teacher_arr[] = $row['a_rname'];
				sname_rs($coursename,$row['a_rname'],$xuhao,$valid_time_range_begin_date,$valid_time_range_end_date);
				$xuhao++;
			}
		//}
	}
	else//其他用户
	{
		$coursename = $course_arr[$i];
		$sql_tea = "SELECT DISTINCT a_rname FROM `apply1` WHERE  a_cname='$coursename' AND a_date BETWEEN  '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
		$result_tea = mysql_query ( $sql_tea ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		$teacher_num = mysql_num_rows($result_tea);
		//echo $course_num;
		for($j=0; $j<$teacher_num ; $j++)
		{
			$row = mysql_fetch_array ( $result_tea );
			//$teacher_arr[] = $row['a_rname'];
			sname_rs($coursename,$row['a_rname'],$xuhao,$valid_time_range_begin_date,$valid_time_range_end_date);
			$xuhao++;
		}
	}
}
echo "</table>\n";
echo "<br /><br />";
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
  
  //coalesce_row(document.all.id1,1,0,'null');
 // coalesce_row(document.all.id2,1,0,'null');
  //coalesce_row(document.all.id3,1,0,'null');
  //coalesce_row(document.all.id4,1,0,'null');
 // coalesce_row(document.all.id11,1,0,'null');
  //coalesce_row(document.all.id12,1,0,'null');

  </script>
<!--单元格竖合并输出END-->



</body>
</html>

