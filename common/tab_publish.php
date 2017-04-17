<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	include("db_conn.inc");//被引入时注意取消 只处理admin
	$update_result = mysql_query("UPDATE user SET u_otherphone='1' WHERE u_id=1 AND u_type=5"); 
	if($update_result)//通过判断上述操作是否成功
	{
		echo "<script type=\"text/javascript\"> alert(\"发布成功\"); history.go(-1); </script>";
	}
?>