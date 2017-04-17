<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>冲突检测</title>
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<!--script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<script type="text/javascript" src="../js/page_jumpSearch.js"></script><!--页内跳转搜索JS-->

<script type="text/javascript" src="../js/page_highLightSearch.js"></script><!--页内高亮搜索JS-->
<style type="text/css">
.highlight{background:red;font-weight:bold;color:white;}/*高度颜色定义*/
</style>

</head>
<body>

<span style="float:left; margin-right:20px">
	<form name="search" onsubmit="return findInPage(this.string.value);"> 
		<input onchange="n = 0;" size="15" name="string" value="" title="输入跳转搜索内容"/> 
		<input type="submit" value=">>跳转搜索" />
	</form> 
</span>

<form onsubmit="highlight(this.s.value);return false;">
	<input name="s" id="s" title="输入高亮搜索内容" value="" />
	<input type="submit" value=">>高亮搜索"/></p>
</form>


<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">冲突检测结果：</div>
<br/>

<?php
	
	set_time_limit(240);  //设置120秒的处理超时时间

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
	$valid_time_range_begin_date	有效时间区间的开始日期
	$valid_time_range_end_date		有效时间区间的结束日期
	$table_title					当前的年度及学期标题
	-----------------------------------------------------*/
	
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<4) 
	echo "<script language='javascript'>alert('你无权进行此操作！');location='../log/error.html';</script>";
	//echo "<script language='javascript'>alert('你无权进行此操作！');location.href='index.html';</script>";
		

	/*-----------------------引入房间数组BEGIN----------------------------*/
	//引入后提供的变量是$room_arr房间数组以及$room_arr_total_num房间数组内元素的个数
	include("../common/func_getRoom.php");
	/*-----------------------引入房间数组END------------------------------*/


	/*-----------------------冲突检测循环BEGIN------------------------------*/
	$time_result = mysql_query("SELECT a_sweek , a_date FROM time where a_id IN (SELECT a_id FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}') ORDER BY a_sweek , a_date");
	$time_result_sweek_row_num = mysql_num_rows($time_result);
	for($i=0;$i<$time_result_sweek_row_num;$i++)
		{
			$time_row = mysql_fetch_row($time_result);
			for($sweek=1;$sweek<21;$sweek++)//周次
			{
				while($time_row[0] == $sweek)
				{
				for($sdate=1;$sdate<6;$sdate++)//星期
				{
					for ($sclass_code=1 ; $sclass_code<11 ; $sclass_code++) //注意是从1开始,节次
					{
					}
				}
				}
			}
		}
		/*
	for ($sweek=1 ; $sweek<21 ; $sweek++) //将周数定为20
	{
		$temp_result_sweek = mysql_query("SELECT a_sweek FROM time where a_sweek='$sweek' AND a_id IN (SELECT a_id FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')");
		$temp_result_sweek_row_num = mysql_num_rows($temp_result_sweek);
		if($temp_result_sweek_row_num == 0)
		{
			continue; //如果没有相关周次的搜索结果，则跳过下面的子循环，直接进入下一次循环
		}
		
		for ($sdate=1 ; $sdate<8 ; $sdate++) //星期几
		{
			
			$temp_result_sdate = mysql_query("SELECT a_sdate FROM time WHERE a_sweek='$sweek' AND a_sdate='$sdate' AND a_id IN ( SELECT a_id FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')");
			$temp_result_sdate_row_num = mysql_num_rows($temp_result_sdate);
			if($temp_result_sdate_row_num == 0)
			{
				continue; //如果没有相关周次及星期的搜索结果，则跳过下面的子循环，直接进入下一次循环
			}
			
			
			for ($sclass_code=1 ; $sclass_code<11 ; $sclass_code++) //注意是从1开始,8类节次
			{
				
				if($sclass_code == '1')
				{
					$sclass_code = $sclass_code.",";
				}
				
				
				
				$temp_sclass_sql =sprintf("SELECT a_sclass FROM time WHERE a_sweek='$sweek' AND a_sdate='$sdate' AND a_sclass LIKE '%%%s%%' AND a_id IN (SELECT a_id FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')",$sclass_code);
				//print_r($temp_sclass_sql); 	die();		
			    //echo "\n";
				$temp_result_sclass = mysql_query($temp_sclass_sql);
				$temp_result_sclass_row_num = mysql_num_rows($temp_result_sclass);
				if($temp_result_sclass_row_num == 0)
				{
					if($sclass_code == '1,')
				{
					$sclass_code = '1';
				}
					continue; //如果没有相关周次及星期及节次的搜索结果，则跳过下面的子循环，直接进入下一次循环
				}				
				
				
				for ($room_code=0 ; $room_code<$room_arr_total_num ; $room_code++) 
				{
					//$sql = sprintf("SELECT a_rname AS 老师姓名 , a_cname AS 课程名称 , a_sid AS 实验编号 , a_sname AS 实验名称 , a_sweek 周次 , a_sdate AS 星期 , a_sclass AS 节次 , a_people AS 人数 , a_room AS 房间 , a_id AS 操作 FROM `apply` WHERE a_sweek=$sweek AND a_sdate=$sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'", $sclass_code,$room_arr[$room_code] );
					$sql = sprintf("SELECT a_rname AS 老师姓名 , a_cname AS 课程名称 , a_sid AS 实验编号 , a_sname AS 实验名称 , a_people AS 人数 , a_id AS 操作 FROM `apply1` WHERE a_id IN (SELECT a_id FROM time WHERE a_sweek=$sweek AND a_sdate=$sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'", $sclass_code,$room_arr[$room_code] );
					$result = mysql_query ( $sql ) or die ( "SQL语句执行失败:" . mysql_error() );
					
					$result_row_num = mysql_num_rows($result);

					if ($result_row_num >1) //有两个以上的结果，才表示有冲突
					{
						$table_content="";//初始化单个表格内容
						
						$col_num = mysql_num_fields( $result );
						
						$table_content .= "<table align=\"center\" border=\"1\" width=\"90%\" border=\"2\" bordercolor=\"#000000\">\n";
						//echo "<caption>检测结果</caption>\n";
						$table_content .=  "<tr>\n";
						for ( $i = 0; $i < $col_num; $i++ ) {
						  $meta = mysql_fetch_field ( $result );
						  $table_content .=  "<th>{$meta->name}</th>\n";
						}
						$table_content .=  "</tr>\n";
						$row = mysql_fetch_row ( $result );//读取一行数据
						for ( $i = 0; $i < $result_row_num; $i++ ) {
						  $table_content .=  "<tr>\n";
						  for ( $j = 0; $j < mysql_num_fields($result)-1; $j++ ) //-1的作用是使最后一行不显示
						    {
						    	$table_content .=  "<td align=\"center\">{$row[$j]}</td>\n";
						    }
						    $table_content .=  "<td align=\"center\">
							<a href=\"manual_arrange.php?a_id=$row[5]&a_sweek=$sweek&a_sdate=$sdate\">手动安排</a>
							</td>\n";//row[5]为申请表记录的id
														
						  $table_content .=  "</tr>\n";
						  $row = mysql_fetch_row ( $result );
						}
						$table_content .=  "</table>\n";
						$table_content .=  "<br/><br/><br/>";
						
						$table_content_arr [] = $table_content;//将每个表格内容集中放入数组中
						
					}
				}
				
				if($sclass_code == '1,')
					{
						$sclass_code = '1';
					}
					
			}
			
		}//for-sdate END	

	}//for-sweek END
	*/
	/*-----------------------冲突检测循环END--------------------------------*/

	/*-----------------------进行数组元素唯一化处理并输出结果BEGIN--------------------------------*/
	$table_content_unique = @array_unique($table_content_arr);
	
	foreach ((array)$table_content_unique as $single_table) {
	    echo $single_table;
	}
	/*-----------------------进行数组元素唯一化处理并输出结果END--------------------------------*/


?>



</body>
</html>



