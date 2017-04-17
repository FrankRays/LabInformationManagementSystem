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
<title>总体安排表</title>
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<!--script type="text/javascript" src="../js/tablecloth_forResultAll.js"></script><!--表格美化JS-->

<script language="JavaScript" src="../js/ajaxDiv.js"></script> <!--与动态DIV相关的js脚本-->

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script><!--引入JQuery.js-->
<script>
<!--
jQuery.noConflict(); //取消$符号的冲突，主要是与ajaxDIV的冲突 
	jQuery(function(){
		jQuery("td[title]").addClass("mouseoverTD");//为有title的TD加上背景
	});

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval("window."+targ+".frames[2].location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>


<style>
<!--
	/*定义冲突周次的链接颜色*/
	#collision_week a:link {
	font-size: 12px;
	line-height: 22px;
	color: #FF0000;
	}
	#collision_week a:visited {
	font-size: 12px;
	line-height: 22px;
	color: #FF0000;
	text-decoration: none;
	}
	#collision_week a:hover {
	font-size: 12px;
	line-height: 22px;
	color: #00FF00;
	text-decoration: underline;
	}
	#collision_week a:active {
	font-size: 12px;
	line-height: 22px;
	color:#FF0000;
	text-decoration: none;
	}
	/*定义冲突周次普通文字颜色*/
	#collision_week{
	color:#FF0000;
	}
	
	/*定义有内容的单元格的背景色*/
	.mouseoverTD{
		background-color: #f8fbfc;
	}

	/*定义AjaxDiv的CSS样式*/
	#Loading {
	position:absolute;
	z-index:10;
	/*left:350px;
	top:25px;*/
	border:1px #666666 solid;
	background:#eeeeee;
	width:700px;
	height:500px;
	padding: 20px;
	}
	.LoadContent {width:100%;height:100%;overflow:auto;
	}
	/*定义关闭按钮的手形样式*/
	#divCloseBTN {
	cursor:hand;
}
	

-->
</style>


</head>
<body>


<?php
	include("../common/db_conn.inc");
	include("../common/excel.inc");
	include("../common/valid_time_range_history.inc");
	set_time_limit(0);
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
	
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";
	$usercode = $_SESSION["u_type"];



/*函数courseCheck())*/
/*功能：根据星期、节次、房间号搜寻并输出课程*/
/*传入：星期、节次*/
/*传出：指定周次、星期和节次内所有房间的搜寻结果*/
/*作者：陈灿*/

	function courseCheck ($var_sdate,$var_sclass,$valid_time_range_begin_date,$valid_time_range_end_date)
	{   
		
	/*-----------------------引入房间数组BEGIN----------------------------*/
	//引入后提供的变量是$room_arr房间数组以及$room_arr_total_num房间数组内元素的个数
	include("../common/func_getRoom.php");

	/*-----------------------引入房间数组END------------------------------*/
	
		$merge_id = 2; //定义用于纵向合并的id,以下程序中的merge_id也是用于纵向合并的，保证其从3开始递增
		
		$counter = 1;  //跨列计数器
	
    	for ($room_code=0 ; $room_code<$room_arr_total_num ; $room_code++)
    	{
    		$merge_id++;
			if($var_sclass == '1')//当节次是1的时候，要求字符是1，才可以查询
			{
				$var_sclass = $var_sclass.",";
			}
			//$sql = sprintf ("SELECT a_sweek FROM `time` WHERE a_sdate=$var_sdate AND a_sclass LIKE  '%%%s%%'  AND a_room LIKE '%%%s%%' AND a_id IN (SELECT a_id FROM `apply1` WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}')",$var_sclass ,$room_arr[$room_code]);
			$sql = sprintf("SELECT a_grade , a_major , a_class , a_cname , a_rname FROM `apply1` WHERE a_id IN (SELECT a_id FROM `time` WHERE a_sdate=$var_sdate AND a_sclass LIKE '%%%s%%' AND a_room LIKE '%%%s%%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'",$var_sclass ,$room_arr[$room_code] ); 
			

			$temp_room_code = $room_code - 1; //用于单元格提示的显示  
			switch($var_sdate)
			{
				case '1': $temp_var_sdate = '一'; break;
				case '2': $temp_var_sdate = '二'; break;
				case '3': $temp_var_sdate = '三'; break;
				case '4': $temp_var_sdate = '四'; break;
				case '5': $temp_var_sdate = '五'; break;
				case '6': $temp_var_sdate = '六'; break;
				case '7': $temp_var_sdate = '日'; break;
			}
			
			 
			
			$result = mysql_query ( $sql ) or die ( "SQL语句执行失败:" . mysql_error() );
			$result_row_num = mysql_num_rows($result);
			if ($result_row_num >0) //有结果时
			{				
				/*-----------------------表项内容显示BEGIN----------------------------*/
				//引入后提供的变量是$td_content(根据“星期”＋“节次”＋“房间号”得出的表格显示内容）
				include("../common/func_getTDcontent.php");
			
				/*-----------------------表项内容显示END------------------------------*/				
			}
			else $td_content = "&nbsp;"; //处理空内容的情况; //没结果时置空		
				
			//---------------------以下为横向合并的处理--------------------------------//	


			if($td_content == "&nbsp;") 
			{
				if ( isset($flag_td) && $flag_td != "&nbsp;" )
				{
					//将标志格写入到“用于输出的td数组”（无跨列的情况）
					if($counter==1) 
					{
						$td_arr[]="<td id=id$merge_id title='星期{$temp_var_sdate},第{$var_sclass}节开始,{$room_arr[$temp_room_code]}'>$flag_td</td>\n"; 
					}
						//将标志格写入到“用于输出的td数组”（有跨列的情况）
					else 	
					{
						//$merge_id1 = $merge_id+$counter;
						$td_arr[]="<td colspan=\"$counter\" id=id$merge_id title='星期{$temp_var_sdate},第{$var_sclass}节开始,{$room_arr[$temp_room_code]}'>$flag_td</td>\n";
					}
					
					$counter=1; //计数器复位
			    	$flag_td = $td_content;	 //更新“标志格”中的内容	
				}			
					else 
					{
						$td_arr[]="<td id=id$merge_id>&nbsp;</td>\n";
					}
	
			}
			
			else
				{
					if (!isset($flag_td))
					{
						$flag_td =$td_content; 
						$merge_id--;
					}
				    	else  //非第一个非空单元格的情况
				    	{
				    		//与标志格比较，如果相同则跨列计数器加一
				    		if( $td_content == $flag_td) 
								{
								$counter++; 
								$merge_id--;
								}  
				    		
				    			else  //与标志格不同的情况
				    			{
				    				
				    				if ($flag_td != "&nbsp;") $temp_title = "title='星期{$temp_var_sdate},第{$var_sclass}节开始,{$room_arr[$temp_room_code]}'";//用于判断是否输出title属性
				    				
				    				//将标志格写入到“用于输出的td数组”（无跨列的情况）
				    				if($counter==1) 
										$td_arr[]="<td id=id$merge_id $temp_title>$flag_td</td>\n"; 
									//将标志格写入到“用于输出的td数组”（有跨列的情况）
				    				else 
									{
										$td_arr[]="<td colspan=\"$counter\" id=id$merge_id $temp_title>$flag_td</td>\n";
									}
				    					
				    				$counter=1; //计数器复位
				    				$flag_td = $td_content;	 //更新“标志格”中的内容
				    			}  	    		
				    	}
					
				}				
							
    	}//END for
		$merge_id++; //定义用于纵向合并的id
		
		//退出循环后将标志格最后放入到td数组中（无跨列的情况）
		if($counter==1) $td_arr[]="<td id=id$merge_id >$flag_td</td>\n";
			//退出循环后将标志格最后放入到td数组中（有跨列的情况）
		else 
		{
			//$merge_id1 = $merge_id-1+$counter;
			$td_arr[]="<td colspan=\"$counter\" id=id$merge_id title='星期{$temp_var_sdate},第{$var_sclass}节开始,{$room_arr[$temp_room_code]}'>$flag_td</td>\n";
		}
			
		
		$td_arr_count = count($td_arr);
		for( $i=0; $i<$td_arr_count ; $i++ ) //输出td数组中的内容
		{
			echo $td_arr[$i];					
		}	
	}//END function

?>

<form name="form1" id="form1">请选择学期：
   <select name="menu1" onchange="MM_jumpMenu('parent',this,0)">
    <?php 
   //获取历史学期记录
   $hisres=mysql_query("SELECT * FROM date_week WHERE state=0 ORDER BY first_week_date DESC");
   while($hisrow=mysql_fetch_array($hisres))
   { 
    echo "<option value=\"arrange_result_all_history.php?fwd=".$hisrow["first_week_date"]."\"";
	if($first_week_date==$hisrow["first_week_date"])
	{ echo " selected=\"selected\" "; } 
	echo ">".$hisrow["term"]."</option>";
   }
  ?>
 
   </select>
 </form>
 
    <a href="#" onclick="method1('content');">= 导出到Excel =</a>
<span id="content"><!--使标签内的内容可以导出-->

<table width="100%" cellpadding="1"  bgcolor="#000000" id="table1" border="2" bordercolor="#000000" style="margin-right:10px;">
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><?= $table_title ?>总体安排表

<?php
   echo "<br/>".$first_week_date."&nbsp;至&nbsp;".$valid_time_range_end_date."<br/>"
	/*if ($usercode=='4' or $usercode=='5')//管理员的权限代码改为4
	{
		echo <<<publish_control
		<br /><br />
		<input type="button" value="发布" onclick="location.href='../common/tab_publish.php'"/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="隐藏" onclick="location.href='../common/tab_hide.php'"/>
publish_control;
	}*/
?>

</div>
<br />
  <!--caption>总体安排表</caption-->
  <tr bgcolor="#FFFFFF">
    <td id="id1">&nbsp;</td>
    <td id="id2">&nbsp;</td>
    
    <?php
    	
	/*-----------------------引入房间数组BEGIN----------------------------*/
	//引入后提供的变量是$room_arr房间数组以及$room_arr_total_num房间数组内元素的个数
	include("../common/func_getRoom.php");
	/*-----------------------引入房间数组END------------------------------*/
	
	/*-----------------------列出房间号BEGIN----------------------------*/
	for ($arr_i=0; $arr_i<$room_arr_total_num; $arr_i++)
	{
		$th_id = $arr_i+3;
		echo "<th id=\"id$th_id\">".$room_arr[$arr_i]."</th>";
		
	}
	/*-----------------------列出房间号END----------------------------*/
    ?>
<!--
	<th id="id3">8B302</th>
    <th id="id4">8B303</th>
    <th id="id5">8B304</th>
    <th id="id6">8B305</th>
    <th id="id7">8B306</th>
    <th id="id8">8B307</th>
    <th id="id9">8A403</th>
    <th id="id10">8B403</th>
    <th id="id11">8B404</th>
    <th id="id12">8B405</th>
    <th id="id13">8B406</th>
    <th id="id14">8B407</th>
    <th id="id15">8B408</th>
    <th id="id16">8B409</th>
    <th id="id17">8B410</th>
-->
  </tr>


  <?php
  //表格主体输出  
  
  for($xinqi=1 ; $xinqi<8 ; $xinqi++)	//进入星期的循环
  {

  	for($i=1 ; $i<11 ; $i++) //进入节次的循环(添加到1－10节课2009-9-4)
  	{
  		echo "<tr bgcolor=\"#FFFFFF\">";
  		if ($i==1) //判断是否输出星期几（第一列）
		  { 
		  	echo "<th rowspan=\"10\" id=\"id1\" width=\"10px\">";//(添加到1－10节课2009-9-4)
			switch($xinqi) //判断具体是星期几
				{
					case '1': echo "星期一";break;
					case '2': echo "星期二";break;
					case '3': echo "星期三";break;
					case '4': echo "星期四";break;
					case '5': echo "星期五";break;
					case '6': echo "星期六";break;
					case '7': echo "星期日";break;
				}
			echo "</th>"; 
		  }
  		echo 	"<th id=\"id2\">$i</th>";//输出节次数字
  		
  		courseCheck($xinqi , $i,$valid_time_range_begin_date,$valid_time_range_end_date);//调用函数输出具体内容
  		
  		echo "</tr>";
  	}//END 节次for  		
  	
  }//END 星期for


  ?>

</table>
</span>


<!--用于显示AjaxDIV的层-->
<div id="Loading" style="display:none"></div>



<!--单元格竖合并输出-->
  <script   language="JavaScript">   
   
  //var   textnum   =   1;   
  function   coalesce_row(obj,s,n,text){   
  var   text;
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
  <?php
  	/*-----------------------列出房间号BEGIN----------------------------*/
	
	for ($arr_i=0; $arr_i<$room_arr_total_num+30; $arr_i++)	//+30以防止意外情况
	{
		$col_id = $arr_i+3;
		echo "coalesce_row(document.all.id".$col_id.",1,0,'null');";
		
	}
	
	/*-----------------------列出房间号END----------------------------*/
	/*
	coalesce_row(document.all.id3,1,0,'null')   
	coalesce_row(document.all.id4,1,0,'null')   
	coalesce_row(document.all.id5,1,0,'null') 
	coalesce_row(document.all.id6,1,0,'null')   
	coalesce_row(document.all.id7,1,0,'null')   
	coalesce_row(document.all.id8,1,0,'null')   
	coalesce_row(document.all.id9,1,0,'null') 
	coalesce_row(document.all.id10,1,0,'null')   
	coalesce_row(document.all.id11,1,0,'null')   
	coalesce_row(document.all.id12,1,0,'null')   
	coalesce_row(document.all.id13,1,0,'null') 
	coalesce_row(document.all.id14,1,0,'null')   
	coalesce_row(document.all.id15,1,0,'null')   
	coalesce_row(document.all.id16,1,0,'null')   
	coalesce_row(document.all.id17,1,0,'null')
	*/
  ?>
 
  </script>


</body>
</html>


<!--删除最后的黑色竖线(无内容的td标签)-->
<script>
	var   s=document.getElementsByTagName("td")   
    for(i=0;i<s.length;i++)
	{
	if(s[i].innerHTML=="")
	s[i].parentNode.removeChild(s[i]);
	}
</script>






