<?php
	//include("../common/db_conn.inc");被引入时注意取消


	//$var_sdate = 1;//星期几，被引入时注意取消
	//$var_sclass = 3;//节次，被引入时注意取消
	//$room_arr[$room_code] = '8B403';//房间号，被引入时注意取消

		
    /*---------------------根据“星期”＋“节次”＋“房间号”提取教师名字BEGIN--------------------------*/
	//此部分提供的变量是$teachers_arr老师名称数组以及$teachers_arr_num该数组内元素的个数
	//
	//$sql_1 = sprintf("SELECT DISTINCT a_rname FROM apply WHERE a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);

	$sql_1 = sprintf("SELECT DISTINCT a_rname FROM apply1 WHERE a_id in ( SELECT  a_id FROM `time` WHERE a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' ) AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);
	
	$result_1 = mysql_query ( $sql_1 ) or die ( "SQL语句执行失败:" . mysql_error() );
	$result_1_row_num = mysql_num_rows($result_1);
	for($i_1=0; $i_1<$result_1_row_num ; $i_1++)
	{
		$temp_row_arr_1 = mysql_fetch_row ( $result_1 );
		$teachers_arr[] = $temp_row_arr_1[0]; 			
	}
	
	$teachers_arr_num = count($teachers_arr);
	//print_r($teachers_arr);
	//echo "<br/>".$teachers_arr_num."<br/>";
	/*---------------------根据“星期”＋“节次”＋“房间号”提取教师名字END--------------------------*/


	
	/*-----------根据“教师名字”＋“星期”＋“节次”＋“房间号”提取教师名下对应的课程名（数组）BEGIN-------------*/
	//此部分提供的变量是$teacher_course_arr老师名称与课程的对应数组以及$teachers_course_arr_num该数组内元素的个数
	
	for($i_2=0 ; $i_2<$teachers_arr_num ; $i_2++)
	{
		$var_rname = $teachers_arr[$i_2];
		//$sql_2 = sprintf("SELECT DISTINCT a_cname FROM apply WHERE a_rname='{$var_rname}' AND a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);
		$sql_2 = sprintf("SELECT DISTINCT a_cname FROM apply1 WHERE a_rname='{$var_rname}' AND  a_id in ( SELECT  a_id FROM `time` WHERE a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' )  AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);

		$result_2 = mysql_query ( $sql_2 ) or die ( "SQL语句执行失败:" . mysql_error() );
		$result_2_row_num = mysql_num_rows($result_2);
		for($i_3=0; $i_3<$result_2_row_num ; $i_3++)
		{
			$temp_row_arr_2 = mysql_fetch_row ( $result_2 );
			$course_arr[] = $temp_row_arr_2[0];		
		}
		$teacher_course_arr[] = array($var_rname,$course_arr); //一维与二维数组的混合
		unset($course_arr); //使用完后注意删除临时数组
	}
	$teachers_course_arr_num = count($teacher_course_arr);
	//print_r($teacher_course_arr);
	//echo "<br/>".$teachers_course_arr_num."<br/>";	
	/*-----------根据“教师名字”＋“星期”＋“节次”＋“房间号”提取教师名下对应的课程名（数组）END-------------*/


	
	/*-----------根据“教师名”＋“课程名”＋“星期”＋“星期”＋“房间号”提取周次BEGIN-----------*/
	//此部分提供的变量是$teacher_course_week_arr老师名称与课程与周次的对应数组以及该数组内元素的个数
	
	for($i_4=0; $i_4<$teachers_course_arr_num ; $i_4++)
	{
		$var_rname = $teacher_course_arr[$i_4][0]; //教师名
		
		$temp_course_arr_num = count($teacher_course_arr[$i_4][1]);//该教师名下课程的数目
		for($i_5=0; $i_5<$temp_course_arr_num ; $i_5++)
		{
			$var_cname = $teacher_course_arr[$i_4][1][$i_5];//课程名
			
			//$sql_3 = sprintf("SELECT a_grade , a_major , a_class , a_cname , a_rname , a_sweek FROM `apply` WHERE a_rname='{$var_rname}' AND a_cname='{$var_cname}' AND a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);
			
			$sql_3 = sprintf("SELECT a_sweek , a_id FROM time WHERE a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' AND a_id in (SELECT a_id FROM apply1 WHERE a_rname='{$var_rname}' AND a_cname='{$var_cname}' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')  ORDER BY s_id ASC",$var_sclass ,$room_arr[$room_code]);
			
			$result_3 = mysql_query ( $sql_3 ) or die ( "SQL语句执行失败:" . mysql_error() );
			$result_3_row_num = mysql_num_rows($result_3);
			for($i_6=0; $i_6<$result_3_row_num ; $i_6++)
			{
				$temp_row_arr_3 = mysql_fetch_row ( $result_3 );
				$course_week_arr[] = $temp_row_arr_3[0]; //周次数组
			}
			$sql_apply = "SELECT a_grade,a_major,a_class FROM apply1 WHERE a_id='{$temp_row_arr_3[1]}'";

			$rs_apply = mysql_query( $sql_apply ) or die("SQL语句执行失败：" . mysql_error());
			$row_apply = mysql_fetch_row($rs_apply);
			$teacher_course_week_arr[] = array($row_apply[0],$row_apply[1],$row_apply[2],$var_cname,$var_rname,$course_week_arr); //存放结果的数组(将年级、专业、班级、课程名称、老师名称)
			unset($course_week_arr);			

		}//END for 每门课程单独处理
				
	}//END for 每位教师单独处理
	
	$teacher_course_week_arr_num = count($teacher_course_week_arr);
	//print_r($teacher_course_week_arr);
	//echo "<br/>".$teacher_course_week_arr_num."<br/>";	
	/*-----------根据“教师名”＋“课程名”＋“星期”＋“星期”＋“房间号”提取周次并输出结果END-----------*/



	/*-----------------------------获取周次数组的交集（即冲突的周数）BEGIN------------------------------------*/
	//得到的变量是周次冲突数组$cd_week_arr
	$all_week_arr = array(
	array ( 1 , 0),
	array ( 2 , 0),
	array ( 3 , 0),
	array ( 4 , 0),
	array ( 5 , 0),
	array ( 6 , 0),
	array ( 7 , 0),
	array ( 8 , 0),
	array ( 9 , 0),
	array ( 10 , 0),
	array ( 11 , 0),
	array ( 12 , 0),
	array ( 13 , 0),
	array ( 14 , 0),
	array ( 15 , 0),
	array ( 16 , 0),
	array ( 17 , 0),
	array ( 18 , 0),
	array ( 19 , 0),
	array ( 20 , 0)
	);//定义所有周次及其出现次数的数组
	$all_week_arr_num = count($all_week_arr);
	
	for($i_7=0 ; $i_7<$teacher_course_week_arr_num ; $i_7++)//整个大数组的循环
	{
		$temp_week_arr_num = count($teacher_course_week_arr[$i_7][5]);//周次总数
		
		for($i_8=0 ; $i_8<$temp_week_arr_num; $i_8++)//大数组内每门课程周次的循环
		{			
			
			for($i_9=0; $i_9<$all_week_arr_num ; $i_9++)//匹配到的话为相应周次的计数器＋1
			{
				if ($all_week_arr[$i_9][0]==$teacher_course_week_arr[$i_7][5][$i_8])
				$all_week_arr[$i_9][1]++;
			}			
		}
				
	}
	
	for($i_10=0; $i_10<$all_week_arr_num ; $i_10++)//匹配到的话为相应周次的计数器＋1
	{
		if ($all_week_arr[$i_10][1] > 1)//有多个周次的表示有冲突
		$cd_week_arr[] = $all_week_arr[$i_10][0]; //将该周次放到冲突周次数组中
	}
	
	//print_r($cd_week_arr);
	//echo "<br/>";
	/*-----------------------------获取周次数组的交集（即冲突的周数）END------------------------------------*/


	
	/*------------------------------------按规格输出结果BEGIN------------------------------------------*/
	$td_content = ""; //输出内容初始化

	for($i_7=0 ; $i_7<$teacher_course_week_arr_num ; $i_7++)//整个大数组的循环
	{
		$td_content .= $teacher_course_week_arr[$i_7][0]."-";//年级
		$td_content .= $teacher_course_week_arr[$i_7][1]."-";//专业
		$td_content .= $teacher_course_week_arr[$i_7][2]."-";//班别
		//$td_content .= $teacher_course_week_arr[$i_7][3]."<br />";//课程名称
		//$td_content .= $teacher_course_week_arr[$i_7][4]."<br />";//教师名称
		//处理发送的参数BEGIN
		//javascript:OpenDiv(500,500,'tuku.html')以打开层的方式发送参数
		$td_content .= '<a href="javascript:OpenDiv(900,500,\'../common/showResultByCnameRname.php?teacher_name=';
		$td_content .=$teacher_course_week_arr[$i_7][4];
		$td_content .='&course_name=';
		$td_content .=$teacher_course_week_arr[$i_7][3];
		$td_content .='\')">';
		$td_content .= $teacher_course_week_arr[$i_7][3]."<br />";
		$td_content .= $teacher_course_week_arr[$i_7][4];
		$td_content .= "</a>";		
		//处理发送的参数END
		
		$td_content .= "<br />周次：";		
		
		$temp_week_arr_num = count($teacher_course_week_arr[$i_7][5]);//周次总数
		
		$cd_week_arr_num = count($cd_week_arr);
		
		for($i_8=0 ; $i_8<$temp_week_arr_num; $i_8++)//大数组内每门课程周次的循环
		{			
			if(@in_array($teacher_course_week_arr[$i_7][5][$i_8],$cd_week_arr)) //查看周次是否在冲突数组内
			{
				if( $_SESSION["u_type"] == '4') //管理员权限才可以使用冲突检测的链接
				{
					$td_content .= 	'<span id="collision_week"><a href="../room_arrange/collision_detect.php">';
					$td_content .=	$teacher_course_week_arr[$i_7][5][$i_8];
					$td_content .=	'</a></span>/';				
				}
					else
					{
					$td_content .= '<span id="collision_week">';
					$td_content .=	$teacher_course_week_arr[$i_7][5][$i_8];
					$td_content .=	'</span>/';		
					}



			}
				else 
				{
					$td_content .= $teacher_course_week_arr[$i_7][5][$i_8]."/";;
				}			
		}
		$td_content .= "<br />"; //结束一个老师的信息后换行	
		//echo $td_content."--------------------";
				
	}
	/*------------------------------------按规格输出结果END------------------------------------------*/
	
	//清空用到的数组
	unset($teachers_arr);
	unset($teacher_course_arr);
	unset($teacher_course_week_arr);
	unset($all_week_arr);
	unset($cd_week_arr);
	

?>