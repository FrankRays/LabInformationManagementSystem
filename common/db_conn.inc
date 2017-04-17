<?php
	$DB_HOST	= "localhost";	  //数据库主机位置
	$DB_LOGIN	= "labplan";	  //数据库的使用账号
	$DB_PASSWORD	= "labplan123";	  //数据库的使用密码
	$DB_NAME	= "lab_test";         //数据库名称

	$conn = mysql_connect($DB_HOST, $DB_LOGIN, $DB_PASSWORD) or die ("未能登陆数据库！");
	mysql_select_db($DB_NAME) or die ("连接数据库失败！");
	mysql_query ( "SET NAMES utf8" );
?>