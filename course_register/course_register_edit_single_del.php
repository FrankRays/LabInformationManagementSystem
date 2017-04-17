<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>删除多个时间算的一个</title>
<LINK rel="stylesheet" href="sty.css" type="text/css" />
</head>
<body>
<?php
$a_id = $_GET["a_id"];
$search_condition = $_GET['search_condition']; //用于返回到上一个页面的“搜索条件”
$search_content = urldecode($_GET['search_content']); //用于返回到上一个页面的“搜索内容”
$t_id=$_GET["t_id"];//log.php?l_id=3,用GET传递值
$sql="delete from time where t_id=$t_id";
$result=mysql_query( $sql );

if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断插入记录是否成功
  { include("../log/log_scsys.inc");
    echo "<script language='javascript'>alert('记录已删除！');	
					 location.href='course_register_edit_single.php?a_id=".$a_id."&search_condition=".$search_condition."&search_content=".urlencode($search_content)."';</script>";
					 
  }
  else 
    {  echo "<script language='javascript'>alert('记录删除失败！');</script>";			 
       echo '<p align=center><a href=javascript:history.back()>请点击返回</a>';
	 }
mysql_close( $conn );
?>
</body>
</html>