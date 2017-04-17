<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->

<title>备份全局数据库</title>
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
-->
</style>
</head>
<body>

<div style="margin-top:10px;">

<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">备份全局数据库</p>
<table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><p>&nbsp;&nbsp;&nbsp;&nbsp;默认主机名 ：localhost，&nbsp;&nbsp;&nbsp;&nbsp;默认字符集： utf8，&nbsp;&nbsp;&nbsp;&nbsp;默认用户名 ：labplan        &nbsp;，&nbsp;&nbsp;&nbsp;&nbsp;默认输出文件 ：lab_test_日期.sql 。</p>
      <p> &nbsp;&nbsp;&nbsp;&nbsp;<strong>注意：</strong>此备份功能只备份了lab_test数据库下的所有的数据（不包括结构）。</p>
      <p align="center"><a href="export_db.php"><strong>开始备份</strong></a>
	  <?php 
	  $dirname="./data/";
	  $dir_handle=opendir( $dirname );
	   ?> 
      <p align="center">下载已备份的数据库：（请用右键点击，目标另存为）</p> 
	  <p align="center"><?php
	    while( $file=readdir( $dir_handle ))
		{ 
		  if($file=="." || $file==".." ) { }
		  else 
			{  echo "→　";
			   echo '<a href='.$dirname.$file.'>'.$file.'</a>';
		       echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=del_backup.php?dir='.$dirname.'&&name='.$file.'>删除</a><br />';
			}
		}
		closedir( $dir_handle );
		
		$delfile=@$_GET["delfile"];
		if( $delfile!="" )
		{
		  unlink( $delfile );
		  if( !file_exists( $delfile ) )
		  echo "<script language='javascript'>location.href='backup_mysql.php';</script>";
		}
		/*$delname=@$_GET["name"];
		if($delname!="")
		{
		 echo "<script language='javascript'>
		       var bin=window.confirm('你确定要删除吗?');
			   if( bin==true ) 
			     location.href='del_backup.php';  
			   else { }
			   </script>"; 
		}*/
	  ?></p>
	   <p>&nbsp;</p></td>
  </tr>
</table>

</div>
</body>
</html>
