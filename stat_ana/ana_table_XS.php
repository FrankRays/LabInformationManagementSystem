<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>学时分析表</title>
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

</head>

<body>

<?php
	include("../common/db_conn.inc");
	include("../common/excel.inc");

	include("../common/valid_time_range.inc");
	/*----------------------------------------------------
	引入后提供的变量如下:
	$first_week_date				自定义的第一周时间
	$date_year						自定义时间中的年
	$date_month						自定义时间中的月
	$date_month_int					数字化后的月份
	$date_day						自定义时间中的日
	$now_week						计算后得到的周次
	$valid_time_range_begin_date	有效时间区间的开始日期
	$valid_time_range_end_date		有效时间区间的结束日期
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/
	
	$ana_type = "a_stime"; //默认为统计学时数

	$ana_name = "总学时数";	
	
	/*-----------------------引入房间数组BEGIN----------------------------*/
	//引入后提供的变量是$room_arr房间数组以及$room_arr_total_num房间数组内元素的个数
	include("../common/func_getRoom.php");
	/*-----------------------引入房间数组END------------------------------*/
	$room_arr_num = $room_arr_total_num; //统计房间的数目
	
	for($i=0 ; $i<$room_arr_num ; $i++)
	{
		$ana_room = $room_arr[$i];  //具体要统计哪间房
		//$sql = sprintf("SELECT a_room, SUM( $ana_type ) AS $ana_type FROM apply  WHERE a_room LIKE '%%%s%%' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' GROUP BY a_room",$ana_room);
		$sql = sprintf("SELECT a_room, a_sclass FROM time  WHERE a_room LIKE '%%%s%%' AND a_id IN (SELECT a_id FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')",$ana_room);
		$result = mysql_query ( $sql ) or die ( "SQL语句执行失败:" . mysql_error() );
		
		$result_row_num = mysql_num_rows($result);
		
		$total=0;
		
		for($j=0 ; $j<$result_row_num ; $j++)  //将一间房LIKE的结果累加
		{
			$row_arr = mysql_fetch_array ( $result );
			//根据节次数组的长度输出学时分析
			$a_sclass_str = $row_arr[a_sclass];//获取节次内容字符串	
			$a_sclass_arr = explode( "," , $a_sclass_str); //▲生成节次列表数组（如1,2生成1、2）	
			$a_sclass_count = count($a_sclass_arr);//▲得到列表数组的个数
			//$total += $row_arr[$ana_type];
			$total += $a_sclass_count;
		}
		
		$ana_result_arr[] = $total;  //将一间房的统计结果动态写入到数组中
		
		
	}
?>


&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="method1('content');">= 导出到Excel =</a>
<br />
<span id="content"><!--使标签内的内容可以导出-->
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?= $table_title ?>每个实验室一个学期的<?= $ana_name?></div>
<br />
<table width="100%" border="2" bordercolor="#000000">


	<tr>
		<?php
		//房号输出
			for($i=0; $i<$room_arr_num ; $i++)
			{
				echo "<th>";
				echo $room_arr[$i];
				echo "</th>";
			}
		?>
	</tr>
	
	<tr>
		<?php
			$ana_result_arr_num = count($ana_result_arr);
		
			for($i=0; $i<$ana_result_arr_num ; $i++)
			{
				echo "<td>";
				echo $ana_result_arr[$i];
				echo "</td>";
			}
		?>
	</tr>

</table>
 </span>
<!--显示柱状图处理BEGIN-->
<p align="center">
		<?php
			
			//将房间整合成“字符串”以便URL传输
			for($i=0; $i<$room_arr_num ; $i++)
			{
				$room_arr_trans .= $room_arr[$i].",";
			}
			rtrim($room_arr_trans,','); //去掉最后面的逗号
			
			//将统计结果整合成“字符串”以便URL传输
			for($i=0; $i<$ana_result_arr_num ; $i++)
			{
				$ana_result_arr_trans .= $ana_result_arr[$i].",";
			}
			rtrim($ana_result_arr_trans,','); //去掉最后面的逗号
			
		?>
<!--使用iframe引入外部图片-->
<iframe src="paint_histogram.php?room_arr_trans=<?= $room_arr_trans?>&ana_result_arr_trans=<?= $ana_result_arr_trans ?>" align="middle" marginheight="0" marginwidth="0" height="350" width="700" scrolling="no" frameborder="0" allowtransparency="true"></iframe>
</p>
<!--显示柱状图处理END-->


</body>
</html>




