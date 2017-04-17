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

<title>单条记录修改结果</title>
</head>
<body>

<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单第一个内边框DIV_BEGIN-->
        <legend>修改内容</legend><!--表单内边框标题-->
        <br />

	<?php
        include("../common/db_conn.inc");
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
    
    
		$search_condition = $_POST['search_condition']; //用于返回到上一个页面的“搜索条件”
		$search_content = urldecode($_POST['search_content']); //用于返回到上一个页面的“搜索内容”
        $a_id = $_POST['a_id']; //获取记录编号
    
        $cname = $_POST['cname'];			//获取课程名字
        $cdirection = $_POST['cdirection'];	//获取课程类别
		$sbook = $_POST['sbook'];           //获取实验教材
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


			/*----------------------------获取房间号BEGIN--------------------------------*/
			//获取多个时间段的房间号
			if($usercode == '4' or $usercode == '5')//管理员功能
			{
            foreach($_POST['form_room'] as $value) //
            {
                $form_room[] = $value;
            }
			}
            /*----------------------------获取房间号END----------------------------------*/

        
        /*------------------------------------数组变量获取END----------------------------------------*/	
    
        $grade = $_POST['grade'];			//获取年级
        $major = $_POST['major'];			//获取专业
        $class = $_POST['class'];			//获取班级
        $stu_num = $_POST['stu_num'];		//获取人数
        $learntime = $_POST['learntime'];	//获取计划学时
		//$stime = $_POST['stime'];			//获取实际学时
		$stime = $sclass_num;
        $resources = $_POST['resources'];	//获取资源需求
        $system = $_POST['system'];			//获取系统需求
        $software = $_POST['software'];		//获取软件需求		
    
        /*-------------------获取结果显示BEGIN-------------------*/	
		//echo "任课老师".$teachername."<br />";
        echo "课程名字：" .$cname ."<br />";
        echo "课程类别：" .$cdirection ."<br />";
		echo "实验教材：" .$sbook ."<br />";
        echo "实验编号：" .$sid ."<br />";
        echo "实验项目：" .$sname ."<br />";
        echo "实验类型：" .$stype ."<br />";
		//
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
		//
        echo "年　　级：" .$grade ."<br />";
        echo "专　　业：" .$major ."<br />";
        echo "班　　级：" .$class ."<br />";
        echo "人　　数：" .$stu_num ."<br />";
        echo "计划学时：" .$learntime ."<br />";
		echo "实际学时：" .$stime ."<br />";
        echo "资源需求：" .$resources ."<br />";
        echo "系统需求：" .$system ."<br />";
        echo "软件需求：" .$software ."<br />";
		?>
		<p>&nbsp;</p></fieldset>
		<?php
		//表单第一个内边框DIV_END
        /*-------------------获取结果显示END-------------------*/
        
		
		
		echo "<fieldset>";//表单第二个内边框DIV_BEGIN
		echo "<legend>修改结果</legend>";//表单内边框标题
		echo "<br/>";
		
		
		
        /*--------------------------获取课程名字对应的实验室方向BEGIN------------------------*/
        $c_result = mysql_query ( "SELECT c_room FROM `course` WHERE c_cname = '$cname'" );
        $c_array = mysql_fetch_array ( $c_result ); 
        $direction = $c_array['c_room'];
        echo "该课程的所有备选方向：" . $direction . '<br />';
        /*--------------------------获取课程名字对应的实验室方向END--------------------------*/
    
        /*---------------------------------自动分配实验室房间BEGIN---------------------------------*/	
        //判断“实验室方向”是否有多个
		/*
        if( strstr( $direction , ',' ))//有多个候选“方向”的情况
        {
            $direction_array = explode(',', $direction);//将各“方向”分开到数组中
            echo "确定方向：" . $direction_array[0].'<br />';
            $final_direction = $direction_array[0];//取“方向”数组中的第一个作为首选
        }
        else//只有唯一一个确定方向的情况，直接取出
            {
                echo "确定方向：" . $direction.'<br />';
                $final_direction = $direction;
            }
			*/
    
		//教师用户采取自动分配房间的方式（修改信息表是，教师用户重新自动分配房间）
        if ($usercode == '1')  
        {
			//判断“实验室方向”是否有多个
			if( strstr( $direction , ',' ))//有多个候选“方向”的情况
			{
				$direction_array = explode(',', $direction);//将各“方向”分开到数组中
				echo "预定方向：" . $direction.'<br />';
				//$final_direction = $direction_array[0];//取“方向”数组中的第一个作为首选
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
            }//end else    
        }//end if教师用户
        
        /*---------------------------------自动分配实验室房间END---------------------------------*/	
        
        //管理员权限直接获取提交修改的房间号变量
        if ($usercode == '4' or $usercode == '5')
            {	
				for($i=0;$i<count($form_room);$i++)
				{
					//$final_room = $_POST['form_room'];
					$final_room .= '时间段'.($i+1).'：'.$form_room[$i];
					
					//改了房间后更改相应的实验室方向（这也关系到反馈信息表的负责人显示）
					//$cut = substr( $form_room[$i] , 0 , 5);//截取前五个字符，相当于一间房
					$room_num_array = explode(',', $form_room[$i]);//将各“方向”分开到数组中
					for($room_num_id=0;$room_num_id<count($room_num_array);$room_num_id++)
					{
						$cut = $room_num_array[$room_num_id];
						$direction_rs = mysql_query("SELECT r_name FROM room WHERE r_number='$cut'");
						$direction_rs_array = mysql_fetch_array($direction_rs);
						$final_direction .= $direction_rs_array["r_name"].',';//更新方向
					}
					
				}           
            }
        $final_direction = substr($final_direction,0,strlen($final_direction)-1);//去掉最后1个字符 
		$final_direction_array = explode(',', $final_direction);//将各“方向”分开到数组中
		//去掉重复值
		$final_direction_array = array_unique($final_direction_array);
		$final_direction = implode(",", $final_direction_array);
        echo "确定房间号：" . $final_room . "<br />";//输出最终自动分配房间的结果
        
    
        
        
        
        /*---------------------------------更新数据库BEGIN---------------------------------*/
        //修改（分为登记表和时间表apply1、time）
        $sql = sprintf ( "UPDATE apply1 SET a_rname='$teachername',a_cname='%s',a_cdirection='%s',a_ctype='%s',a_sbook='%s',a_sid='%d',a_sname='%s',a_stype='%s',a_grade='%s',a_major='%s',a_class='%s',a_people='%d',a_learntime='%d',a_stime='%d',a_resources='%s',a_system='%s',a_software='%s' WHERE a_id='$a_id'",$cname,$final_direction,$cdirection,$sbook,$sid,$sname,$stype,$grade,$major,$class,$stu_num,$learntime,$stime,$resources,$system,$software);
		//printf($sql);die();
        //注意通过INSERT录入数据的格式,其中的'%s'表示该位置将由后面一个字符串变量代替
        mysql_query ( $sql ) or die ( "更新操作出错：" .mysql_error() );  //向数据库执行SQL语句

		$m = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
        $msg = ( $m == 1 ) ? "登记记录修改成功！" : "没有修改内容或修改失败！";

        $sql_tid_num = "SELECT * FROM time WHERE a_id='$a_id'";
		$rs_tid_num = mysql_query( $sql_tid_num ) or die(mysql_error());
		$tid_num = mysql_num_rows($rs_tid_num);//获取影响行数
		//---------------------------对于原有的数据进行更新-----------------------------------2009-12-5
		//分两部分
		//1、管理员的更新修改
		//2、教师修改
		if($usercode == '4' or $usercode == '5')
		{
		for($i=0;$i<$tid_num;$i++)
		{
			$row_tid_num = mysql_fetch_array($rs_tid_num);//获得一行数据(a_sid、周次、星期。节次、房号)
			$sql_new = sprintf ("UPDATE time SET a_id='%d',s_id='%d', a_sweek='%s',a_sdate='%s',a_sclass='%s',a_room='%s' WHERE t_id='$row_tid_num[0]'",$a_id,$sid,$sweek[$i],$sdate[$i],$sclass[$i],$form_room[$i]);
			mysql_query ($sql_new) or die ( mysql_error() );//向数据库写入数据
			if($m!=1)
			{
				$m = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
				$msg = ( $m == 1 ) ? "登记记录修改成功！" : "没有修改内容或修改失败！";
			}
		}
		}
		//2、教师修改
		if($usercode == '1')
		{
			for($i=0;$i<$tid_num;$i++)
		{
			$row_tid_num = mysql_fetch_array($rs_tid_num);//获得一行数据(a_sid、周次、星期。节次、房号)
			$sql_new = sprintf ("UPDATE time SET a_id='%d',s_id='%d', a_sweek='%s',a_sdate='%s',a_sclass='%s',a_room='%s' WHERE t_id='$row_tid_num[0]'",$a_id,$sid,$sweek[$i],$sdate[$i],$sclass[$i],$final_room);
			mysql_query ($sql_new) or die ( mysql_error() );//向数据库写入数据
			if($m!=1)
			{
				$m = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
				$msg = ( $m == 1 ) ? "登记记录修改成功！" : "没有修改内容或修改失败！";
			}
		}
		}

        //----------------------------新增时间段采用插入操作-------------------------------------
        if($time_count-$tid_num>0)
        {        
            for($tid_num; $tid_num<$time_count; $tid_num++)
            { 
				//新增加的插入操作（时间段的数据插入）
				if($usercode == '1')
				{
					$sql = sprintf ("INSERT INTO time (a_id,s_id,a_sweek,a_sdate,a_sclass,a_room) VALUES ('%d','%d','%s','%s','%s','%s')",$a_id,$sid,$sweek[$tid_num],$sdate[$tid_num],$sclass[$tid_num],$final_room);
				}
				if($usercode == '4' or $usercode == '5')
				{
					$sql = sprintf ("INSERT INTO time (a_id,s_id,a_sweek,a_sdate,a_sclass,a_room) VALUES ('%d','%d','%s','%s','%s','%s')",$a_id,$sid,$sweek[$tid_num],$sdate[$tid_num],$sclass[$tid_num],$form_room[0]);
				}
                //注意通过INSERT录入数据的格式,其中的'%s'表示该位置将由后面一个字符串变量代替
                mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句                            
            }
			if($m!=1)
			{
				$m = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
				$msg = ( $m == 1 ) ? "登记记录修改成功！" : "没有修改内容或修改失败！";
			}
			
		}
        if($m==1) include('../log/log_xgdjxx.inc'); //写入日志
		echo "<br />" . $msg;
        
		echo "</fieldset>";//表单第二个内边框DIV_END				
		echo "<br />";
        /*---------------------------------更新数据库END---------------------------------*/
    ?>

			<?php
			if( $_SESSION["u_type"] == '1' or $_SESSION["u_type"] == '2')//给教师及负责人的链接
			{
				echo "<img src=\"../images/edit_continue.gif\"  style=\"cursor:hand;\" onClick=\"location.href='course_register_edit_show.php'\"/>";
			}
				else//管理员的链接
				{
					echo "<img src=\"../images/edit_continue.gif\"  style=\"cursor:hand;\" onclick=\"location.href='course_register_edit_show.php?turn_back_Scondition=".$search_condition."&turn_back_Scontent=".urlencode($search_content)."'\"/>";
				}
	
	        ?>
	<!--img src="../images/edit_continue.gif"  style="cursor:hand;" onClick="location.href='course_register_edit_show.php'"/-->
	<!--a href="">继续修改其它记录</a-->
    <br /><br />
</div><!--表单外边框DIV_END-->
</body>
</html>