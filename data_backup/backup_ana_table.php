<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<title>分析表</title>
</head>

<body>

<?php
	include("../common/db_conn.inc");
	include("excel.inc");

	$ana_type = $_GET['ana_type']; //获取要统计的字段
	
	if(empty($ana_type)) {$ana_type = "a_learntime";} //默认为统计学时数

	switch($ana_type)  //将要统计的类型换成译成中文
	{
		case "a_learntime": $ana_name = "总学时数"; 		break;
		case "a_people": 	$ana_name = "总共上课的人数"; break;
	}
	
	
	/*-----------------------引入房间数组BEGIN----------------------------*/
	//引入后提供的变量是$room_arr房间数组以及$room_arr_total_num房间数组内元素的个数
	include("../common/func_getRoom.php");
	/*-----------------------引入房间数组END------------------------------*/
	$room_arr_num = $room_arr_total_num; //统计房间的数目
	
	for($i=0 ; $i<$room_arr_num ; $i++)
	{
		$ana_room = $room_arr[$i];  //具体要统计哪间房

		$sql = sprintf("SELECT a_room, SUM( $ana_type ) AS $ana_type FROM apply  WHERE a_room LIKE '%%%s%%' GROUP BY a_room",$ana_room);    
		$result = mysql_query ( $sql ) or die ( "SQL语句执行失败:" . mysql_error() );
		
		$result_row_num = mysql_num_rows($result);
		
		$total=0;
		
		for($j=0 ; $j<$result_row_num ; $j++)  //将一间房LIKE的结果累加
		{
			$row_arr = mysql_fetch_array ( $result );
						
			$total += $row_arr[$ana_type];
		}
		
		$ana_result_arr[] = $total;  //将一间房的统计结果动态写入到数组中
		
		
	}
?>



<p>
<!--选择统计类型BEGIN-->
<select onchange="location.href=this.options[this.selectedIndex].value;">
<option selected>请选择统计类型</option>
<option value="backup_ana_table.php?ana_type=a_learntime">每个实验室一个学期的总学时数</option>;		
<option value="backup_ana_table.php?ana_type=a_people">每个实验室一个学期的总共上课的人数</option>;	

</select>
</p>
<!--选择统计类型END-->

<p>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="method1('content');">= 导出到Excel =</a></p>

<table width="100%" border="2" id="content" bordercolor="#000000">
<caption style="font-size:14px; color:#1E7ACE; font-weight:bold;">每个实验室一个学期的<?= $ana_name?></caption>

	<tr>
		<?php
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




