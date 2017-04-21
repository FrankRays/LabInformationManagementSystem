<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script>
<script language="javascript">
 <!--
//屏蔽table.length的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
function test_more() {//判断多个单选是否被选
	for (i=0;i<document.form_add.a_cname.length;i++) 
		{
		if (document.form_add.a_cname[i].checked) 
			{
			//alert("请选择要添加实验项目的课程！23"+document.form_add.a_cname.length);
			return true;
			}
		}
		alert("请选择要添加实验项目的课程！");
		return false;
}
function test_one(){//判断一个单选是否被选
	if (document.form_add.a_cname.checked) 
		return true;
	else
	{
		alert("请选择要添加实验项目的课程！");
		return false;
	}
}
/*
function chooseall()
{ 
 var arr=document.getElementsByName("field[]");
 var sum=arr.length;
 for (var i=0;i<sum;i++)
 {
	if(arr[i].checked==true)
	arr[i].checked =false;
	else 
	arr[i].checked =true;
 }
 var arr2=document.getElementsByName("record[]");
 var sum2=arr2.length;
 for (var i=0;i<sum2;i++)
 {
	if(arr2[i].checked==true)
	arr2[i].checked =false;
	else 
	arr2[i].checked =true;
 }
}
*/
//2017-04-12修改
function chooseall(element)
{ 
/*
	if(element.checked == true) {
		element.checked = false;
	} else {
		element.checked = true;
	}
 var arr=element.parentNode.parentNode.getElementsByTagName("input");
 var sum=arr.length;
 for (var i=0;i<sum;i++)
 {
	if(arr[i].checked==true)
	arr[i].checked =false;
	else 
	arr[i].checked =true;
 }
 */
 var arr2=element.parentNode.parentNode.parentNode.getElementsByTagName("input");
 var sum2=arr2.length;
 for (var i=0;i<sum2;i++)
 {
	if(arr2[i].checked==true)
	arr2[i].checked =false;
	else 
	arr2[i].checked =true;
 }
 
 if(element.checked == true) {
		element.checked = false;
	} else {
		element.checked = true;
	}
 
}
//-->  
</script>

<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改登记表显示</title>
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->


<!-----------------------------------自动完成功能BEGIN---------------------------------->

<script type="text/javascript">
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			if ( $("option:selected").attr("value") == "a_cname" ) //使用课程名称作为搜索条件时
			{
				$.post("jq_name_select.php", {queryString: ""+inputString+""}, function(data){
					if(data.length >0) {
						$('#suggestions').show();
						$('#autoSuggestionsList').html(data);
					}
				});			
			}
			
			if ( $("option:selected").attr("value") == "a_rname" ) //使用教师名称作为搜索条件时
			{
				$.post("jq_Tname_select.php", {queryString: ""+inputString+""}, function(data){
					if(data.length >0) {
						$('#suggestions').show();
						$('#autoSuggestionsList').html(data);
					}
				});			
			}			
			
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
</script>

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
	
	/*2017-04-02*/
	.btn{
		display: inline-block;
		padding: 6px 12px;
		margin-left: 10px;
		margin-bottom: 0;
		font-size: 14px;
		font-weight: 400;
		line-height: 1.42857143;
		text-align: center;
		white-space: nowrap;
		vertical-align: middle;
		-ms-touch-action: manipulation;
		touch-action: manipulation;
		cursor: pointer;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		background-image: none;
		border: 1px solid transparent;
		border-radius: 4px;
		text-decoration: none;
	}
	.btn-default{
		color: #333;
		background-color: #fff;
		border-color: #ccc;
	}
	.btn-default:focus,
	.btn-default.focus {
	  color: #333;
	  background-color: #e6e6e6;
	  border-color: #8c8c8c;
	}
	.btn-default:hover {
	  color: #333;
	  background-color: #e6e6e6;
	  border-color: #adadad;
	}
	
</style>

<!-----------------------------------自动完成功能END------------------------------------>



</head>
<body>
<!-- 		<p>
		备注：要增加新的实验项目，您可以在“填写登记表”里填上相同课程的名字，然后实验编号按顺序填写即可
		</p> -->

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


	$search_condition = $_GET['turn_back_Scondition']; //获取“返回”的查询条件
	$search_content = urldecode($_GET['turn_back_Scontent']);	//获取“返回”的查询变量
	
	if(!isset($search_condition)) 
	{
		$search_condition = "a_rname"; //初始化搜索条件以保证搜索语句不出错
	}

	//(2010-06-06提交回车修改)if(isset($_POST['btnSubmit']))  //针对“管理员”按下“查看”按钮时所做的处理
	if(isset($_POST['search_content']))  //针对“管理员”按下“查看”按钮时所做的处理
	{
		$search_condition = $_POST['search_condition'];
		$search_content = $_POST['search_content'];
		
		//获取该负责人“实验室相关申请总表”里的那些老师的，不在那里面的是不能改的
	   if($usercode=='2')
	   {
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
			// print_r($teacher_arr);
	
			//判断老师是否在该负责人管理的范围
			if($search_condition=='a_rname' && (!in_array($search_content, $teacher_arr)))
			{
			  echo "<script language='javascript'>alert('".$search_content." 老师没有安排在您负责的实验室!'); history.back();</script>";
			  exit();
			  
			}
	   }
		
	}


	if ($usercode=='1')		  //教师权限直接显示其名下课程
	{
			$search_condition = 'a_rname';		
			$search_content = $_SESSION["u_name"];	//获取真实名称			
	}
	
		else if ($usercode =='5' or $usercode =='4'  or $usercode=='2'  or $usercode=='3')  //管理员权限5要求先输入教师名称
		{
			echo "请先输入查询条件";
			echo '<form action="course_register_edit_show.php" method="post">';
			echo '<select name="search_condition">';
			echo '	<option value="a_rname" >教师姓名</option>';
			echo '	<option value="a_cname" >课程名字</option>';
			echo '</select>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input name="search_content" type="text" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();"/>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input name="btnSubmit" type="submit" value="查看" />';
			
		    echo '<!--以下是自动提示的显示空间-->';
		    echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
		    echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px;" alt="upArrow" />';
		    echo '<div class="suggestionList" id="autoSuggestionsList">';
		    echo '&nbsp;';
			echo '</div>';
			echo '</div>';  
			
			echo '</form>';
		}
		
	 
	 /*如果选择的查询的条件为老师的话，执行下面的语句END-------*/

/*		
		$sql = sprintf("SELECT a_cname AS 课程名称, a_rname AS 教师名称, a_sid AS 实验编号,a_sname AS 实验项目名称, a_id AS 操作1 FROM apply1  WHERE %s='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY `a_rname`,`a_cname`,`a_sid`",$search_condition,$search_content);*/
		$sql = sprintf("SELECT a_cname AS 课程名称, a_rname AS 教师名称 , a_ctype AS 课程类别 , a_sbook AS 实验教材 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_stype AS 实验类型  , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 ,a_learntime AS 计划学时, a_stime AS 实际学时 , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求 , a.a_id AS 操作, a_room AS 房间 FROM apply1 AS a LEFT JOIN time AS t ON a.a_id = t.a_id WHERE %s='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY a_cname, a_grade, a_major, a_class",$search_condition,$search_content); 
		
		//ORDER BY 'a_major','a_grade', `a_cname`,`a_sid`
		
		$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);       //获取影响行的数目
	 
		
		if($search_condition == 'a_rname') //搜索条件为教师名时输出的标题
		{
			echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'老师已提交的实验室申请如下</div>';
		}
		
		if($search_condition == 'a_cname') //搜索条件为课程名时输出的标题
		{
			echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'课程的相关申请如下</div>';
		}
		
	/********************zyc修改(批量字段管理)************************/
	include('course_register_edit_show_function.php');
	$row_a_cname = show($result, $row_num, $usercode, $search_condition, $search_content);

	/********************zyc修改(批量字段管理)************************/
	
	/*如果选择的查询的条件为老师的话，执行下面的语句BEGIN-------*/
	/*主要功能是输出老师所有本学期的上课课程-------*/
	/*---------2009-9-23（广强）-------*/
	if(count($row_a_cname) > 0) {
		echo "任课老师所有课程(点击添加该课程的实验项目):";
	}
	echo '<br>';
	for($i = 0; $i < count($row_a_cname); $i++) {
		echo "<a class='btn btn-default' href='course_register_edit_show_add.php?a_rname=".$row_a_cname[$i][0]."&a_cname=".$row_a_cname[$i][1]."&a_major=".$row_a_cname[$i][2]."&a_class=".$row_a_cname[$i][3]."'>".$row_a_cname[$i][4]."级".$row_a_cname[$i][2].$row_a_cname[$i][3]."班".$row_a_cname[$i][1]."</a>";
	}
	
?>

</body>
</html>