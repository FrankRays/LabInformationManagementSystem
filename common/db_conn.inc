<?php
	$DB_HOST	= "localhost";	  //���ݿ�����λ��
	$DB_LOGIN	= "labplan";	  //���ݿ��ʹ���˺�
	$DB_PASSWORD	= "labplan123";	  //���ݿ��ʹ������
	$DB_NAME	= "lab_test";         //���ݿ�����

	$conn = mysql_connect($DB_HOST, $DB_LOGIN, $DB_PASSWORD) or die ("δ�ܵ�½���ݿ⣡");
	mysql_select_db($DB_NAME) or die ("�������ݿ�ʧ�ܣ�");
	mysql_query ( "SET NAMES utf8" );
?>