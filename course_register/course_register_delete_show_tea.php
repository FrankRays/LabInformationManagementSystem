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
<title>删除登记表显示</title>
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

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
	$username = $_SESSION["u_name"];		 //获取教师姓名

		

	//教师姓名参数结合SQL语句进行查询操作
	$sql = sprintf("SELECT a_cname AS 课程名称, a_rname AS 教师名称 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_sweek AS 周次, a_sdate AS 星期, a_sclass AS 节次 ,a_id AS 操作 FROM apply WHERE a_rname='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'",$username);
	$result = mysql_query ( $sql )
	  or die ( "不能查询指定的数据库表：" . mysql_error() );
	  
	$row_num = mysql_num_rows($result);       //获取影响行的数目
	$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目
	
	echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">您已提交的实验室申请如下</div>';

	
	echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
	//echo "<caption>".$teachername."老师已提交的实验室申请如下</caption>\n";
	echo "<tr>\n";
	
	//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
	for ( $i = 0; $i < $col_num; $i++ ) {   
	  $meta = mysql_fetch_field ( $result );  
	  echo "<th>{$meta->name}</th>\n";
	}	
	echo "</tr>\n";
	
	$row = mysql_fetch_array ( $result );    //取得查询出来的多行结果中的一行
	
	//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
	for ( $i = 0; $i < $row_num; $i++ ) {
	  echo "<tr>\n";
	  for ( $j = 0; $j < mysql_num_fields($result)-1; $j++ ) 
	    echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
		
		//单独添加"修改链接"	
		//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
		//$row[7]表示登记表的编号
		echo "<td align=\"center\">
	    <img src=\"../images/delete.gif\"  style=\"cursor:hand;\" onClick='javascript:if(confirm(\"您确实要删除该记录吗？\"))location=\"course_register_delete_result_tea.php?a_id=$row[7]\"' title='删除'/>
		</td>\n";
		
		//<a href='javascript:if(confirm(\"您确实要删除该记录吗？\"))location=\"course_register_delete_result.php?a_id=$row[7]\"'>删除</a>
		
	  echo "</tr>\n";
	  $row = mysql_fetch_array ( $result );
	}
	echo "</table>\n";		
		
?>


<!--单元格竖合并输出BEGIN-->
  <script   language="JavaScript">   
   
  //var   textnum   =   1;   
  function   coalesce_row(obj,s,n,text){   
  var   text   
  var table   =   obj;   
  
  //alert(s)   
  for   (var i=n;   i<table.length;   i++){   
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
  coalesce_row(document.all.id0,1,0,'null')     
  coalesce_row(document.all.id1,1,0,'null')   
  //coalesce_row(document.all.id2,1,0,'null')   
  </script>
<!--单元格竖合并输出END-->




</body>
</html>