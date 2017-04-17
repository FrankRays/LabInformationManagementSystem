<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<LINK rel="stylesheet" href="sty.css" type="text/css" />
</head>
<body>
<?php
$c_id=$_POST["c_id"];
$c_cname=$_POST["c_cname"];
$c_direction=$_POST["c_direction"];
$c_major=$_POST["c_major"];
$c_room=$_POST["c_room"];

$sql="update course set c_cname='$c_cname', c_direction='$c_direction', c_major='$c_major', c_room='$c_room' 
      where c_id='$c_id' ";
$result=mysql_query( $sql );

if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断插入记录是否成功
  { include("../log/log_xgkcfx.inc");
    echo "<script language='javascript'>alert('修改成功！');
				     location.href='course.php';</script>";
  }
  else 
    {  echo "<script language='javascript'>alert('修改失败！');</script>";			 
       echo '<p align=center><a href=javascript:history.back()>请点击返回</a>';
	 }
mysql_close( $conn );
?>
</body>
</html>
