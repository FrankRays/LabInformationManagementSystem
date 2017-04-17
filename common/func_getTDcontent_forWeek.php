<?php
	//include("../common/db_conn.inc");被引入时注意取消


	//$sweek 周次
	//$var_sdate = 1;星期几，被引入时注意取消
	//$var_sclass = 3;节次，被引入时注意取消
	//$room_arr[$room_code] = '8B403';房间号，被引入时注意取消

	$flag = 0 ; //用于判断该“TD格的内容是否冲突”
		
    /*---------------------根据“周次”＋“星期”＋“节次”＋“房间号”提取教师名字BEGIN--------------------------*/
	//此部分提供的变量是$teachers_arr老师名称数组以及$teachers_arr_num该数组内元素的个数
			
	$sql_1 = sprintf("SELECT DISTINCT a_rname,a_cname,t_id FROM apply1,time WHERE apply1.a_id=time.a_id AND t_id IN (SELECT t_id FROM `time` WHERE a_sweek=$sweek AND a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' ) AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);
	
	$result_1 = mysql_query ( $sql_1 ) or die ( "SQL语句执行失败:" . mysql_error() );
	$result_1_row_num = mysql_num_rows($result_1);
	for($i_1=0; $i_1<$result_1_row_num ; $i_1++)
	{
		$temp_row_arr_1 = mysql_fetch_row ( $result_1 );
		$teachers_arr[] = $temp_row_arr_1[0]; 			
	}
	
	$teachers_arr_num = count($teachers_arr);
	if( $teachers_arr_num > 1)//有两位以上的老师记录表示有冲突
	{
		$flag = 1 ;
	}
	//print_r($teachers_arr);
	//echo "<br/>".$teachers_arr_num."<br/>";
	/*---------------------根据“周次”＋星期”＋“节次”＋“房间号”提取教师名字END--------------------------*/


	
	/*-----------根据“教师名字”＋“周次”＋“星期”＋“节次”＋“房间号”提取教师名下对应的课程名（数组）BEGIN-------------*/
	//此部分提供的变量是$teacher_course_arr老师名称与课程的对应数组以及$teachers_course_arr_num该数组内元素的个数
	
	for($i_2=0 ; $i_2<$teachers_arr_num ; $i_2++)
	{
		$var_rname = $teachers_arr[$i_2];
		$sql_2 = sprintf("SELECT DISTINCT a_cname FROM apply1 WHERE a_rname='{$var_rname}' AND a_id IN (SELECT a_id FROM `time` WHERE  a_sweek=$sweek AND a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);

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
	/*-----------根据“教师名字”＋“周次”＋“星期”＋“节次”＋“房间号”提取教师名下对应的课程名（数组）END-------------*/

	
	/*-----------根据“教师名”＋“周次”＋“课程名”＋“星期”＋“星期”＋“房间号”提取并输出结果BEGIN-----------*/
	//此部分提供的变量是$teacher_course_week_arr老师名称与课程与周次的对应数组以及该数组内元素的个数
	
	for($i_4=0; $i_4<$teachers_course_arr_num ; $i_4++)
	{
		$var_rname = $teacher_course_arr[$i_4][0]; //教师名
		
		$temp_course_arr_num = count($teacher_course_arr[$i_4][1]);//该教师名下课程的数目
		for($i_5=0; $i_5<$temp_course_arr_num ; $i_5++)
		{
			$var_cname = $teacher_course_arr[$i_4][1][$i_5];//课程名
			//2010-6-15多加了一个房间号的输出
			$sql_3 = sprintf("SELECT a_grade , a_major , a_class , a_cname , a_room , a_rname FROM `apply1`,time WHERE apply1.a_id=time.a_id AND a_rname='{$var_rname}' AND a_cname='{$var_cname}' AND  t_id IN ( SELECT t_id FROM `time` WHERE a_sweek=$sweek AND a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'" ,$var_sclass ,$room_arr[$room_code]);
			
			$result_3 = mysql_query ( $sql_3 ) or die ( "SQL语句执行失败:" . mysql_error() );
			$result_3_row_num = mysql_num_rows($result_3);
			$temp_row_arr_3 = mysql_fetch_row ( $result_3 );
			$teacher_course_week_arr[] = array($temp_row_arr_3[0],$temp_row_arr_3[1],$temp_row_arr_3[2],$temp_row_arr_3[3],$temp_row_arr_3[4],$temp_row_arr_3[5]); //存放结果的数组
			
		}//END for 每门课程单独处理
				
	}//END for 每位教师单独处理
	
	$teacher_course_week_arr_num = count($teacher_course_week_arr);
	//print_r($teacher_course_week_arr);
	//echo "<br/>".$teacher_course_week_arr_num."<br/>";	
	/*-----------根据“教师名”＋“周次”＋“课程名”＋“星期”＋“星期”＋“房间号”提取并输出结果END-----------*/


	
	/*------------------------------------按规格输出结果BEGIN------------------------------------------*/
	$td_content = ""; //输出内容初始化

	for($i_7=0 ; $i_7<$teacher_course_week_arr_num ; $i_7++)//整个大数组的循环
	{
		if ( $flag == 1 )//有冲突时
		{
			$td_content .= $teacher_course_week_arr[$i_7][0]."-";//年级
			$td_content .= $teacher_course_week_arr[$i_7][1]."-";//专业
			$td_content .= $teacher_course_week_arr[$i_7][2]."-";//班别
			$td_content .= '<span id="collision_week">';
			$td_content .= $teacher_course_week_arr[$i_7][3]."<br />";//课程名称
			$td_content .=	'</span>';
			$td_content .= $teacher_course_week_arr[$i_7][5]."<br />";//教师名称
			$td_content .= $teacher_course_week_arr[$i_7][4]."<br />";//房间号
		}
			else
			{
				$td_content .= $teacher_course_week_arr[$i_7][0]."-";//年级
				$td_content .= $teacher_course_week_arr[$i_7][1]."-";//专业
				$td_content .= $teacher_course_week_arr[$i_7][2]."-";//班别
				$td_content .= $teacher_course_week_arr[$i_7][3]."<br />";//课程名称
				$td_content .= $teacher_course_week_arr[$i_7][5]."<br />";//教师名称
				$td_content .= $teacher_course_week_arr[$i_7][4]."<br />";//房间号
			}

		$td_content .= "<br />"; //结束一个老师的信息后换行		
				
	}
	/*------------------------------------按规格输出结果END------------------------------------------*/
	
	//清空用到的数组
	unset($teachers_arr);
	unset($teacher_course_arr);
	unset($teacher_course_week_arr);	

?>