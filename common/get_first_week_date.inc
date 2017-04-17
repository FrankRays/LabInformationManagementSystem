<?php	
	/*-------------------
	引入后提供的变量如下:
	$frist_week_date	自定义的第一周时间
	$date_year			自定义时间中的年
	$date_month			自定义时间中的月
	$date_day			自定义时间中的日
	$now_week			计算后得到的周次
	$now_week_output	显示在top区域的周次文字		
	--------------------*/
	
	//include("db_conn.inc");//被引入时注意取消
	
	/*-------------------------------获取自定义的第一周时间BEGIN-------------------------------------------*/
	
	$get_date_sql_result = mysql_query("SELECT term, first_week_date FROM date_week WHERE state=1");
	$date_sql_result_row = mysql_fetch_row ( $get_date_sql_result ); 
	$term = $date_sql_result_row[0];
	$first_week_date = $date_sql_result_row[1];

	//echo $first_week_date."<br />"; //取得自定义的第一周时间$frist_week_date
	/*-------------------------------获取自定义的第一周时间END---------------------------------------------*/
	
	
	/*-------------------------------获取自定义时间中的年、月、日BEGIN-------------------------------------------*/
	$date_part_arr = explode("-",$first_week_date);
	$date_year  = $date_part_arr[0];
	$date_month = $date_part_arr[1];
	$date_day   = $date_part_arr[2];
	
	//echo "年:".$date_year."<br />";
	//echo "月:".$date_month."<br />";
	//echo "日:".$date_day."<br />";
	/*-------------------------------获取自定义时间中的年、月、日END---------------------------------------------*/

	/*------------------根据自定义的时间以及服务器时间算出当前周次BEGIN------------------------*/
	$now_timestamp = time();//获取当前时间的时间戳
	//echo "服务器当前时间的时间戳：".$now_timestamp."<br />";
		
	$get_date_timestamp = mktime( 0 , 0 , 0 , $date_month , $date_day , $date_year );//获取自定义时间的时间戳
	//echo "自定义的第一周时间的时间戳:".$get_date_timestamp."<br />";
	
	$second_for_week = $now_timestamp - $get_date_timestamp ; //获取以上两个时间相差的秒数
	
	$now_week = ceil( $second_for_week/(7*24*60*60) );//计算出目前的周数（向上取整）
	//echo "目前的周次：".$now_week."<br />";
	if( $now_week > 0 ) //周次为正数时
	{
		$now_week_output = "第".$now_week."周";
	}
	else  //周次为负时
	{
		$now_week_output = "离下学期开学还有".abs($now_week)."周"; //取绝对值显示
	}		
	/*------------------根据自定义的时间以及服务器时间算出当前周次END--------------------------*/
	
?>
