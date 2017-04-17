<?php	
	/*----------------------------------------------------
	引入后提供的变量如下:
	$first_week_date				自定义的第一周时间
	$date_year						自定义时间中的年
	$date_month						自定义时间中的月
	$date_month_int					数字化后的月份
	$date_day						自定义时间中的日
	$now_week						计算后得到的周次
	$now_week_output				显示在top区域的周次文字
	$valid_time_range_begin_date	有效时间区间的开始日期
	$valid_time_range_end_date		有效时间区间的结束日期
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/
	
	//include("db_conn.inc");被引入时注意取消
	
	/*-------------------------------获取自定义的第一周时间BEGIN-------------------------------------------*/
	//$get_date_sql_result = mysql_query("SELECT first_week_date FROM date_week WHERE state=1");
	//$date_sql_result_row = mysql_fetch_row ( $get_date_sql_result ); 
	//$first_week_date = $date_sql_result_row[0];
	if($_GET['fwd'])
    {  $first_week_date=$_GET['fwd']; }
	else
	{
	 $get_date_sql_result = mysql_query("SELECT first_week_date FROM date_week WHERE state=0 ORDER BY first_week_date DESC LIMIT 0,1");
	 $date_sql_result_row = mysql_fetch_row ( $get_date_sql_result ); 
	 $first_week_date = $date_sql_result_row[0];
	} 
	
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
	
	
	/*-----------------处理10以下月份前面的零BEGIN---------------------*/
	$date_month_int = $date_month;
	
	if ( $date_month!='10' && $date_month!='11' && $date_month!='12')
	{
		$date_month_int = substr( $date_month , 1); //去掉月份数字前面的“0” 
	}
	//echo "数字化后的月份：".$date_month_int."<br />";	
	/*-----------------处理10以下月份前面的零END-----------------------*/
	
	
	/*-----------------处理有效期时间区间BEGIN-----------------------*/
	if ( $date_month_int > 5 ) //第二学期的情况(已过完暑假)
	{
		$valid_time_range_begin_second = $get_date_timestamp - (2*30*24*60*60) ;//将有效时间的开始放到两个月前
		
		$valid_time_range_begin_date = date( "Y-m-d" , $valid_time_range_begin_second );
		
		//echo "距自定义的时间两个月前的时间：".$valid_time_range_begin_date."<br />";
	}
		else //第一个学期的情况（已过完寒假）
		{
			$valid_time_range_begin_second = $get_date_timestamp - (1*30*24*60*60) ;//将有效时间的开始放到一个月前
			
			$valid_time_range_begin_date = date( "Y-m-d" , $valid_time_range_begin_second );
			
			//echo "距自定义的时间一个月前的时间：".$valid_time_range_begin_date."<br />";			
		}
	
	$valid_time_range_end_second = $get_date_timestamp + (20*7*24*60*60) ;//将有效时间的结束放到20周后
	
	$valid_time_range_end_date = date( "Y-m-d" , $valid_time_range_end_second );
	//echo $valid_time_range_end_date;
	//echo "距自定义的时间20周后的时间：".$valid_time_range_end_date."<br />";			
	/*-----------------处理有效期时间区间END-------------------------*/
	
	/*-----------------处理年度标题的显示BEGIN-------------------------*/
	
	if ( $date_month_int > 5 ) //第一学期的情况(六月份之后)
	{
		$title_term = "一";
		$title_begin_year = $date_year ; //开始年
		$title_end_year  = $date_year + 1 ; //结束年
	}
		else //第二学期的情况(六月份之前)
		{
			$title_begin_year = $date_year - 1 ; //开始年
			$title_end_year  = $date_year ; //结束年
			$title_term = "二";				
		}
	
	$table_title = $title_begin_year."～".$title_end_year."学年第".$title_term."学期"; //将标题组合	
	//echo "当前的年度及学期标题：".$table_title."<br />";
	/*-----------------处理年度标题的显示END---------------------------*/
?>
