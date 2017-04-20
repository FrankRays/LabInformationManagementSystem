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
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查询登记表</title>
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<!-----------------------------------自动完成功能BEGIN---------------------------------->

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script>
<script type="text/javascript">
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			if ( $("option:selected").attr("value") == "a_cname" ) //使用课程名称作为搜索条件时
			{
				$.post("jq_name_select.php", {queryString: ""+inputString+""}, function(data){
					if(data.length >0) {
						$('#suggestions').show();
						$('#autoSuggestionsList').html(data);
					}
				});			
			}
			
			if ( $("option:selected").attr("value") == "a_rname" ) //使用教师名称作为搜索条件时
			{
				$.post("jq_Tname_select.php", {queryString: ""+inputString+""}, function(data){
					if(data.length >0) {
						$('#suggestions').show();
						$('#autoSuggestionsList').html(data);
					}
				});			
			}			
			
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
</script>

<style type="text/css">
	
	h3 {
		margin: 0px;
		padding: 0px;	
	}

	.suggestionsBox {
		position: relative;
		left: 30px;
		margin: 10px 0px 0px 0px;
		width: 300px;
		background-color: #212427;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 2px solid #000;	
		color: #fff;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}
</style>

<!-----------------------------------自动完成功能END------------------------------------>




</head>
<body>


<?php
	include("../common/db_conn.inc");
	include("../common/excel.inc");//导出
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
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$usercode = $_SESSION["u_type"];		  //获取权限代码
	
	$search_condition = $_GET['turn_back_Scondition']; //获取“返回”的查询条件
	$search_content = urldecode($_GET['turn_back_Scontent']);	//获取“返回”的查询变量

	if(!isset($search_condition)) 
	{
		$search_condition = "a_rname"; //初始化搜索条件以保证搜索语句不出错
	}
	
	//if(isset($_POST['btnSubmit']))  //针对“管理员”按下“查看”按钮时所做的处理
	//2010-06-06(提交回车修改)
	if(isset($_POST['search_content']))  //针对“管理员”按下“查看”按钮时所做的处理
	{
		
		$search_condition = $_POST['search_condition'];
		$search_content = $_POST['search_content'];
	}


	if ($usercode=='1' or $usercode=='2' or$usercode=='3')		  //教师权限直接显示其名下课程
	{
			$search_condition = 'a_rname';		
			$search_content = $_SESSION["u_name"];	//获取真实名称			
	}
	
		else if ($usercode =='4' or $usercode =='5') //管理员权限要求先输入教师名称（要修改管理员的查询输出2009年8月13日14时6分4秒）
		{
			echo "请先输入查询条件";
			echo '<form action="" method="post">';
			echo '<select name="search_condition">';
			echo '	<option value="a_rname" >教师姓名</option>';
			echo '	<option value="a_cname" >课程名字</option>';
			echo '</select>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input name="search_content" type="text" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();"/>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input name="btnSubmit" type="submit" value="查看" />';
			
		    echo '<!--以下是自动提示的显示空间-->';
		    echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
		    echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px;" alt="upArrow" />';
		    echo '<div class="suggestionList" id="autoSuggestionsList">';
		    echo '&nbsp;';
			echo '</div>';
			echo '</div>';  
			
			echo '</form>';
	        			
		}

		//修改为一下的查询格式（2009年8月13日14时23分59秒）	
		/*------多加一个实验教材字段a_book---------*/
		//$sql = sprintf("SELECT a_cname AS 课程名称, a_rname AS 教师名称 , a_ctype AS 课程类别 , a_sbook AS 实验教材 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_stype AS 实验类型  , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 ,a_learntime AS 计划学时, a_stime AS 实际学时 , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求 , a_id AS 操作 FROM apply1 WHERE %s='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY a_cname, a_grade, a_major, a_class ",$search_condition,$search_content);
		//2017-04-20通过左联表查询，增加查询房间字段,移除原来查看详细信息操作
		$sql = sprintf("SELECT a_cname AS 课程名称, a_rname AS 教师名称 , a_ctype AS 课程类别 , a_sbook AS 实验教材 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_stype AS 实验类型  , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 ,a_learntime AS 计划学时, a_stime AS 实际学时 , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求 , a.a_id, a_room AS 房间 FROM apply1 AS a LEFT JOIN time AS t ON a.a_id = t.a_id WHERE %s='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY a_cname, a_grade, a_major, a_class ",$search_condition,$search_content);
		
		//ORDER BY `a_rname`, `a_cname`,`a_sid`
		//针对负责人的查询语句（2009年3月14日22时27分19秒分组修改）		
		if($usercode =='2')
		{
			//2017-04-20通过左联表查询，增加查询房间字段,删除操作
			//$sql = "SELECT a_cname AS 课程名称, a_rname AS 教师名称 , a_ctype AS 课程类别 , a_sbook AS 实验教材 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_stype AS 实验类型  , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 ,a_learntime AS 计划学时, a_stime AS 实际学时 , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求 , a_id AS 查看 FROM apply1 WHERE a_cdirection IN (SELECT r_name FROM room WHERE r_admin LIKE '%".$search_content."%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY a_cname, a_grade, a_major, a_class";
			$sql = "SELECT a_cname AS 课程名称, a_rname AS 教师名称 , a_ctype AS 课程类别 , a_sbook AS 实验教材 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_stype AS 实验类型  , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 ,a_learntime AS 计划学时, a_stime AS 实际学时 , a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求 , a.a_id, a_room AS 房间 FROM apply1 AS a LEFT JOIN time AS t ON a.a_id = t.a_id WHERE a_cdirection IN (SELECT r_name FROM room WHERE r_admin LIKE '%".$search_content."%') AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY a_cname, a_grade, a_major, a_class";
			// ORDER BY `a_rname`, `a_cname` , `a_sid`
		}

		
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);			//获取影响行的数目
		$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目

		//针对不同的权限输出不同的标题
		if($usercode =='2')//针对负责人输出的标题
		{
	/*--------------------------获取负责人负责实验室种类数组BEGIN------------------------*/
	//引入后提供的变量是$admin_roomtype_arr负责人负责实验室种类数组以及该数组内元素的个数$admin_roomtype_arr_total_num
			include("../common/func_getAdminRoom.php");
			foreach($admin_roomtype_arr as $roomtype)
			{
				$roomtype_output .= "“".$roomtype."”";
			}			
	/*--------------------------获取负责人负责实验室种类数组BEGIN------------------------*/
	        //添加excel导出2009年8月13日15时11分24秒
			echo '<a href="#" onclick="method1(\'content\');">= 导出到Excel =</a>';
			echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$_SESSION["u_name"].'老师，您负责的'.$roomtype_output.'实验室已提交的申请如下</div>';
			//echo "<caption>".$teachername."老师，您负责的实验室已提交的申请如下</caption>\n";
		}
			else//权限不是负责人
			{
				/*2009年8月13日14时58分58秒------修改begin*/
				/*修改*/
				if($search_content == "")
				{
					//添加excel导出2009年8月13日15时11分24秒
					echo '<a href="#" onclick="method1(\'content\');">= 导出到Excel =</a>';
					//echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'老师已提交的实验室申请如下</div>';
					echo '<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">查到的'. $table_title.'相关的实验课信息登记表如下</p>';
				}
				/*2009年8月13日15时9分47秒--------修改end*/
				else
				{
				if($search_condition == 'a_rname') //搜索条件为教师名时输出的标题
				{
					//添加excel导出2009年8月13日15时11分24秒
					echo '<a href="#" onclick="method1(\'content\');">= 导出到Excel =</a>';
					echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'老师已提交的实验室申请如下</div>';
				}
				
				if($search_condition == 'a_cname') //搜索条件为课程名时输出的标题
				{
					//添加excel导出2009年8月13日15时11分24秒
					echo '<a href="#" onclick="method1(\'content\');">= 导出到Excel =</a>';
					echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'课程的相关申请如下</div>';
				}
				}
				
				//echo "<caption>".$teachername."老师已提交的实验室申请如下</caption>\n";
			}
						
						
		$title = array();				

		echo "<table id='content' align=\"center\"  border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
		
		echo "<tr>\n";
		
		//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
		for ( $i = 0; $i < 7; $i++ ) //登记表前面字段
			{   
				$meta = mysql_fetch_field ( $result ); 
				$title[$i] = $meta;
				echo "<th>{$meta->name}</th>\n";
			}
		echo "<th>周次</th>\n";
		echo "<th>星期</th>\n";
		echo "<th>节次</th>\n";
		for($i=7;$i<$col_num;$i++)//登记表后面字段
		{
			$meta = mysql_fetch_field ( $result ); 
			//2017-04-20如果不是a_id列则输出
			if ($meta->name != 'a_id') {
				$title[$i] = $meta;			
				echo "<th>{$meta->name}</th>\n";
			}
			
		}
		echo "</tr>\n";
		
		$isTheSameTask = '';//2017-04-20判断是否为同一个实验
		
		$row = mysql_fetch_array ( $result );    //取得查询出来的多行结果中的一行
		
		//不同课程
		$differentCourse = $row[0].$row[1].$row[7].$row[8].$row[9];
		include("course_register_search_function.php");
		
		
		//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
		for ( $z = 0; $z < $row_num; $z++ ) {
			//2017-04-20判断是否为同一个实验
			if ($isTheSameTask != $row[4].$row[5]) {
				
				$isTheSameTask = $row[4].$row[5];
				
				$sql_tid = sprintf("SELECT a_sweek AS 周次, a_sdate AS 星期, a_sclass AS 节次 FROM time WHERE a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[16]);
				
				//printf($sql_tid);die();
				$result_tid = mysql_query($sql_tid) or die("不能查询指定的数据库表：" . mysql_error());
				$num_tid = mysql_num_rows($result_tid);
			
				$row_tid = mysql_fetch_array($result_tid);//读取多行结果中的一行
				$cont=0;//计数，输出的次数(针对计划学时的合并输出)
				
				if($differentCourse != $row[0].$row[1].$row[7].$row[8].$row[9]){
					echo "<tr style='height:40px;'></tr>";
					showHeader($title, $col_num);
					
				}
				$differentCourse = $row[0].$row[1].$row[7].$row[8].$row[9];
				
				
				for($num=0;$num<$num_tid;$num++){ 
					echo "<tr>\n"; 
					//登记表前半部分输出
					for ( $j = 0; $j < 7; $j++ ) {
						//实验编号、实验项目名称、实验类型合并
						if($j==4 or $j==5 or $j==6){
							if($cont==0)
								echo "<td align=\"center\" id=\"id$j\"  rowspan='$num_tid'>{$row[$j]}</td>\n";
						} else
							echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
					}
					//时间段输出
					for($j=8;$j<11;$j++) {
						$row_j=$j-8;
						echo "<td align=\"center\" id=\"id$j\">{$row_tid[$row_j]}</td>\n";
					}

					//登记表后半部分输出
					for ( $j = 7; $j < mysql_num_fields($result)-1; $j++ ) {
						//年级、班级、专业、人数合并
						if($j==7 or $j==8 or $j==9 or $j==10){
							if($cont==0)
								echo "<td align=\"center\" id=\"id$j\"  rowspan='$num_tid'>{$row[$j]}</td>\n";
						} else {
							//对计划学时特别处理
							if($j==11 or $j==12) {
								//当变量为0的时候则合并单元格
								if($cont==0) {
									echo "<td align=\"center\" id=\"id$j\" rowspan='$num_tid'>{$row[$j]}</td>\n";
								}

							} else if ($j != 16)
								//2017-04-20如果不是a_id列则输出
								echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
						}
							
					}
					$cont++;
			
					//单独添加"修改链接"	
					//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
					//2017-04-20注释掉  通过左联表查询，增加查询房间字段,删除操作
					/*
					if($usercode =='2')//针对负责人查看详细信息
					{
						echo "<td align=\"center\"  id='id16'>
						<img src=\"../images/search.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_search_single_result.php?a_id=$row[16]&search_condition=".$search_condition."&search_content=".urlencode($search_content)."\"' title='查看详细信息'/>
					</td>\n";
					}
					else  //其他$row[17]表示登记表的编号
					{
						echo "<td align=\"center\" id='id16'>
						<img src=\"../images/search.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_search_single_result.php?a_id=$row[16]&search_condition=".$search_condition."&search_content=".urlencode($search_content)."\"' title='查看详细信息'/>
						</td>\n";
					}
					*/
					//输出房间号
					echo "<td align='center'>${row[17]}</td>";
					//<a href=\"course_register_search_single_result.php?a_id=$row[7]\">详细信息</a>
			
					echo "</tr>\n";
			
					$row_tid = mysql_fetch_array($result_tid);//读取多行结果中的一行
				}
			}
		  $row = mysql_fetch_array ( $result );
		}
		echo "</table>\n";
		
?>



<!--单元格竖合并输出BEGIN-->
  <script   language="JavaScript">   
  //2017-04-22删除，造成新版浏览器不兼容(此段代码的作用是合并td)
   /*
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
  coalesce_row(document.all.id0,1,0,'null')   //合并单元格  
  coalesce_row(document.all.id1,1,0,'null')   
  coalesce_row(document.all.id2,1,0,'null')
  coalesce_row(document.all.id3,1,0,'null')
  //coalesce_row(document.all.id7,1,0,'null')
  //coalesce_row(document.all.id8,1,0,'null')
  //coalesce_row(document.all.id9,1,0,'null')
  coalesce_row(document.all.id16,1,0,'null')*/
  </script>
<!--单元格竖合并输出END-->




</body>
</html>