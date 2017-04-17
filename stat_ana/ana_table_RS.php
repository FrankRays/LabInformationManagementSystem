<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>人时分析表</title>
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

</head>
<body>

<?php
	include("../common/db_conn.inc");
	include("../common/valid_time_range.inc");
	/*----------------------------------------------------
	引入后提供的变量如下:
	$first_week_date				自定义的第一周时间
	$date_year						自定义时间中的年
	$date_month						自定义时间中的月
	$date_month_int					数字化后的月份
	$date_day						自定义时间中的日
	$now_week						计算后得到的周次
	$valid_time_range_begin_date	有效时间区间的开始日期(传入函数用)
	$valid_time_range_end_date		有效时间区间的结束日期(传入函数用)
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/	
	
	include("../common/excel.inc");
		
	/*-----------------------获取每种实验室类型对应的房间号并整合成数组BEGIN----------------------------*/
//引入后提供的变量是$final_RT_room_arr实验室类型与实验室关联数组以及$final_RT_room_arr_total_num该数组内元素的个数
	include("../common/func_getRoomtype_NO.php");
	/*-----------------------获取每种实验室类型对应的房间号并整合成数组END------------------------------*/
	
	
	/*函数labtypeForNO($lab_type)*/
	/*功能：根据房间类型获取该类型下的具体房间*/
	/*传入：房间类型*/
	/*传出：对应的房间数组*/
	/*作者：陈灿*/
	/*--------------------------获取指定类型的实验室对应的可用房间号并整合成数组BEGIN------------------------*/
	function labtypeForNO($lab_type)
	{	
		$sql = "SELECT r_number FROM room WHERE r_state=1 AND r_name='".$lab_type."'";
		$lab_type_room_sql_result = mysql_query ( $sql );//获取某实验室类型对应的实验室SQL结果
		$lab_type_room_sql_result_row_num = mysql_num_rows($lab_type_room_sql_result);//获取影响的数据记录数目
		
		for($temp_arr_i=0 ; $temp_arr_i<$lab_type_room_sql_result_row_num; $temp_arr_i++)
		{
	    	$temp_room_arr = mysql_fetch_row ( $lab_type_room_sql_result ); 
	    	$lab_type_room_arr[] = $temp_room_arr[0];
		}
		return $lab_type_room_arr;	
	}	
	/*--------------------------获取指定类型的实验室对应的可用房间号并整合成数组END--------------------------*/		
?>


<!--选择实验室类型BEGIN-->
<select onchange="location.href=this.options[this.selectedIndex].value;">
<option selected>请选择要查看的实验室类型</option>
	<?php
	/*-----------------------列出可选的实验室类型BEGIN----------------------------*/
	for ($arr_i=0; $arr_i<$final_RT_room_arr_total_num; $arr_i++)
	{
		echo "<option value='ana_table_RS.php?lab_type=".urlencode($final_RT_room_arr[$arr_i][0])."'>".$final_RT_room_arr[$arr_i][0]."</option>";
	}
	/*-----------------------列出可选的实验室类型END----------------------------*/
	?>
</select>
</p>
<!--选择实验室类型END-->


<?php

	$lab_type = urldecode($_GET['lab_type']);  //获取要显示记录的实验室
	if(empty($lab_type))//默认实验室
	{
		/*-----------------------获取每种实验室类型对应的房间号并整合成数组BEGIN----------------------------*/
//引入后提供的变量是$final_RT_room_arr实验室类型与实验室关联数组以及$final_RT_room_arr_total_num该数组内元素的个数
		$lab_type = $final_RT_room_arr[0][0]; //将房间数组中的首元素作为默认房间
		/*-----------------------获取每种实验室类型对应的房间号并整合成数组END------------------------------*/
	} 	
	
	/*--------------------------根据实验室类型生成LIKE的SQL语句BEGIN--------------------------*/	
	//$lab_type= "嵌入式系统实验室";
	$room_array = labtypeForNO($lab_type);
	//print_r($room_array);
		
	echo"&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"method1('content');\">= 导出到Excel =</a>
<br />
<span id=\"content\">";
	
	
	echo "<div align=\"center\" style=\"font-size:14px; color:#1E7ACE; font-weight:bold;\">{$table_title}{$lab_type}利用率一览表</div>";
	
	echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";

	echo "<tr>\n";
	  echo "<th>序号</th>\n";
	  echo "<th>老师名称</th>\n";
	  echo "<th>课程名称</th>\n";
	  echo "<th>班级</th>\n";
	  echo "<th>实验课时</th>\n";
	  echo "<th>学生人数</th>\n";
	  echo "<th>人时数</th>\n";	  
	echo "</tr>\n";

	if(count($room_array)!=0)
	{
	foreach((array)$room_array AS $room)
	{
		$sql_like .= "a_room LIKE '%".$room."%' OR ";
	}
	$sql_like = substr($sql_like,0,strlen($sql_like)-4);//去掉最后四个字符，即OR和两个空格
	//echo "<br />".$sql_like;
	/*--------------------------根据实验室类型生成LIKE的SQL语句END--------------------------*/


	/*----------------------------------------输出单个表BEGIN----------------------------------------*/	
	//上表的数据库查询及显示
	//$sql = "SELECT a_cname, a_grade, a_major, a_class, sum( a_learntime ) AS 实验课时, a_people AS 人数, sum( a_learntime ) * a_people AS 人时数  FROM (SELECT * FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}') AS s WHERE $sql_like GROUP BY a_cname";
	$sql = "SELECT a_rname , a_cname, a_grade, a_major, a_class, sum( a_stime ) AS 实验课时, a_people AS 人数, sum( a_stime ) * a_people AS 人时数  FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' AND a_id IN (SELECT a_id FROM `time` WHERE $sql_like ) GROUP BY a_cname , a_rname  , a_grade";
	$result = mysql_query ( $sql )
	  or die ( "不能查询指定的数据库表：" . mysql_error() );
	  
	$row_num = mysql_num_rows($result);       //获取影响行的数目
	$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目
	
	
	$serial = 1;//前面的序号列
	
	$row = mysql_fetch_array ( $result );
	//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
	for ( $i = 0; $i < $row_num+1; $i++ )//减去管理员那一行
	{
		if($row!=null)
		{
	    echo "<tr>\n";
	  	echo "<td align=\"center\">$serial</td>\n";
	    echo "<td align=\"center\">{$row[0]}</td>\n";
		echo "<td align=\"center\">{$row[1]}</td>\n";
		echo "<td align=\"center\">{$row[2]}{$row[3]}{$row[4]}</td>\n";
		echo "<td align=\"center\">{$row[5]}</td>\n";
		echo "<td align=\"center\">{$row[6]}</td>\n";
		echo "<td align=\"center\">{$row[7]}</td>\n";
	    echo "</tr>\n";
		}
	  $row = mysql_fetch_array ( $result );
	  $serial++;
	}
	
	//总计部分的数据库查询及显示
	$sql_for_total = "SELECT sum( s.total ) 
	FROM (
	SELECT sum( a_stime ) * a_people AS total
	FROM  apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' AND a_id IN ( SELECT a_id FROM `time` WHERE $sql_like ) 
	GROUP BY a_cname , a_rname , a_grade
	) AS s";
	$result2 = mysql_query ( $sql_for_total )
	or die ( "不能查询指定的数据库表：" . mysql_error() );
	$row2 = mysql_fetch_array ( $result2 );
	
	echo "<tr>\n";
		echo "<td colspan='6'>总计</td>\n";
		echo "<td style='font-weight:800'>{$row2[0]}</td>\n";
	echo "</tr>\n";
	}
	else
	{
	  echo "<tr>\n";
	  echo "<td>-----</td>\n";
	  echo "<td>-----</td>\n";
	  echo "<td>-----</td>\n";
	  echo "<td>没有相关数据</td>\n";
	  echo "<td>-----</td>\n";
	  echo "<td>-----</td>\n";
	  echo "<td>-----</td>\n";	  
	echo "</tr>\n";
	}
	
	echo "</table>\n";
	
	/*----------------------------------------输出单个表END----------------------------------------*/	
echo "</span>";


?>

<!---------------------处理柱状图的显示BEGIN----------------------------->
<div>
	<?php 
	for ($arr_j=0 ; $arr_j<$final_RT_room_arr_total_num ; $arr_j++) //循环判断
	{
		
		$lab_type = $final_RT_room_arr[$arr_j][0]; //将房间数组中的首元素作为默认房间
		
		$RS_lab_type_arr[] = $final_RT_room_arr[$arr_j][0]; //将实验室类型放入到用于绘图的数组		
		/*--------------------------根据实验室类型生成LIKE的SQL语句BEGIN--------------------------*/	
		$room_array = labtypeForNO($lab_type);
		$sql_like = "";//注意初始化
		if(count($room_array)!=0)
		{
		foreach((array)$room_array AS $key => $room)
		{
			$sql_like .= " a_room LIKE '%".$room."%' OR ";
		}
		$sql_like = substr($sql_like,0,strlen($sql_like)-4);//去掉最后四个字符，即OR和两个空格
		}
		else
			//对没有使用的实验室定义一个另外的查询条件，使得可以执行sql语句
			$sql_like = " a_room LIKE '%8B888%'";
	    //echo $sql_like."<br>";
		/*--------------------------根据实验室类型生成LIKE的SQL语句END--------------------------*/
		
		//总计部分的数据库查询及显示
	$sql_for_total = "SELECT sum( s.total ) 
	FROM (
	SELECT sum( a_stime ) * a_people AS total
	FROM  apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' AND a_id IN ( SELECT a_id FROM `time` WHERE $sql_like ) GROUP BY a_cname , a_rname
	) AS s";
	//print_r($sql_for_total);
		$result2 = mysql_query ( $sql_for_total )
			or die ( "不能查询指定的数据库表13：" . mysql_error() );
		$row_num = mysql_num_rows($result2);       //获取影响行的数目
		$row2 = mysql_fetch_array ( $result2 );	
		if (!$row2[0])
		{
			$RS_num_arr[] = 0; //没有相关结果时
		}
			else
			{
				$RS_num_arr[] = $row2[0];
			}				
		
	}//END for
	
	$RS_lab_type_arr_num = count($RS_lab_type_arr);
	$RS_num_arr_num = count($RS_num_arr);
		
?>

<!--显示柱状图处理BEGIN-->
<p align="center">
		<?php
			
			//将房间整合成“字符串”以便URL传输
			for($i=0; $i<$RS_lab_type_arr_num ; $i++)
			{
				$RS_lab_type_arr_trans .= $RS_lab_type_arr[$i].",";
			}
			
			//将统计结果整合成“字符串”以便URL传输
			for($i=0; $i<$RS_num_arr_num ; $i++)
			{
				$RS_num_arr_trans .= $RS_num_arr[$i].",";
			}
			
		?>
		<!--使用iframe引入外部图片-->
		<iframe src="paint_histogram_for_RS.php?RS_lab_type_arr_trans=<?= urlencode($RS_lab_type_arr_trans)?>&RS_num_arr_trans=<?= $RS_num_arr_trans ?>" align="middle" marginheight="0" marginwidth="0" height="350" width="800" scrolling="no" frameborder="0" allowtransparency="true"></iframe>
		</p>
		<!--显示柱状图处理END-->
</div>
<!---------------------处理柱状图的显示END----------------------------->
</body>
</html>