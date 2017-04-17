<?php
/*-------------------------------获取自定义时间中的年、月、日BEGIN-------------------------------------------*/
	$date_part_arr = explode("-",$form_first_week_date);
	$date_year  = $date_part_arr[0];
	$date_month = $date_part_arr[1];
	$date_day   = $date_part_arr[2];
	
	//echo "年:".$date_year."<br />";
	//echo "月:".$date_month."<br />";
	//echo "日:".$date_day."<br />";
	/*-------------------------------获取自定义时间中的年、月、日END---------------------------------------------*/
	
	/*-----------------处理有效期时间区间BEGIN-----------------------*/
	if ( $date_month > 5 ) //第二学期的情况(已过完暑假)
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
	
	//echo "距自定义的时间20周后的时间：".$valid_time_range_end_date."<br />";			
	/*-----------------处理有效期时间区间END-------------------------*/
	
	/*-----------------处理年度标题的显示BEGIN-------------------------*/
	
	if ( $date_month > 5 ) //第一学期的情况(六月份之后)
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