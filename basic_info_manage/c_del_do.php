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
//要先找到v_cname表中的数据给以删除，再删除course表的数据
$c_id = $_POST["c_id"];
$c_cname = $_POST["c_cname"];
$sql_course = "DELETE FROM v_cname WHERE c_cname=$c_cname";
$result_course = mysql_query( $sql_course );
$sql = "delete from course where c_id=$c_id";
$result = mysql_query( $sql );

if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断插入记录是否成功
  { include("../log/log_sckc.inc");
   echo "<script language='javascript'>alert('记录已删除！');
				     location.href='course.php';</script>";
  }
  else 
    {  echo "<script language='javascript'>alert('记录删除失败！');</script>";			 
       echo '<p align=center><a href=javascript:history.back()>请点击返回</a>';
	 }
mysql_close( $conn );
?>
</body>
</html>
