<?php
	include("session.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录注销并关闭窗口</title>
</head>

<body>
<?php 
	session_unset();
	session_destroy();
?>

<script language='javascript'>
	parent.window.opener = null; //针对IE6
	parent.window.open("","_self"); //针对IE7
	parent.window.close();
</script>

</body>
</html>