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
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<title>登记记录添加结果</title>
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
		$valid_time_range_begin_date	有效时间区间的开始日期
		$valid_time_range_end_date		有效时间区间的结束日期
		$table_title					当前的年度及学期标题
		-----------------------------------------------------*/
        
        include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
        if($n<1) 
        echo "<script language='javascript'>alert('你无权进行此操作！');
                             location.href='index.html';</script>";
    
    
        $usercode = $_SESSION["u_type"];		  //获取权限代码
        
        if($usercode == '1')//教师权限直接用其真实姓名
            {
                $teachername = $_SESSION["u_name"];	//获取真实名称
            }
        
            else if ($usercode == '4' or $usercode == '5')//管理员权限要先获取教师名字
            {
                $teachername = $_POST['form_rname'];
            }
        
        
    
        $cname = $_POST['cname'];			//获取课程名字
        
        /*--------------------------判断课程名称是否在规定范围内BEGIN--------------------------------*/
        $cname_check_result = mysql_query("SELECT c_cname FROM v_cname WHERE c_cname='$cname'");
        $cname_check_result_row_num = mysql_num_rows($cname_check_result);//取得结果的行数
        if ($cname_check_result_row_num == 0 ) //没有结果，即还没有该课程的安排
        {
        	echo '<script>alert("提示：本系统中目前还没有课程“';
        	echo $cname;
			echo '”的相关信息！\n\n请与系统管理员联系，致电计算机学院计算机科学与技术实验教学中心陈杨杨老师\n\n电话：22861880(内线：1880)");</script>';
			
			echo '<script>history.back();</script>';//转回上一个页面 
			exit; 	
        }
 
        

echo '<div id="formwrapper"><!--表单外边框DIV_BEGIN-->';
/*echo	'<fieldset><!--表单第一个内边框DIV_BEGIN-->';
echo        '<legend>添加内容</legend><!--表单内边框标题-->';
echo        '<br />';*/

        
        /*--------------------------判断课程名称是否在规定范围内END----------------------------------*/
        
        $cdirection = $_POST['cdirection'];	//获取课程类别
		$sbook = $_POST['sbook'];           //获取实验教材<添加2009年9月1日10时58分42秒>
        $sid = $_POST['sid'];				//获取实验编号
        $sname = $_POST['sname'];			//获取实验项目
        $stype = $_POST['stype'];			//获取实验类型
            
        /*------------------------------------数组变量获取BEGIN--------------------------------------*/
        
            $time_count = 0;//用该变量统计时间段的条数
            /*----------------------------获取周数BEGIN--------------------------------*/
            foreach($_POST['sweek'] as $value) //
            {
                $sweek[] = $value;
                $time_count++;
            }
            /*----------------------------获取周数END----------------------------------*/
            
        
            
            /*----------------------------获取星期BEGIN--------------------------------*/
            foreach($_POST['sdate'] as $value) //
            {
                $sdate[] = $value;
            }
            /*----------------------------获取星期END----------------------------------*/
            
        
            
            /*----------------------------获取节次BEGIN--------------------------------*/
            foreach($_POST['sclass'] as $value) //
            {
                $sclass[] = $value;
            }
            /*----------------------------获取节次END----------------------------------*/

			//获取节次数
			$sclass_num = 0;//计算总节次
			for($i=0;$i<$time_count;$i++)
			{
				$sclass_array = explode(',', $sclass[$i]);//将各节次分开到数组中
				$sclass_array_num = count($sclass_array);
				$sclass_num = $sclass_num + $sclass_array_num;
			}
        
        /*------------------------------------数组变量获取END----------------------------------------*/	
    
        $grade = $_POST['grade'];			//获取年级
        $major = $_POST['major'];			//获取专业
        $class = $_POST['class'];			//获取班级
        $stu_num = $_POST['stu_num'];		//获取人数
        $learntime = $_POST['learntime'];	//获取计划学时
		//需要提交的节次判断实际学时
		//$stime = $_POST['stime'];			//获取实际学时
		$stime = $sclass_num;
        $resources = $_POST['resources'];	//获取资源需求
        $system = $_POST['system'];			//获取系统需求
        $software = $_POST['software'];		//获取软件需求		
    
        /*-------------------获取结果显示BEGIN-------------------*/	
        /*echo "课程名字：" .$cname ."<br />";
        echo "课程类别：" .$cdirection ."<br />";
		echo "实验教材：" .$sbook ."<br />";
        echo "实验编号：" .$sid ."<br />";
        echo "实验项目：" .$sname ."<br />";
        echo "实验类型：" .$stype ."<br />";
		echo "周　　数：" ;//print_r($sweek);
		  foreach($sweek as $sweek_value)
		  {
		  	echo $sweek_value . " " ;
		  }
		  echo "<br />";
		echo "星　　期：" ;//print_r($sdate);
		  foreach($sdate as $sdate_value)
		  {
		  	echo $sdate_value . " " ;
		  }
		  echo "<br />";
		echo "节　　次：" ;//print_r($sclass);
		  foreach($sclass as $sclass_value)
		  {
		  	echo $sclass_value . " " ;
		  }
		echo "<br />";
        echo "年　　级：" .$grade ."<br />";
        echo "专　　业：" .$major ."<br />";
        echo "班　　级：" .$class ."<br />";
        echo "人　　数：" .$stu_num ."<br />";
        echo "计划学时：" .$learntime ."<br />";
		echo "实际学时：" .$stime ."<br />";//需要提交的节次判断实际学时
		//echo "获得实际学时为：" .$sclass_num ."<br />";
        echo "资源需求：" .$resources ."<br />";
        echo "系统需求：" .$system ."<br />";
        echo "软件需求：" .$software ."<br />";
        echo "<p>&nbsp;</p></fieldset>";*/
		
		//表单第一个内边框DIV_END
        /*-------------------获取结果显示END-------------------*/
    
        
		
		echo "<fieldset>";//表单第二个内边框DIV_BEGIN
		echo "<legend>添加结果</legend>";//表单内边框标题
		echo "<br/>";
		
		
        /*--------------------------获取课程名字对应的实验室方向BEGIN------------------------*/
        $c_result = mysql_query ( "SELECT c_room FROM `course` WHERE c_cname = '$cname'" );
        $c_array = mysql_fetch_array ( $c_result ); 
        $direction = $c_array['c_room'];
        echo "该课程的所有备选方向：" . $direction . '<br />';
        /*--------------------------获取课程名字对应的实验室方向END--------------------------*/
        
        
        /*---------------------------------自动分配实验室房间BEGIN---------------------------------*/	
        //判断“实验室方向”是否有多个
        if( strstr( $direction , ',' ))//有多个候选“方向”的情况
        {
            $direction_array = explode(',', $direction);//将各“方向”分开到数组中
            echo "确定方向：" . $direction .'<br />';
            
			//-----------------------------------------
			//使用某数值代替已经达到人数需求则不用继续分配(等于0则没达到，1为达到)
			$conf = 0;
			FOR($j=0;$j<count($direction_array);$j++)
			{
				if($j==0)//对第一个方向的实验室安排
				{
				$capacity_result = mysql_query ( "SELECT r_number,r_capacity FROM `room` WHERE r_name = '$direction_array[$j]' AND r_state = 1");
				//取出与“方向”相关的实验室房间号及房间容量的记录
				$capacity_result_total_rows = mysql_num_rows($capacity_result);//取得结果记录条数
				$temp_array = mysql_fetch_array ( $capacity_result );
				$room_capacity = $temp_array['r_capacity'];
				$final_room = $temp_array['r_number'];//先分配出一间房
				$final_direction = $direction_array[$j];
				}
				if($j==1)//需要对第二个方向安排实验室
				{
				$capacity_result = mysql_query ( "SELECT r_number,r_capacity FROM `room` WHERE r_name = '$direction_array[$j]' AND r_state = 1");
				//取出与“方向”相关的实验室房间号及房间容量的记录
				$capacity_result_total_rows = mysql_num_rows($capacity_result);//取得结果记录条数
				$temp_array = mysql_fetch_array ( $capacity_result );
				$room_capacity += $temp_array['r_capacity'];
				$final_room .= ','.$temp_array['r_number'];//先分配出一间房
				$final_direction = $direction;
				}
				if( $stu_num > $room_capacity )//再判断是否需要分配给对方更多的房间
				{
					for ( $i = 1; $i < $capacity_result_total_rows; $i++) //用$i=1跳过上面的第[0]个元素
					{
						 $temp_array = mysql_fetch_array ( $capacity_result );
						 $final_room .= ','.$temp_array['r_number'];//注意多个房间在存储时依然用','分隔
						 $room_capacity += $temp_array['r_capacity'];	
						 if( $stu_num <= $room_capacity)//达到人数需求则不用继续分配
							{
								//使用某数值代替已经达到人数需求则不用继续分配
							   // break;
							   $conf = 1;
							}
							if( $conf == 1 )
					                 break;
					}
				}
				else//满足条件就跳出循环
					break;
				if( $conf == 1 )
					break;
			}
			//-----------------------------------------
        }
        else//只有唯一一个确定方向的情况，直接取出
        {
             echo "确定方向：" . $direction.'<br />';
             $final_direction = $direction;
			 //-------------------
			 $capacity_result = mysql_query ( "SELECT r_number,r_capacity FROM `room` WHERE r_name = '$final_direction' AND r_state = 1");
			 //取出与“方向”相关的实验室房间号及房间容量的记录
			 $capacity_result_total_rows = mysql_num_rows($capacity_result);//取得结果记录条数
			 $temp_array = mysql_fetch_array ( $capacity_result );
			 $room_capacity = $temp_array['r_capacity'];
			 $final_room = $temp_array['r_number'];//先分配出一间房
			 if( $stu_num > $room_capacity )//再判断是否需要分配给对方更多的房间
				{
					for ( $i = 1; $i < $capacity_result_total_rows; $i++) //用$i=1跳过上面的第[0]个元素
					{
						 $temp_array = mysql_fetch_array ( $capacity_result );
						 $final_room .= ','.$temp_array['r_number'];//注意多个房间在存储时依然用','分隔
						 $room_capacity += $temp_array['r_capacity'];	
						 if( $stu_num < $room_capacity)//达到人数需求则不用继续分配
							{
							    break;
							}
					}
				}
			 //-------------------
         }
        echo "预定房间号：" . $final_room . "<br />";//输出最终自动分配房间的结果
		//2009-12-6（确定第一个时间段的时间，第二个时间段也就按照第一个时间的确定房号）
        /*---------------------------------自动分配实验室房间END---------------------------------*/	
        
    
        /*---------------------------------写入数据库BEGIN---------------------------------*/
        
        $sql = "SELECT a_id FROM `apply1` WHERE a_rname = '$teachername' AND a_sid = '$sid' AND a_cname = '$cname' AND a_grade='$grade' AND a_major='$major' AND a_class='$class' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
        $result = mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句
		$row = mysql_fetch_array( $result );//读取一行数据

        $n = mysql_affected_rows ( $conn );//$connect是连接数据库时的标量
        if($n==1)//判断是否重复申请
        {
            echo "<br />您已经申请过".$cname."的实验".$sid."，请不要重复申请！";
			echo "</fieldset>";//表单第二个内边框DIV_END				
			echo "<br />";
        }
            else//非重复申请的情况（分2部分提交登记表、时间表）
            {
				//提交数据到登记表信息
				
				$sql = sprintf ( "INSERT INTO apply1 (a_rname,a_cname,a_cdirection,a_ctype,a_sbook,a_sid,a_sname,a_stype,a_grade,a_major,a_class,a_people,a_learntime,a_stime,a_resources,a_system,a_software,a_date) VALUES ('%s','%s','%s','%s','%s','%d','%s','%s','%s','%s','%s','%d','%d','%d','%s','%s','%s',CURDATE())", $teachername,$cname,$final_direction,$cdirection,$sbook,$sid,$sname,$stype,$grade,$major,$class,$stu_num,$learntime,$stime,$resources,$system,$software);
                //注意通过INSERT录入数据的格式,其中的'%s'表示该位置将由后面一个字符串变量代替
                mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句
				
				/*获取登记表a_id*/
				$sql_aid = "SELECT * FROM apply1 WHERE a_rname='$teachername' AND a_cname='$cname' AND a_cdirection='$final_direction' AND a_ctype='$cdirection' AND a_sbook='$sbook' AND a_sid='$sid' AND a_sname='$sname' AND a_stype='$stype' AND a_resources='$resources'  AND a_grade='$grade' AND a_major='$major' AND a_class='$class' AND a_people='$stu_num' AND a_learntime='$learntime' AND a_stime='$stime' AND a_system='$system' AND a_software='$software'  AND a_date=CURDATE()";
                $rs_aid = mysql_query ( $sql_aid ) or die ( "获取a_id时发生错误:" . mysql_error() );
                $row_aid = mysql_fetch_array( $rs_aid );
				//提交数据到时间段
                for($i=0; $i<$time_count; $i++)
                {
                    //获取时间表id
					$sql = sprintf ( "INSERT INTO time (a_id,s_id,a_sweek,a_sdate,a_sclass,a_room) VALUES ('%d','%d','%s','%s','%s','%s')",$row_aid['a_id'],$sid,$sweek[$i],$sdate[$i],$sclass[$i],$final_room );
                    //注意通过INSERT录入数据的格式,其中的'%s'表示该位置将由后面一个字符串变量代替
                    mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句


					     
                }
                $n = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
                $msg = ( $n == 1 ) ? "登记记录添加成功！" : "登记记录添加失败！";
                if($n==1) include('../log/log_txdjxx.inc'); //写入日志
                echo "<br />" . $msg;
				echo "</fieldset>";//表单第二个内边框DIV_END				
				echo "<br />";
				//
				/*
				提交后输出该老师及本课程的所有实验项目信息
				*/
				//$sql_edit = sprintf ("SELECT a_cname AS 课程名称, a_rname AS 教师名称, a_sid AS 实验编号,a_sname AS 实验项目名称, a_id AS 操作1 FROM `apply1` WHERE a_id='%d'",$row_aid['a_id']);
				$sql_edit = "SELECT a_cname AS 课程名称, a_rname AS 教师名称, a_sid AS 实验编号,a_sname AS 实验项目名称, a_id AS 操作1, a_grade AS 年级, a_major AS 专业, a_class AS 班级 FROM `apply1` where a_rname='{$teachername}' AND a_cname='{$cname}' AND a_grade='{$grade}' AND a_major='${major}' AND a_class='${class}' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
				
				$rs_edit = mysql_query ( $sql_edit ) or die ( "不能查询指定id：" . mysql_error() );
				$row_num = mysql_num_rows($rs_edit); //影响数目
				$row = mysql_fetch_array( $rs_edit );
	
				echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
				//echo "<caption>"</caption>\n";
				echo "<tr>\n";
				echo "<th>课程名称</th>";
				echo "<th>老师名称</th>";
				echo "<th>班级</th>";
				echo "<th>实验编号</th>";
				echo "<th>实验项目</th>";
				echo "<th>周次</th>";
				echo "<th>星期</th>";
				echo "<th>节次</th>";
				echo "<th>修改</th>";
				echo "<th>删除</th>";
				echo "</tr>\n";
		//$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[2],$row[4]);
for($i=0;$i<$row_num;$i++)
	{
		
		$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[2],$row[4]);
		$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		$row_num_tid = mysql_num_rows($result_tid);       //获取影响行的数目
		$row_tid = mysql_fetch_array($result_tid);
		
		
		
		for($n=0;$n<$row_num_tid;$n++)//应该用时间段的行数
		{
			echo "<tr>";
		    echo "<td align=\"center\" id=\"id0\" >".$row[0]."</td>";//课程名字
		    echo "<td align=\"center\" id=\"id1\" >".$row[1]."</td>";//老师名称
			echo "<td align=\"center\" id=\"id1\" >".$row[5].$row[6].$row[7]."</td>";//班级
			echo "<td align=\"center\" id=\"id2\" >".$row[2]."</td>";//实验编号
			echo "<td align=\"center\" id=\"id3\" >".$row[3]."</td>";//实验名称
			//时间段
			echo "<td align=\"center\" id=\"id4\">".$row_tid[0]."</td>";//周次
			echo "<td align=\"center\" id=\"id5\">".$row_tid[1]."</td>";//星期
			echo "<td align=\"center\" id=\"id6\">".$row_tid[2]."</td>";//节次

			//单独添加"修改链接"	
			//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
			//$row[4]表示登记表的编号
			$search_condition = "a_rname";
			$search_content = $teachername;
			echo "<td align=\"center\" id=\"id7\">
		    <img src=\"../images/edit.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_edit_single_add.php?a_id=$row[4]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='修改'/>
			</td>\n";
			//增加删除操作2
			echo "<td align=\"center\" id=\"id8\">
	        <img src=\"../images/delete.gif\"  style=\"cursor:hand;\" onClick='javascript:if(confirm(\"您确实要删除该记录吗？\"))location=\"course_register_delete_result.php?a_id=$row[4]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='删除'/>
		    </td>\n";
			echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);

		}
		$row = mysql_fetch_array( $rs_edit );//获取相同课程的a_id
	}
		echo "</table>";
				//
				echo "<img src=\"../images/add_this_course_another.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_add_continue.php?a_id=$row_aid[0]\"'/>";
				
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<img src=\"../images/end_add_course.gif\" target='_parent'  style=\"cursor:hand;\" onClick='location.href=\"../frame.php\"'/>";
				//echo "<a href='../frame.php' target='_parent' style='margin-left: 20px;padding: 0 11px;background: #02bafa;border: 1px #26bbdb solid;border-radius: 3px;display: inline-block;text-decoration: none;font-size: 12px;outline: none;'>结束填写课程信息</a>";
				
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                
            }
    
        /*---------------------------------写入数据库END---------------------------------*/	
   
    ?>
	<img src="../images/add_another_course.gif"  style="cursor:hand;" onClick="location.href='course_register.php'"/>
    <br /><br />
	<?php
		require("list_the_different_class_by_teacher_course.php");
		displayAllOtherClass($teachername, $cname, $grade, $major, $class, $valid_time_range_begin_date, $valid_time_range_end_date);
	?>
</div><!--表单外边框DIV_END-->

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
  coalesce_row(document.all.id0,1,0,'null')     
  coalesce_row(document.all.id1,1,0,'null')   
  //coalesce_row(document.all.id2,1,0,'null') 
  //coalesce_row(document.all.id3,1,0,'null')
  //coalesce_row(document.all.id4,1,0,'null')
 //coalesce_row(document.all.id7,1,0,'null')
  //coalesce_row(document.all.id8,1,0,'null')
  </script>
<!--单元格竖合并输出END-->
</body>
</html>