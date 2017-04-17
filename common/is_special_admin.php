<?php
	//include('db_conn.inc');	
	$name_for_special = $_SESSION["u_name"];
	$type_for_special = $_SESSION["u_type"];
		
	$sql_result1 = mysql_query("SELECT * FROM user LIMIT 1 , 1"); //查询第二条记录的（实验室负责人）
	$sql_result2 = mysql_query("SELECT * FROM user WHERE u_name = '{$name_for_special}' AND u_type ={$type_for_special}");

	$result_arr1 = mysql_fetch_array($sql_result1);
	$result_arr2 = mysql_fetch_array($sql_result2);
	
	$flag = 0 ; //初始化标志变量,默认为不是特殊负责人
	if( $result_arr1 == $result_arr2 )
	{
		//echo "<br />两者相同";
		$flag = 1;
	}
 		else
 		{
 			//echo "<br />两者不同";
 			$flag = 0;
 		}
?>