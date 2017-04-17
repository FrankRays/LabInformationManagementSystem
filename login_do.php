<?php
$u_lname=$_POST["u_lname"]; 

if($u_lname!="")
{ 
	include_once("./common/db_conn.inc");
	$sql="select u_name, u_password, u_type from user where u_lname='$u_lname' and u_status='1'";

	$result=mysql_query( $sql );
	$num_rows=mysql_num_rows( $result );

	if($num_rows<1)
	$js = "<script language='javascript'>alert('该用户不存在或待审核，请重新登陆！');location.href='index.html';</script>";
	else
	{ 
		$u_password=$_POST["u_password"];    
		$array=mysql_fetch_array( $result );
	    if($array["u_password"]!=$u_password) 
			$js = "<script language='javascript'>alert('密码不正确，请重新登陆');location.href='index.html';</script>";
		else
		{ 
		  session_start();
			/*	  
			$checkcode=$_POST["checkcode"];
			if($checkcode!=$_SESSION["checkcode"]) 
				$js = "<script language='javascript'>alert('随机码不正确，请重新登陆！');location.href='index.html';</script>";
			else
			{  */
		  $_SESSION["u_name"]=$array["u_name"];
		  $_SESSION["u_type"]=$array["u_type"]; 
		  
		  mysql_close( $conn );
		  $js = "<script language='javascript'>location.href='frame.php';</script>";  
	//     }
		}
	}

}
else 
$js = "<script language='javascript'>location.href='index.html';</script>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
 <!--
//未知的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录处理</title>
</head>
<body>
<?php echo $js; ?>
</body>
</html>
