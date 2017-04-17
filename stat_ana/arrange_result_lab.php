<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
 <!--
//屏蔽table.length的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>按实验室安排表</title>
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<!--script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script><!--引入JQuery.js-->
<script>
	jQuery.noConflict(); //取消$符号的冲突，主要是与ajaxDIV的冲突 
	jQuery(function(){
		jQuery("td span").parent().addClass("mouseoverTD");//为有span标签的TD加上背景色
	});	
</script>

<style>
<!--
	/*定义有内容的单元格的背景色*/
	.mouseoverTD{
		background-color: #f8fbfc;
	}
-->
</style>



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

	$lab_type = urldecode($_GET['lab_type']);  //获取要显示记录的实验室
	
	if(empty($lab_type))//默认实验室
	{
		/*-----------------------获取每种实验室类型对应的房间号并整合成数组BEGIN----------------------------*/
//引入后提供的变量是$final_RT_room_arr实验室类型与实验室关联数组以及$final_RT_room_arr_total_num该数组内元素的个数
		$lab_type = $final_RT_room_arr[0][0]; //将房间数组中的首元素作为默认房间
		/*-----------------------获取每种实验室类型对应的房间号并整合成数组END------------------------------*/
		//$lab_type = "weiji";
	} 
	
	for ($arr_j=0 ; $arr_j<$final_RT_room_arr_total_num ; $arr_j++) //循环判断
	{
		if($lab_type == $final_RT_room_arr[$arr_j][0]) //如果实验室类型一致	
		{
			$room_arr = $final_RT_room_arr[$arr_j][1]; //把实验室类型下的房间数组放到用于显示的数组中
		}
			
	}
		
/*
	switch ($lab_type)
	{		
		case 'weiji':  		$room_arr = array('8B302' , '8B303'); break;
		
		case 'zhucheng':  	$room_arr = array('8B304' , '8B305'); break;
		
		case 'quanru':  	$room_arr = array('8B306' , '8B307'); break;
		
		case 'shuju':		$room_arr = array('8A403'); break;
		
		case 'ruanjian': 	$room_arr = array('8B403' , '8B404' , '8B405' ); break;
		
		case 'qiye':  		$room_arr = array('8B406'); break;
		
		case 'wangluo':  	$room_arr = array('8B407' , '8B408' , '8B409', '8B410'); break;
	}
*/		

	$room_total = count($room_arr);



/*函数courseCheck())*/
/*功能：根据星期、节次、房间号搜寻并输出课程*/
/*传入：星期、节次、房间号*/
/*传出：指定的星期、节次所在房间内的记录*/
/*作者：陈灿*/

	function courseCheck ($var_sdate,$var_sclass,$room_code,$valid_time_range_begin_date,$valid_time_range_end_date)
	{  
		if($var_sclass == '1')
		{
			$var_sclass = $var_sclass.',';	
		}
    		$sql = sprintf("SELECT a_grade , a_major , a_class , a_cname , a_rname FROM `apply1` WHERE  a_id IN ( SELECT a_id FROM `time` WHERE a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%' ) AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'",$var_sclass ,$room_code );    
			
			$result = mysql_query ( $sql ) or die ( "SQL语句执行失败:" . mysql_error() );
					
			$result_row_num = mysql_num_rows($result);				
					
			if ($result_row_num >0) //有结果时输出信息
			{
				/*-----------------------表项内容显示BEGIN----------------------------*/
				//引入后提供的变量是$td_content(根据“星期”＋“节次”＋“房间号”得出的表格显示内容）
				include("../common/func_getTDcontent_forSingleRoom.php");
			
				/*-----------------------表项内容显示END------------------------------*/
				echo "<span>".$td_content."</span>";//无冲突时显示为空
			}
				else echo "&nbsp;"; //没结果时置空					

		
	}//END function courseCheck


/*函数roomTableOutput())*/
/*功能：根据房间号搜寻并输出按房间号安排表格*/
/*传入：房间号*/
/*传出：输出按房间号安排表格*/
/*作者：陈灿*/

	function roomTableOutput($room_code)
	{
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
		echo <<<room_table_part1
		<br /><br /><br />
		<table width="100%" cellpadding="1"  bordercolor="#000000" bgcolor="#000000" border="2" >
		 <caption><b>{$table_title}实验室{$room_code}安排表</b></caption>
		  <tr bgcolor="#FFFFFF">
		    <td id="id1">{$room_code}</td>
		    <th id="id2">星期一</th>
		    <th id="id3">星期二</th>
		    <th id="id4">星期三</th>
		    <th id="id5">星期四</th>
		    <th id="id6">星期五</th>
            <th id="id7">星期六</th>
			<th id="id8">星期日</th>
		  </tr>
room_table_part1;

		  //表格主体输出  
		  for($hang=2 ; $hang<12 ; $hang++)	//进入行的循环
		  {
		  	
		  	echo "<tr bgcolor=\"#FFFFFF\">";
			
		  	for($lie=1 ; $lie<9 ; $lie++) //进入列的循环
		  	{  		
		  		if ($lie==1) //判断是否输出第几节（第一列）
				  { 
				  	echo "<th  id=\"id1\" width=\"70\">";
					switch($hang) //判断具体是星期几
						{
							case '2': echo "第一节";break;
							case '3': echo "第二节";break;
							case '4': echo "第三节";break;
							case '5': echo "第四节";break;
							case '6': echo "第五节";break;
							case '7': echo "第六节";break;
							case '8': echo "第七节";break;
							case '9': echo "第八节";break;
							case '10': echo "第九节";break;
							case '11': echo "第十节";break;
						}
					echo "</th>"; 
				  }
					else
					{
						echo "<td id=\"id$lie\">";
						$xinqi = $lie-1;
						$jieci = $hang-1;							
				  		courseCheck($xinqi , $jieci , $room_code,$valid_time_range_begin_date,$valid_time_range_end_date);//调用函数输出具体内容        
				  		echo "</td>";
					}
		  		
		  	}//END 节次for   	
				
		  	echo "</tr>";
		  }//END 节for


		 echo <<<room_table_part2
		
		</table>
		
room_table_part2;
		
	}



?>




<!--选择房间号BEGIN-->



按实验室安排表：
<select onchange="location.href=this.options[this.selectedIndex].value;">
<option selected>请选择要查看的实验室类型</option>

	<?php
	/*-----------------------列出可选的实验室类型BEGIN----------------------------*/
	for ($arr_i=0; $arr_i<$final_RT_room_arr_total_num; $arr_i++)
	{
		echo "<option value='arrange_result_lab.php?lab_type=".urlencode($final_RT_room_arr[$arr_i][0])."'>".$final_RT_room_arr[$arr_i][0]."</option>";
	}
	/*-----------------------列出可选的实验室类型END----------------------------*/
	?>
	
<!--
<option value="arrange_result_lab.php?lab_type=weiji">微机原理与接口实验室</option>
<option value="arrange_result_lab.php?lab_type=zhucheng">计算机组成原理实验室</option>
<option value="arrange_result_lab.php?lab_type=quanru">嵌入式系统实验室</option>
<option value="arrange_result_lab.php?lab_type=shuju">数据交换中心</option>
<option value="arrange_result_lab.php?lab_type=ruanjian">软件工程实训中心</option>
<option value="arrange_result_lab.php?lab_type=qiye">企业信息化实训中心</option>
<option value="arrange_result_lab.php?lab_type=wangluo">网络实验室</option>
-->

</select>
</p>
<!--选择房间号END-->


<?php 


	for ($arr_k=0 ; $arr_k<$final_RT_room_arr_total_num ; $arr_k++) //循环判断并输出每个表格的标题
	{
		if($lab_type == $final_RT_room_arr[$arr_k][0]) //如果实验室类型一致	
		{
			$temp_room_total_num = count($final_RT_room_arr[$arr_k][1]);
			for($arr_l=0 ; $arr_l<$temp_room_total_num; $arr_l++ )
			{
				$room_output .= $final_RT_room_arr[$arr_k][1][$arr_l]."、";
			}
			$room_output = substr($room_output,0,strlen($room_output)-3);//去掉最后一个字符，即最后一个“、”
			
			echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>";
			echo $final_RT_room_arr[$arr_k][0];
			echo "：";
			echo $room_output;
			echo "</div>";
			//$room_arr = $final_RT_room_arr[$arr_k][1]; //把实验室类型下的房间数组放到用于显示的数组中
		}
			
	}

/*
	switch ($lab_type)
	{
		case 'weiji':  		echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>微机原理与接口实验室：8B302、8B303实验课程安排表</div>"; break;
		
		case 'zhucheng':  	echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>计算机组成原理实验室：8B304、8B305实验课程安排表</div>"; break;
		
		case 'quanru':  	echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>嵌入式系统实验室：8B306、8B307实验课程安排表</div>"; break;
		
		case 'shuju':		echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>数据交换中心：8A403实验课程安排表</div>"; break;
		
		case 'ruanjian': 	echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>软件工程实训中心：8B403、8B404、8B405实验课程安排表</div>"; break;
		
		case 'qiye':  		echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>企业信息化实训中心：8B406实验课程安排表</div>"; break;
		
		case 'wangluo':  	echo "<div align='center' style='font-size:14px; color:#1E7ACE; font-weight:bold;'>网络实验室：8B407、8B408、8B409、8B410实验课程安排表</div>"; break;
		
	}
*/
echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="method1(\'content\');">= 导出到Excel =</a>';
echo '<span id="content"><!--使标签内的内容可以导出-->';
	//显示实验室对应房间的安排表
	for($i=0 ; $i<$room_total ; $i++)
	{
		roomTableOutput($room_arr[$i]);
		
	}
echo '</span>';
?>


<!--单元格竖合并输出BEGIN-->
  <script   language="JavaScript">   
   
  //var   textnum   =   1;   
  function   coalesce_row(obj,s,n,text){   
  var   text   
  table   =   obj;   
  //alert(s)   
  for   (i=n;   i<table.length;   i++){   
  if   (table(i).innerHTML   ==   text && text!='&nbsp;'){   
  s   =   s   +   1   
  table(i-1).rowSpan   =   s   
  table(i).removeNode(true);   
  coalesce_row(obj,s,i,table(i-1).innerHTML)   
  return   this;   
  }else{   
  s   =   1   
  }   
  text   =   table(i).innerHTML   
  }   
  }   
  //coalesce_row(document.all.id1,1,0,'null')   
  coalesce_row(document.all.id2,1,0,'null')   
  coalesce_row(document.all.id3,1,0,'null')   
  coalesce_row(document.all.id4,1,0,'null')   
  coalesce_row(document.all.id5,1,0,'null') 
  coalesce_row(document.all.id6,1,0,'null')
  coalesce_row(document.all.id7,1,0,'null')
  coalesce_row(document.all.id8,1,0,'null')
  </script>
<!--单元格竖合并输出END-->



</body>
</html>


