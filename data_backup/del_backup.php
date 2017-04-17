<?php
include("../common/session.inc");
include("../common/db_conn.inc");

$dir=@$_GET["dir"];
$name=@$_GET["name"];
$delfile=$dir.$name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<LINK rel="stylesheet" href="sty.css" type="text/css" />
</head>
<body>
<table width="500" border="0" align="center">
  <tr>
    <td><p>&nbsp;</p>
    <p align="center">您确定要删除 " <?php echo $name; ?> " 吗？</p>
    <p align="center"><a href="backup_mysql.php?delfile=<?php echo $delfile; ?>">确定</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="backup_mysql.php" target="_self">取消</a></p></td>
  </tr>
</table>
</body>
</html>