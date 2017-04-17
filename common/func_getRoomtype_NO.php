<?php
	//include("db_conn.inc");被include时注意取消
	
	/*--------------------------获取实验室类型数组BEGIN------------------------*/
	//此部分提供的变量是$roomtype_arr实验室类型数组以及$roomtype_arr_total_num该数组内元素的个数
    $fun_getRoomtype_sql_result = mysql_query ( "SELECT DISTINCT r_name FROM room" );//获取实验室类型SQL结果
    $fun_getRoomtype_sql_result_row_num = mysql_num_rows($fun_getRoomtype_sql_result);//获取影响的数据记录数目
    
    for($roomtype_arr_i=0; $roomtype_arr_i<$fun_getRoomtype_sql_result_row_num ; $roomtype_arr_i++ )
    {
    	$temp_roomtype_arr = mysql_fetch_row ( $fun_getRoomtype_sql_result ); 
    	$roomtype_arr[] = $temp_roomtype_arr[0];
    }
    
    //print_r($roomtype_arr);
    
	$roomtype_arr_total_num = count($roomtype_arr);
	
	//echo "<br >".$roomtype_arr_total_num."<br />";
	/*--------------------------获取实验室类型数组BEGIN------------------------*/
	
	/*--------------------------获取每种实验室类型对应的可用房间号并整合成数组BEGIN------------------------*/
	//此部分提供的变量是$final_RT_room_arr实验室类型与实验室关联数组以及$final_RT_room_arr_total_num该数组内元素的个数
	for ($final_arr_i=0 ; $final_arr_i<$roomtype_arr_total_num ; $final_arr_i++)
	{
		$temp_roomtype = $roomtype_arr[$final_arr_i];
		$sql = "SELECT r_number FROM room WHERE r_state=1 AND r_name='".$temp_roomtype."'";
		$roomtype_room_sql_result = mysql_query ( $sql );//获取某实验室类型对应的实验室SQL结果
		$roomtype_room_sql_result_row_num = mysql_num_rows($roomtype_room_sql_result);//获取影响的数据记录数目
		
		for($temp_arr_i=0 ; $temp_arr_i<$roomtype_room_sql_result_row_num; $temp_arr_i++)
		{
	    	$temp_room_arr = mysql_fetch_row ( $roomtype_room_sql_result ); 
	    	$temp_roomtype_room_arr[] = $temp_room_arr[0];
		}
		$final_RT_room_arr[] = array($temp_roomtype,$temp_roomtype_room_arr);
		unset($temp_roomtype_room_arr);//使用完后删除房间的临时数组
	}
	
	//print_r($final_RT_room_arr);
    
	$final_RT_room_arr_total_num = count($final_RT_room_arr);
	
	//echo "<br >".$final_RT_room_arr_total_num;
	
	/*--------------------------获取每种实验室类型对应的可用房间号并整合成数组BEGIN------------------------*/	
	
?>