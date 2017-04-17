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
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<title>备份所有实验课信息登记表</title>

</head>
<body>

<?php
	include("../common/db_conn.inc");
	include("../common/excel.inc");
	
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
	/*-----------------------获取每种实验室类型对应的房间号并整合成数组BEGIN----------------------------*/
//引入后提供的变量是$final_RT_room_arr实验室类型与实验室关联数组以及$final_RT_room_arr_total_num该数组内元素的个数
	include("../common/func_getRoomtype_NO.php");
	/*-----------------------获取每种实验室类型对应的房间号并整合成数组END------------------------------*/
	
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	set_time_limit(0);
	if($n<2) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";

	$user_code = $_SESSION["u_type"];
	if($user_code==2)//负责人
	{
		
		
	    echo '<form name="applyform" id="applyform" method="post">请选择学期：';
        echo '<select name="term">';
			
		   //获取历史学期记录
		   $hisres=mysql_query("SELECT * FROM date_week ORDER BY first_week_date DESC");
		   while($hisrow=mysql_fetch_array($hisres))
		   { 
			echo "<option value=\"".$hisrow['first_week_date']."\"";
			if($first_week_date==$hisrow["first_week_date"])
			{ echo " selected=\"selected\" "; } 
			 echo ">".$hisrow["term"]."</option>";
		   } 
	     echo '</select>';
		 echo '&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="submit" value="查看" />';
		 echo '</form>';
		 
		
		$admin_name = $_SESSION["u_name"];	
		$sql_roomnumber = "SELECT r_number FROM `room` WHERE  r_admin LIKE '%".$admin_name."%' AND r_state=1";
		$rs_roomnumber =mysql_query( $sql_roomnumber ) or die("不能查询指定的数据库表：" . mysql_error());
		$roomnumber_num = mysql_num_rows($rs_roomnumber);
		for($i=0;$i<$roomnumber_num;$i++)
		{
			$row_roomnumber = mysql_fetch_array( $rs_roomnumber );
			$room_arr[] = $row_roomnumber['r_number'];
			$sql2="SELECT DISTINCT a_rname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE a_room LIKE '%".$room_arr[$i]."%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //获取教师信息
			$result2 = mysql_query ( $sql2 ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$teacher_num = mysql_num_rows($result2);
			for($j=0; $j<$teacher_num ; $j++)
			{
				$row2 = mysql_fetch_array ( $result2 );
				$teacher_arr[] = $row2['a_rname'];
				//echo $row2['a_rname'];
			}
		}
	}
	else//其他用户
	{
			 //echo $_POST['term'];echo urldecode($_POST['labtype']);
		echo '<form name="applyform" id="applyform" method="post">请选择学期：';
        echo '<select name="term">';
			
		   //获取历史学期记录
		   $hisres=mysql_query("SELECT * FROM date_week ORDER BY first_week_date DESC");
		   while($hisrow=mysql_fetch_array($hisres))
		   { 
			echo "<option value=\"".$hisrow['first_week_date']."\"";
			if($first_week_date==$hisrow["first_week_date"])
			{ echo " selected=\"selected\" "; } 
			 echo ">".$hisrow["term"]."</option>";
		   } 
	     echo '</select>';
		 	/*-----------------------列出可选的实验室类型BEGIN----------------------------*/
		 echo '&nbsp;实验室类型：';
         echo '<select name="labtype">';
		 echo "<option value=\"\" selected=\"selected\"  >--所有实验室类型--</option>";
			for ($arr_i=0; $arr_i<$final_RT_room_arr_total_num; $arr_i++)
				{
					echo "<option value=\"".urlencode($final_RT_room_arr[$arr_i][0])."\"";
				    if((urldecode($_POST['labtype']))==$final_RT_room_arr[$arr_i][0])
					{ echo " selected=\"selected\" "; } 
				    echo ">".$final_RT_room_arr[$arr_i][0]."</option>";
				}
				
	     echo '</select>';
		 /*-----------------------列出可选的实验室类型END----------------------------*/
		 echo '&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="submit" value="查看" />';
		 echo '</form>';
		
		///////////////////////////////////////////////////
		
		$admin_name = $_SESSION["u_name"];	
		$sql_roomnumber = "SELECT r_number FROM `room` WHERE  r_name  LIKE '%".urldecode($_POST['labtype'])."%' AND r_state=1";
		$rs_roomnumber =mysql_query( $sql_roomnumber ) or die("不能查询指定的数据库表：" . mysql_error());
		$roomnumber_num = mysql_num_rows($rs_roomnumber);
		for($i=0;$i<$roomnumber_num;$i++)
		{
			$row_roomnumber = mysql_fetch_array( $rs_roomnumber );
			$room_arr[] = $row_roomnumber['r_number'];
		///////////////////////////////////////////////////
		
		
			
			/*$sql2="SELECT DISTINCT a_rname FROM `apply1` WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";   获取教师信息*/
			
			$sql2="SELECT DISTINCT a_rname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE a_room LIKE '%".$room_arr[$i]."%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //获取教师信息
			$result2 = mysql_query ( $sql2 ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$teacher_num = mysql_num_rows($result2);
			for($j=0; $j<$teacher_num ; $j++)
			{
				$row2 = mysql_fetch_array ( $result2 );
				$teacher_arr[] = $row2['a_rname'];
				//echo $row2['a_rname'];
			}
			///////////////////////////////////////////////////
		}
	}
	//对数组的各个值进行比较，只保留不同项(用数组函数array_values，array_unique)
	if($teacher_arr){
		$teacher_arr = array_values(array_unique($teacher_arr));
	}
	


/*函数courseRegisterTable()*/
/*功能：根据教师姓名与课程姓名输出实验课信息登记表*/
/*传入：教师名、课程名*/
/*传出：单个实验课信息登记表*/
/*作者：陈灿*/


	function courseRegisterTable($teachername,$coursename,$admin_name,$valid_time_range_begin_date,$valid_time_range_end_date)
	{
		//a_cdirection IN (SELECT r_name FROM room WHERE r_admin LIKE '%".$admin_name."%') AND
		$sql = "SELECT a_id, a_rname , a_cname , a_ctype AS 课程类别, a_sbook , a_sid AS 实验编号 , a_sname AS 实验项目 , a_stype, a_grade, a_major, a_class, a_people, a_learntime , a_stime , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求  FROM `apply1`  WHERE  a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename'  ORDER BY `a_cname`,`a_sid`";
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
			//一个实验有多个时间段处理AND a_room IN (SELECT r_number FROM `room` WHERE r_admin='$admin_name' )
			$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次,a_room AS 实验安排 FROM time WHERE s_id='%d' AND a_id='%d'  ORDER BY `a_sweek`",$row[5],$row[0]);
			//print_r($sql_tid); 
			$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$row_num_tid = mysql_num_rows($result_tid);			//获取影响行的数目
			$row_tid = mysql_fetch_array($result_tid);			//获取多行数据的一行
			$cont = 0;
			for($num_tid=0;$num_tid<$row_num_tid;$num_tid++)//多个时间段输出
			{
			echo "<tr bgcolor=\"#FFFFFF\">\n";
			//查询整个关于老师跟课程的所有时间段的条数，作为老师名称显示表格的合并
			$sql_rowspan = "SELECT a_id FROM `time`  WHERE a_id IN (SELECT a_id FROM `apply1` WHERE  a_date BETWEEN '$valid_time_range_begin_date' AND '$valid_time_range_end_date' AND a_rname='$teachername' AND a_cname='$coursename')";
			//printf($sql_rowspan);
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
				echo "<td rowspan='$rowspan' align=\"center\">{$row[4]}</td>";
			}
			$hebing++;//用于老师名称显示合并（判断）
			for($j=5;$j<8;$j++)//编号、项目、类型
				{
					if($cont==0)//当变量为0的时候则合并单元格
						{
						echo "<td align=\"center\" id=\"id$j\"  rowspan='$row_num_tid'>{$row[$j]}</td>\n";
						}
				}
			for($t_j=0;$t_j<3;$t_j++)//周次、星期、节次
				{
					$id_t = $t_j+8;
					echo "<td align=\"center\" id=\"id$id_t\">{$row_tid[$t_j]}</td>\n";
				}
			for ( $j = 8; $j < mysql_num_fields($result); $j++ ) //从第4列开始
		  	{
				//id从10开始
				$j_id = $j+3;
				if($j==11 or $j==10 or $j==9 or $j==8 or $j==13 or $j==12)//对年级、专业、班级、人数、计划学时特别处理
					{
						if($cont==0)//当变量为0的时候则合并单元格
						{
						echo "<td align=\"center\" id=\"id$j_id\" rowspan='$row_num_tid'>{$row[$j]}</td>\n";
						
						}

					}
					else
						echo "<td align=\"center\" id=\"id$j_id\">{$row[$j]}</td>\n";		    	
		    }
			$cont++;//改变数值（用于合并）
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

&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="method1('content');">= 导出到Excel =</a><br />

<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?php echo $table_title; if($user_code==2) echo "您负责的实验室相关的实验课信息登记表汇总如下"; else echo "&nbsp;".urldecode($_POST['labtype'])."&nbsp;实验课信息登记表汇总如下";?></p>


<span id="content">  

	<?php
	
		for($j=0 ; $j<count($teacher_arr); $j++)
		{
			//
			$teachername = $teacher_arr[$j];
			if($user_code==2)//负责人
			{
				for($r_num=0;$r_num<count($room_arr);$r_num++)
				{
					$sql="SELECT DISTINCT a_cname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE a_room LIKE '%".$room_arr[$r_num]."%') AND a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //获取教师信息
					//print_r($sql);
					$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
					$course_num = mysql_num_rows($result);		
					for($i=0; $i<$course_num ; $i++)
					{
						$row = mysql_fetch_array ( $result );
						$course_arr[] = $row['a_cname'];
					}
				}
			}
			else//其他用户
			{
				for($r_num=0;$r_num<count($room_arr);$r_num++)
				{
				    $sql="SELECT DISTINCT a_cname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE a_room LIKE '%".$room_arr[$r_num]."%') AND a_rname='$teachername' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";  //获取教师信息
					//print_r($sql);
					$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
					$course_num = mysql_num_rows($result);		
					for($i=0; $i<$course_num ; $i++)
					{
						$row = mysql_fetch_array ( $result );
						$course_arr[] = $row['a_cname'];
					}
				}
			}
			//对数组的各个值进行比较，只保留不同项(用数组函数array_values，array_unique)
			$course_arr = array_values(array_unique($course_arr));
			for($i=0; $i<count($course_arr) ; $i++)
			{
			//函数功能：根据老师名称、课程名称、负责人查找相关的信息表$course_arr[$i]
			courseRegisterTable($teachername,$course_arr[$i],$admin_name,$valid_time_range_begin_date,$valid_time_range_end_date);
			}
			//放结果的数组
			unset($course_arr);
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
  //coalesce_row(document.all.id4,1,0,'null')
  //coalesce_row(document.all.id5,1,0,'null')
  //coalesce_row(document.all.id8,1,0,'null')
  </script>
<!--单元格竖合并输出END-->
</body>
</html>