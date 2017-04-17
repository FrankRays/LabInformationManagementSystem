<?php
	//include("db_conn.inc");被引入时注意取消
	
/*	function tabPublish()//发布
	{ 
		$update_result = mysql_query("UPDATE user SET u_otherphone='1' WHERE u_name='管理员'"); 
		if($update_result)//通过判断上述操作是否成功
		{
			echo "alert(\"发布成功\")";
		}
	}
	
	function tabHide()
	{
		$update_result = mysql_query("UPDATE user SET u_otherphone='0' WHERE u_name='管理员'"); 
		if($update_result)//通过判断上述操作是否成功
		{
			echo "alert(\"隐藏成功\")";
		}
	}
*/	
	
	//tabPublish();
	//tabHide();
		
	$pub_result = mysql_query ( "SELECT * FROM user WHERE u_type='5'" );//5为管理员权限代码
	
	$pub_result_arr = mysql_fetch_array ( $pub_result );

	$publish_state = $pub_result_arr['u_otherphone'];//发布的状态，0——隐藏，1——发布

	//echo $publish_state;	
	
?>