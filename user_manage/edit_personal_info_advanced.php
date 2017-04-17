<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<title>修改列表显示</title>
</head>
<body>

<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">所有用户的基本信息如下</div>

<?php
	include("../common/db_conn.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<4) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


		//教师姓名参数结合SQL语句进行查询操作
		$sql = "SELECT u_name AS 用户姓名, u_lname AS 登录id , u_type AS 用户权限,u_gender AS 用户性别, u_cellphone AS 联系方式, u_email AS 电子邮箱, u_id AS 操作 FROM user";
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);       //获取影响行的数目
		$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目
		
		echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
		//echo "<caption>所有用户的基本信息如下</caption>\n";
		echo "<tr>\n";
		
		//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
		for ( $i = 0; $i < $col_num; $i++ ) {   
		  $meta = mysql_fetch_field ( $result );  
		  echo "<th>{$meta->name}</th>\n";
		}	
		echo "</tr>\n";
		
		$row = mysql_fetch_array ( $result );    //取得查询出来的多行结果中的一行
		
		//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
		for ( $i = 0; $i < $row_num; $i++ ) {
		  echo "<tr>\n";
		  for ( $j = 0; $j < mysql_num_fields($result)-1; $j++ ) 
		    echo "<td align=\"center\">{$row[$j]}</td>\n";
			
			//单独添加"修改链接"	
			//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
			//$row[6]表示用户表的编号
			echo "<td align=\"center\">
		    <img src=\"../images/edit.gif\"  style=\"cursor:hand;\" onClick='location.href=\"edit_personal_info_advanced_single.php?u_id=$row[6]\"'  title='修改该用户的信息'/>
			</td>\n";
							echo "";
			//<a href=\"edit_personal_info_advanced_single.php?u_id=$row[6]\">编辑</a>
							
		  echo "</tr>\n";
		  $row = mysql_fetch_array ( $result );
		}
		echo "</table>\n";
		
		
?>
</body>
</html>