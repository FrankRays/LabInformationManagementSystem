<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
 <!--
//缺少对象的bug
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


<title>添加用户结果显示</title>
</head>
<body>

<div align="center"><!--居中DIV容器BEGIN-->
<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单第一个内边框DIV_BEGIN-->
        <legend>添加的内容</legend><!--表单内边框标题-->
        <br />

	<?php
		include("./common/db_conn.inc");
		//include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
						     
		//默认状态为非审核
		$u_status = 0;
		/*------------------------变量获取BEGIN------------------------*/
		$u_name = $_POST['u_name'];					//获取用户姓名
		$u_lname = $_POST['u_lname'];				//获取用户登录id
		$u_password = $_POST['u_password'];			//获取用户密码
		$u_type = $_POST['u_type'];					//获取用户权限
		$u_gender = $_POST['u_gender'];				//获取用户性别
		//----添加字段获取(共13个)
		$u_birthday = $_POST['u_year']."年".$_POST['u_match']."月";//获取出生年月（格式为***年**月）
		$u_duty = $_POST['u_duty'];                 //获取职务
		$u_dutyname = $_POST['u_dutyname'];			//获取职称
		$u_dutytime = $_POST['u_dutytime'];			//获取职称评定时间
		$u_xueli = $_POST['u_xueli'];				//获取学历
		$u_degree = $_POST['u_degree'];				//获取学位
		$u_graduate = $_POST['u_graduate'];			//获取毕业院校
		$u_speciality = $_POST['u_speciality'];		//获取所学专业
		$u_graduatetime = $_POST['u_graduatetime'];	//获取毕业时间
		$u_worktime = $_POST['u_worktime'];			//获取参加工作时间
		$u_seminarytime = $_POST['u_seminarytime'];	//获取理工工作时间
		$u_workage = $_POST['u_workage'];			//获取教龄
		$u_appoint = $_POST['u_appoint'];			//获取任用情况
		//$u_ = $_POST['u_'];
		//----
		$u_dept = $_POST['u_dept'];					//获取用户所在部门
		$u_cellphone = $_POST['u_cellphone'];		//获取用户联系方式
		$u_otherphone = $_POST['u_otherphone'];		//获取用户其它联系方式
		$u_email = $_POST['u_email'];				//获取用户电子邮箱
		/*-----------------------变量获取END---------------------------*/
		
		/*-----------------------显示获取结果BEGIN---------------------*/
		echo "用户姓名：" .$u_name ."<br />";
		echo "登录id：" .$u_lname ."<br />";
		echo "密码：" .$u_password ."<br />";
		echo "权限：" .$u_type ."<br />";
		echo "性别：" .$u_gender ."<br />";
		/*-----------添加字段--------------*/
		echo "出生年月：" .$u_birthday."<br />";
		echo "职务：" .$u_duty."<br />";
		echo "职称：" .$u_dutyname."<br />";
		echo "职称评定时间：" .$u_dutytime."<br />";
		echo "学历：" .$u_xueli."<br />";
		echo "学位：" .$u_degree."<br />";
		echo "毕业院校：" .$u_graduate."<br />";
		echo "所学专业：" .$u_speciality."<br />";
		echo "毕业时间：" .$u_graduatetime."<br />";
		echo "参加工作时间：" .$u_worktime."<br />";
		echo "理工学院工作时间：" .$u_seminarytime."<br />";
		echo "教龄：" .$u_workage."<br />";
		echo "任用情况：" .$u_appoint."<br />";
		//echo "" .$u_."<br />";
		/*-----------添加字段--------------*/ 
		echo "所在部门：" .$u_dept ."<br />";
		echo "联系方式：" .$u_cellphone ."<br />";
		echo "其它联系方式：" .$u_otherphone ."<br />";
		echo "电子邮箱：" .$u_email ."<br />";
        echo "<p>&nbsp;</p></fieldset>";
		//表单第一个内边框DIV_END
        /*-------------------获取结果显示END-------------------*/
        
		
		
		echo "<fieldset>";//表单第二个内边框DIV_BEGIN
		echo "<legend>添加结果</legend>";//表单内边框标题
		echo "<br/>";
		
		
		/*-----------------------写入数据库BEGIN-----------------------*/
		$sql = "SELECT * FROM `user` WHERE u_name = '$u_name' OR u_lname = '$u_lname'";
		mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句
		$n = mysql_affected_rows ( $conn );
		if($n==1)//判断是否已经有相同用户名和登录ID的用户存在
		{
			echo "抱歉！数据库中已存在姓名为：<font color='red'>".$u_name."</font> 或登录id为：<font color='red'>".$u_lname."</font> 的用户，<font color='red'>请不要重复添加！</font><br />";
		}
			else//非重复添加的情况
			{
				
				$sql = sprintf ( "INSERT INTO user (u_name,u_lname,u_password,u_type,u_status,u_gender,u_birthday,u_duty,u_dutyname,u_dutytime,u_xueli,u_degree,u_graduate,u_speciality,u_graduatetime,u_worktime,u_seminarytime,u_workage,u_appoint,u_dept,u_cellphone,u_otherphone,u_email) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')", $u_name,$u_lname,$u_password,$u_type,$u_status,$u_gender,$u_birthday,$u_duty,$u_dutyname,$u_dutytime,$u_xueli,$u_degree,$u_graduate,$u_speciality,$u_graduatetime,$u_worktime,$u_seminarytime,$u_workage,$u_appoint,$u_dept,$u_cellphone,$u_otherphone,$u_email);
				  //注意通过INSERT录入数据的格式,其中的'%s'表示该位置将由后面一个字符串变量代替
				mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句
				
				//提交到v_rname表结构
				$sql_rname = "INSERT INTO v_rname (u_name) value ('$u_name')";//提交到用于查询输入自动显示老师名字
				mysql_query($sql_rname) or die("不能链接到数据库：" . mysql_error());
				$n = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
				$msg = ( $n == 1 ) ? "您的申请已经提交，请等待管理员审核！" : "申请失败，请稍后重试！";
				if($n==1) include('./log/log_yhsq.inc'); //写入日志
				echo $msg."<br />";
			}	
		/*-----------------------写入数据库END-------------------------*/
	
	?>

	</fieldset><!--表单第二个内边框DIV_END-->
	<br /><br />
	<p>
    	<input type="button" value="返回"  class="buttonSubmit" onclick="window.history.go(-1);"/>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="关闭"  class="buttonSubmit" onclick="closewin(); "/>
	</p>
	<br />
</div><!--表单外边框DIV_END-->
</div><!--居中DIV容器END-->
<script language="javascript">
function closewin()
{
	parent.window.opener = null;
	parent.window.open("", "_self");
	parent.window.close();
}
</script>
</body>
</html>