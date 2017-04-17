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
	if($n<4) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$usercode = $_SESSION["u_type"];		  //获取权限代码
	
	/*
	if($usercode == '1')//教师权限直接用其真实姓名
		{
			$teachername = $_SESSION["u_name"];	//获取真实名称
		}
	
		else if ($usercode == '5' or $usercode == '4')//管理员权限要先获取教师名字
		{
			$teachername = $_POST['form_rname'];
		}
		*/
	
	$a_id = $_POST['a_id']; //获取记录编号
	$t_id = $_POST['t_id']; //获取记录编号
	$teachername = $_POST['form_rname'];	
	$cname = $_POST['cname'];			//获取课程名字
	$cdirection = $_POST['cdirection'];	//获取课程类别
	$sbook = $_POST['sbook'];			//获取实验教材
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
	
	/*------------------------------------数组变量获取END----------------------------------------*/	

	$grade = $_POST['grade'];			//获取年级
	$major = $_POST['major'];			//获取专业
	$class = $_POST['class'];			//获取班级
	$stu_num = $_POST['stu_num'];		//获取人数
	$learntime = $_POST['learntime'];	//获取计划学时
	$stime = $_POST['stime'];			//获取实际学时
	$resources = $_POST['resources'];	//获取资源需求
	$system = $_POST['system'];			//获取系统需求
	$software = $_POST['software'];		//获取软件需求		

	/*-------------------获取结果显示BEGIN-------------------*/	
	echo " 教师名字：" .$teachername."<br />";
	echo " 课程名字：" .$cname ."<br />";
	echo " 课程类别：" .$cdirection ."<br />";
	echo " 实验教材：" .$sbook ."<br />";
	echo " 实验编号：" .$sid ."<br />";
	echo " 实验项目：" .$sname ."<br />";
	echo " 实验类型：" .$stype ."<br />";
	echo " 周　　数：" ;//print_r($sweek);
	  foreach($sweek as $sweek_value)
	  {
	  	echo $sweek_value . " " ;
	  }
	  echo "<br />";
	echo " 星　　期：" ;//print_r($sdate);
	  foreach($sdate as $sdate_value)
	  {
	  	echo $sdate_value . " " ;
	  }
	  echo "<br />";
	echo " 节　　次：" ;//print_r($sclass);
	  foreach($sclass as $sclass_value)
	  {
	  	echo $sclass_value . " " ;
	  }
	echo "<br />";
	echo " 年　　级：" .$grade ."<br />";
	echo " 专　　业：" .$major ."<br />";
	echo " 班　　级：" .$class ."<br />";
	echo " 人　　数：" .$stu_num ."<br />";
	echo " 计划学时：" .$learntime ."<br />";
	echo " 实际学时：" .$stime ."<br />";
	echo " 资源需求：" .$resources ."<br />";
	echo " 系统需求：" .$system ."<br />";
	echo " 软件需求：" .$software ."<br />";
    echo " <p>&nbsp;</p></fieldset>";
	//表单第一个内边框DIV_END
    /*-------------------获取结果显示END-------------------*/
        
		
		
	echo "<fieldset>";//表单第二个内边框DIV_BEGIN
	echo "<legend>修改结果</legend>";//表单内边框标题
	echo "<br/>";
	
	/*--------------------------获取课程名字对应的实验室方向BEGIN------------------------*/
	//$c_result = mysql_query ( "SELECT c_room FROM `course` WHERE c_cname = '$cname'" );
	//$c_array = mysql_fetch_array ( $c_result ); 
	//$direction = $c_array['c_room'];
	//echo "该课程的所有备选方向：" . $direction . '<br />';
	/*--------------------------获取课程名字对应的实验室方向END--------------------------*/

	/*---------------------------------自动分配实验室房间BEGIN---------------------------------*/	
	//判断“实验室方向”是否有多个
	//if( strstr( $direction , ',' ))//有多个候选“方向”的情况
	/*{
		$direction_array = explode(',', $direction);//将各“方向”分开到数组中
		echo "确定方向：" . $direction_array[0].'<br />';
		$final_direction = $direction_array[0];//取“方向”数组中的第一个作为首选
	}
	else//只有唯一一个确定方向的情况，直接取出
	{
		echo "确定方向：" . $direction.'<br />';
		$final_direction = $direction;
	}*/
	
	/*---------------------------------自动分配实验室房间END---------------------------------*/	
			
			$final_room = $_POST['form_room'];
			$room_array = explode(',', $final_room);//将各“房间号”分开到数组中
            //改了房间后更改相应的实验室方向（这也关系到反馈信息表的负责人显示）
			for($i=0;$i<count($room_array);$i++)
			{
				$room = $room_array[$i];
				$direction_rs = mysql_query("SELECT r_name FROM room WHERE r_number='$room'");
				$direction_rs_array = mysql_fetch_array( $direction_rs );
				$final_direction_array []= $direction_rs_array["r_name"];//更新方向
			}
			//对数组的各个值进行比较，只保留不同项(用数组函数array_values，array_unique)
			$final_direction_array = array_values(array_unique($final_direction_array));
			//取得房间号的字符串
			for($j=0;$j<count($final_direction_array);$j++)
			{
				$final_direction .= $final_direction_array[$j].',';
			}
			$final_direction = substr($final_direction,0,strlen($final_direction)-1);//去掉最后一个字符，即逗号

	
	echo "确定房间号：" . $final_room . "<br />";//输出最终自动分配房间的结果
	

	
	
	
	/*---------------------------------更新数据库BEGIN---------------------------------*/
	//更新登记表,a_sweek='%s',a_sdate='%s',a_sclass='%s',a_room='%s'
	$sql = sprintf ( "UPDATE apply1 SET a_rname='$teachername',a_cname='%s',a_cdirection='%s',a_ctype='%s',a_sbook='%s',a_sid='%s',a_sname='%s',a_stype='%s',a_learntime='%d',a_stime='%d',a_grade='%s',a_major='%s',a_class='%s',a_people='%d',a_resources='%s',a_system='%s',a_software='%s' WHERE a_id='$a_id'",$cname,$final_direction,$cdirection,$sbook,$sid,$sname,$stype,$learntime,$stime,$grade,$major,$class,$stu_num,$resources,$system,$software);
	//注意通过INSERT录入数据的格式,其中的'%s'表示该位置将由后面一个字符串变量代替
	mysql_query ( $sql ) or die ( "更新操作出错：" .mysql_error() );  //向数据库执行SQL语句
	
	//更新时间表(根据a_id/s_id/a_sweek/a_sdate)
	for($i=0;$i<$time_count;$i++)
	{
	$sql_tid = "UPDATE time SET a_sweek='$sweek[$i]',a_sdate='$sdate[$i]',a_sclass='$sclass[$i]',a_room='$final_room' WHERE t_id='$t_id'";
	mysql_query($sql_tid) or die("更新出错" . mysql_error());
	}
	$n = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
	$msg = ( $n == 1 ) ? "登记记录修改成功！" : "没有修改内容或修改失败！";
	echo "<br />" . $msg;

		echo "</fieldset>";//表单第二个内边框DIV_END				
		echo "<br />";
        /*---------------------------------更新数据库END---------------------------------*/
	//echo "<br /><br />";
	//echo "<a href=\"collision_detect.php\">返回冲突检测列表</a>";
?>
	<input type="button" onclick="location.href='collision_detect.php'" class="buttonSubmit" value="返回"/>
    <br /><br />
</div><!--表单外边框DIV_END-->
</body>
</html>