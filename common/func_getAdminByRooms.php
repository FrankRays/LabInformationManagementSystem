<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
引入时注意去掉上面的编码句-->

<?php
	//include("db_conn.inc");被include时注意取消

	//$rooms = "8B306,8B407,8B408,8B409,8B410";

	/*----------------------函数***********通过单个或多个房间名称获取单个或多个负责人名称并输出BEGIN----------------------------*/

	function getAdminsByRooms($rooms)
	{
		$rooms_arr = explode(",", $rooms); //生成房间数组
		
		//利用房间数组中的每个元素查询出相应的负责人,并将结果添加到负责人数组中
		foreach ($rooms_arr as $roomname)
		{
			$admin_rs =mysql_query("SELECT r_admin FROM room WHERE r_number = '$roomname' ") or die ( "SQL语句执行失败:" . mysql_error() );	
			
			$admin_rs_row = mysql_fetch_array( $admin_rs );
			
			$admin_arr[] = $admin_rs_row[r_admin] ;//获取权限内容字符串
		}
		
		$admin_arr_unique = @array_unique($admin_arr);//将负责人数组唯一化
		
		$admin_str = implode(",", $admin_arr_unique);//将负责人数组转换为以逗号分隔的字符串
		
		echo $admin_str;//输出负责人数组
		//return $admin_str;
	}
	
	/*----------------------函数***********通过单个或多个房间名称获取单个或多个负责人名称并输出END----------------------------*/
	
	//getAdminsByRooms($rooms);

?>