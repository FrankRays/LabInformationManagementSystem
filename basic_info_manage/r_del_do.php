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
$r_id=$_POST["r_id"];
$sql="delete from room where r_id=$r_id";
$result=mysql_query( $sql );

if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断插入记录是否成功
  { include("../log/log_scsys.inc");
    echo "<script language='javascript'>alert('记录已删除！');
				     location.href='room.php';</script>";
  }
  else 
    {  echo "<script language='javascript'>alert('记录删除失败！');</script>";			 
       echo '<p align=center><a href=javascript:history.back()>请点击返回</a>';
	 }
mysql_close( $conn );
?>
</body>
</html>
