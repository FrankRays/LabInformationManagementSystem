<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
引入时注意去掉上面的编码句-->

<?php
	//include("db_conn.inc");被include时注意取消

	$admin_name = $_SESSION["u_name"];	//获取负责人名称
	//$admin_name = "魏小锐";
	
	
	/*--------------------------获取负责人负责实验室种类数组BEGIN------------------------*/
	//引入后提供的变量是$admin_roomtype_arr负责人负责实验室种类数组以及该数组内元素的个数$admin_roomtype_arr_total_num
	
										//使用LIKE，因为有两人共同负责一个实验室的情况
	$sql_1 =sprintf("SELECT DISTINCT r_name FROM room WHERE r_admin LIKE '%%%s%%'", $admin_name);	
    $get_room_type_result = mysql_query ( $sql_1 );//获取实验室类别SQL结果
    $get_room_type_result_row_num = mysql_num_rows($get_room_type_result);//获取影响行的数目（也就是记录的数目）
    
    for($arr_i=0; $arr_i<$get_room_type_result_row_num ; $arr_i++ )
    {
    	$temp_room_type_arr = mysql_fetch_row ( $get_room_type_result ); 
    	$admin_roomtype_arr[] = $temp_room_type_arr[0];
    	
    }

	$admin_roomtype_arr_total_num = count($admin_roomtype_arr);
	
	//print_r($admin_roomtype_arr);
	//echo "<br />".$admin_roomtype_arr_total_num."<br />";
	
	/*--------------------------获取负责人负责实验室种类数组END----------------------------*/
	
	
	/*--------------------------获取负责人负责的所有房间BEGIN------------------------*/
	//引入后提供的变量是$admin_allroom_arr负责人负责的所有房间数组以及该数组内元素的个数$admin_allroom_arr_total_num
	
										//使用LIKE，因为有两人共同负责一间房的情况
	$sql_2 =sprintf("SELECT r_number FROM room WHERE r_admin LIKE '%%%s%%'", $admin_name);	
    $get_room_all_result = mysql_query ( $sql_2 );//获取具体房间SQL结果
    $get_room_all_result_row_num = mysql_num_rows($get_room_all_result);//获取影响行的数目（也就是记录的数目）
    
    for($arr_j=0; $arr_j<$get_room_all_result_row_num ; $arr_j++ )
    {
    	$temp_room_all_arr = mysql_fetch_row ( $get_room_all_result ); 
    	$admin_allroom_arr[] = $temp_room_all_arr[0];    	
    }

	$admin_allroom_arr_total_num = count($admin_allroom_arr);
	
	//print_r($admin_allroom_arr);
	//echo "<br />".$admin_allroom_arr_total_num."<br />";
	
	/*--------------------------获取负责人负责的所有房间END--------------------------*/
	
	
	/*--------------------------获取负责人负责的可用房间BEGIN------------------------*/
	//引入后提供的变量是$admin_avaroom_arr负责人负责的所有房间数组以及该数组内元素的个数$admin_avaroom_arr_total_num
	
										//使用LIKE，因为有两人共同负责一间房的情况
	$sql_3 =sprintf("SELECT r_number FROM room WHERE r_admin LIKE '%%%s%%' AND r_state=1 " , $admin_name);	
    $get_room_ava_result = mysql_query ( $sql_3 );//获取具体房间SQL结果
    $get_room_ava_result_row_num = mysql_num_rows($get_room_ava_result);//获取影响行的数目（也就是记录的数目）
    
    for($arr_k=0; $arr_k<$get_room_ava_result_row_num ; $arr_k++ )
    {
    	$temp_room_ava_arr = mysql_fetch_row ( $get_room_ava_result ); 
    	$admin_avaroom_arr[] = $temp_room_ava_arr[0];    	
    }

	$admin_avaroom_arr_total_num = count($admin_avaroom_arr);
	
	//print_r($admin_avaroom_arr);
	//echo "<br />".$admin_avaroom_arr_total_num."<br />";
		
	/*--------------------------获取负责人负责的可用房间END--------------------------*/	
	
?>