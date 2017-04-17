<?php
	//include("db_conn.inc");被include时注意取消
	
	/*--------------------------获取房间数组BEGIN------------------------*/
	//引入后提供的变量是$room_arr房间数组以及$room_arr_total_num房间数组内元素的个数
    $fun_getRoom_sql_result = mysql_query ( "SELECT r_number FROM room WHERE r_state=1" );//获取房间SQL结果
    $fun_getRoom_sql_result_row_num = mysql_num_rows($fun_getRoom_sql_result);//获取影响行的数目（也就是记录的数目）
    
    for($arr_i=0; $arr_i<$fun_getRoom_sql_result_row_num ; $arr_i++ )
    {
    	$temp_room_arr = mysql_fetch_row ( $fun_getRoom_sql_result ); 
    	$room_arr[] = $temp_room_arr[0];
    	
    }

	$room_arr_total_num = count($room_arr);
	
	/*--------------------------获取房间数组END--------------------------*/
?>