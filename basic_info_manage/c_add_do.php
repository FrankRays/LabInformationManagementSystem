<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<script language="javascript" type="text/javascript" src="../js/select2css.js"></script><!--表单下拉列表美化JS-->
<link rel="stylesheet" type="text/css" href="../css/select2css.css"><!--表单下拉列表美化CSS-->


<title>无标题文档</title>
<LINK rel="stylesheet" href="sty.css" type="text/css" />
</head>
<body>
<?php
$c_cname=$_POST["c_cname"];
$c_direction=$_POST["c_direction"];
$c_major=$_POST["c_major"];
$c_room=$_POST["c_room"];

$sql="insert into course (c_cname, c_direction, c_major, c_room) 
      values ('$c_cname', '$c_direction', '$c_major', '$c_room')";
$result=mysql_query( $sql );
//添加数据到v_cname
$sql_v_cname = "INSERT INTO v_cname (c_cname) value ('$c_cname')";
$rs_v_cname = mysql_query( $sql_v_cname );

if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断插入记录是否成功
  { include("../log/log_zjkc.inc"); //写入日志
    echo "<script language='javascript'>alert('添加记录成功！');</script>";
    echo'<p>&nbsp;</p><p align=center><input name="back" id="back" type="button" value="继续添加记录" onClick="javascript:location=\'c_add.php\'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="back" id="back" type="button" value="返回列表" onClick="javascript:location=\'course.php\'" />
	</p>';
  }
  else 
    {  echo "<script language='javascript'>alert('添加记录失败！');</script>";			 
       echo '<p align=center><input name="back" id="back" type="button" value="请点击返回" onClick="history.back()" /></p>';
	 }
mysql_close( $conn );

?>
</body>
</html>
