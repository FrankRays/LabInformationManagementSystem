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

<title>用户删除结果显示</title>
</head>
<body>

<div align="center"><!--居中DIV容器BEGIN-->
<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单第一个内边框DIV_BEGIN-->
        <legend>删除的内容</legend><!--表单内边框标题-->
        <br />
<?php
	include("../common/db_conn.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<3) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$u_id = $_GET['u_id'];

	$sql = sprintf ( "SELECT * FROM user WHERE u_id='%s'",$u_id);
	$rs = mysql_query ( $sql ) or die ( "获取u_id时发生错误:" . mysql_error() );
	$row = mysql_fetch_array( $rs );
		
	/*-----------------------删除信息显示BEGIN---------------------*/
	echo "用户姓名：" .$row['u_name'] ."<br />";
	echo "登录id：" .$row['u_lname'] ."<br />";
	echo "密码：" .$row['u_password'] ."<br />";
	echo "权限：" .$row['u_type'] ."<br />";
	echo "性别：" .$row['u_gender'] ."<br />";
	echo "所在部门：" .$row['u_dept'] ."<br />";
	echo "联系方式：" .$row['u_cellphone'] ."<br />";
	echo "其它联系方式：" .$row['u_otherphone'] ."<br />";
	echo "电子邮箱：" .$row['u_email'] ."<br />";
    echo "<p>&nbsp;</p></fieldset>";
	//表单第一个内边框DIV_END
    /*-------------------获取结果显示END-------------------*/

		
	echo "<fieldset>";//表单第二个内边框DIV_BEGIN
	echo "<legend>删除结果</legend>";//表单内边框标题
	echo "<br/>";
	
	
	/*-----------------------删除数据库相关记录BEGIN-----------------------*/
	
	$m = 0;   //用于在后面记录数据库操作影响的行数
	$sql = sprintf ( "DELETE FROM user WHERE u_id='%s'", $u_id);
	mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句
	$sql_v = sprintf ( "DELETE FROM v_rname WHERE u_name='%s'", $row['u_name'] );
	mysql_query ( $sql_v ) or die ( mysql_error() );  //向数据库执行SQL语句
	$m = mysql_affected_rows ( $conn );    //通过判断上述操作影响的行数以判断插入记录是否成功
	$msg = ( $m == 1 ) ? "以上用户记录删除成功！" : "以上用户记录删除失败！";
	if($m==1) include('../log/log_scyh.inc'); //写入日志
	echo "<br />".$msg; 

	/*-----------------------删除数据库相关记录END-------------------------*/
	
	
?>
	</fieldset><!--表单第二个内边框DIV_END-->
	<br /><br />
	<p><!-- 删除完了要返回－>用户筛选(qiang) 
		<input type="button" onclick="location.href='del_user.php';" class="buttonSubmit" value="返回"/>-->
		<input type="button" onclick="location.href='edit_personal_info_type.php';" class="buttonSubmit" value="返回"/>
	</p>
	<br />
</div><!--表单外边框DIV_END-->
</div><!--居中DIV容器END-->
</body>
</html>