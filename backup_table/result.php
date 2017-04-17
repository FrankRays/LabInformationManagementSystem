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
<title>多组合输出表</title>
 <link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS--> 
<!-- script language="javascript" type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->
<!--<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<!-- <script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS--> 
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->

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
body{
background:#CFDFEF;

}
fieldset {
	width:780px;
    padding:0px;
    margin-top:0px;
	text-align:left;
    border:1px solid #1E7ACE;
    background:#FFFFFF;
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
	?>
	<a href="#" onclick="method1('content');">= 导出到Excel =</a>
	
	<?php 
	echo "<div  align='center'>";
	echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$table_title.'实验课程多组合报表输出</div>';
	echo '<fieldset><!--表单第一个内边框DIV_BEGIN-->';
	?>
	
	(提示请选择要输出的字段)<br />
	<form action="" method="post" style="margin:10px">
	<input name="a[]" type="checkbox" value="a_rname AS 老师名称"/>老师名称
	<input name="a[]" type="checkbox" value="a_cname AS 课程名称"/>课程名称
	<input name="a[]" type="checkbox" value="a_ctype AS 课程类别"/>课程类别
	<input name="a[]" type="checkbox" value="a_sbook AS 实验教材"/>实验教材
	<input name="a[]" type="checkbox" value="a_sid AS 实验编号"/>实验编号
	<input name="a[]" type="checkbox" value="a_sname AS 实验项目"/>实验项目
	<input name="a[]" type="checkbox" value="a_stype AS 实验类型"/>实验类型
	<input name="a[]" type="checkbox" value="a_sweek AS 周次"/>周&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;次
	<input name="a[]" type="checkbox" value="a_sdate AS 星期"/>星&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期
	<input name="a[]" type="checkbox" value="a_sclass AS 节次"/>节&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;次
	<br />
	<input name="a[]" type="checkbox" value="a_grade AS 年级"/>年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;级
	<input name="a[]" type="checkbox" value="a_major AS 专业"/>专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;业
	<input name="a[]" type="checkbox" value="a_class AS 班级"/>班&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;级
	<input name="a[]" type="checkbox" value="a_people AS 人数"/>人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;数
	<input name="a[]" type="checkbox" value="a_learntime AS 计划学时"/>计划学时
	<input name="a[]" type="checkbox" value="a_stime AS 实际学时"/>实际学时
	<input name="a[]" type="checkbox" value="a_resources AS 耗材需求"/>耗材需求
	<input name="a[]" type="checkbox" value="a_system AS 系统需求"/>系统需求
	<input name="a[]" type="checkbox" value="a_software AS 软件需求"/>软件需求
	<input name="a[]" type="checkbox" value="a_room AS 实验室安排"/>实验室安排
	

	
	
	<?php
	echo "<br />\n";
	echo '<input name="btnSubmit" type="submit" value="提交" /></form>';
	echo "</fieldset>";
	echo "</div>";
	if(isset($_POST['btnSubmit']))  //针对“管理员”按下“提交”按钮时所做的处理
	{
		if($_POST['a'] != '')//提交不为空执行
		{
		$count = 0;//统计提交的个数
		foreach($_POST['a'] as $value) //
            {
				//对时间段的字段处理
				if($value=='a_sweek AS 周次' or $value=='a_sdate AS 星期' or $value=='a_sclass AS 节次' or $value=='a_room AS 实验室安排' )
				{
					$a_time[] = $value;
					$count_time++;
				}
				//对登记表字段的处理
				else
				{
                $a_apply[] = $value;
                $count_apply++;
				}
            }
		for($i = 0;$i<$count_apply;$i++)
			{
			//生成sql查询语句
			$sql_apply .= $a_apply[$i].",";
			}
			$sql = "SELECT  ".$sql_apply."  a_id  FROM `apply1` WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY `a_rname` , `a_cname` , `a_sid`";
		for($j = 0;$j<$count_time;$j++)
			{
			$sql_time .= $a_time[$j].',';
			}
			$sql_time = substr($sql_time,0,strlen($sql_time)-1);
			//函数功能
			//
			outcourse($sql,$sql_time,$a_apply,$valid_time_range_begin_date,$valid_time_range_end_date);
		
		}
		else//为空显示
		{
			echo '<div align="center" style="font-size:14px; color:red; margin:10px; font-weight:bold;">请注意，您提交的查询序列是空的！</div>';
		}
	}
	else
	{
	/*	
	//没有设置列表则全部输出
	$sql = "SELECT  a_cname AS 课程名称, a_rname AS 教师名称 , a_ctype AS 课程类别 , a_sbook AS 实验教材 , a_sid AS 实验编号,a_sname AS 实验项目名称, a_stype AS 实验类型  , a_grade AS 年级 , a_major AS 专业 , a_class AS 班别 , a_people AS 人数 , a_learntime AS 计划学时, a_resources AS 耗材需求 , a_system AS 系统需求 , a_software AS 软件需求 , a_id FROM apply1 WHERE a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY `a_cname` , `a_rname` , `a_sid`";
	$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );  
	$row_num = mysql_num_rows($result);			//获取影响行的数目
	$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目

		//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
		echo "<span id='content' ><table align=\"center\"  border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
		
		echo "<tr>\n";
		for ( $i = 0; $i < 7; $i++ ) //登记表前面字段
			{   
				$meta = mysql_fetch_field ( $result );  
				echo "<th>{$meta->name}</th>\n";
			}
		echo "<th>周次</th>\n";
		echo "<th>星期</th>\n";
		echo "<th>节次</th>\n";
		for($i=7;$i<$col_num-1;$i++)//登记表后面字段
		{
			$meta = mysql_fetch_field ( $result );  
			echo "<th>{$meta->name}</th>\n";
		}
		echo "<th>实验室安排</th>\n";
		echo "</tr>\n";
		
		$row = mysql_fetch_array ( $result );    //取得查询出来的多行结果中的一行
		
		//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
		for ( $i = 0; $i < $row_num; $i++ ) 
			{
				$sql_tid = sprintf("SELECT a_sweek AS 周次, a_sdate AS 星期, a_sclass AS 节次 , a_room FROM time WHERE a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[15]);
				//printf($sql_tid);die();
				$result_tid = mysql_query($sql_tid) or die("不能查询指定的数据库表：" . mysql_error());
				$num_tid = mysql_num_rows($result_tid);
			
				$row_tid = mysql_fetch_array($result_tid);//读取多行结果中的一行
				$cont=0;//计数，输出的次数(针对计划学时的合并输出)
				for($num=0;$num<$num_tid;$num++)
					{ 
						echo "<tr>\n"; 
					for ( $j = 0; $j < 7; $j++ ) //登记表前半部分输出
						{
							if($j==4 or $j==5 or $j==6)//实验编号、实验项目名称、实验类型合并
							{
								if($cont==0)
								echo "<td align=\"center\" id=\"id$j\"  rowspan='$num_tid'>{$row[$j]}</td>\n";
							}
							else
								echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
						}
					for($j=8;$j<11;$j++)//时间段输出
						{
						$row_j=$j-8;
						echo "<td align=\"center\" id=\"id$j\">{$row_tid[$row_j]}</td>\n";
						}


						//
						
					for ( $j = 7; $j < mysql_num_fields($result)-1; $j++ ) //登记表后半部分输出
						{
							if($j==7 or $j==8 or $j==9 or $j==10)//年级、班级、专业、人数合并
							{
								if($cont==0)
								echo "<td align=\"center\" id=\"id$j\"  rowspan='$num_tid'>{$row[$j]}</td>\n";
							}
							else
							{
							if($j==11)//对计划学时特别处理
							{
								if($cont==0)//当变量为0的时候则合并单元格
								{
								echo "<td align=\"center\" id=\"id$j\" rowspan='$num_tid'>{$row[$j]}</td>\n";
								$cont++;
								}

							}
							else
								echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
							}
							
						}
						echo "<td align=\"center\" id=\"id$j\">{$row_tid[3]}</td>\n";
		    echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);//读取多行结果中的一行
			}
		  $row = mysql_fetch_array ( $result );
		}
		echo "</table>\n";
		*/
		echo '<div align="center" style="font-size:14px; color:red; margin:10px; font-weight:bold;">请选择要输出的字段！</div>';
	}
	/*
	函数功能：根据生成的sql查询语句，分别对登记表和时间表进行查询，并输出结果
	*/
	function outcourse($sql,$sql_time,$a_apply,$valid_time_range_begin_date,$valid_time_range_end_dat)
	{
		
		$result = mysql_query ( $sql ) or die ( "不能查询指定的数据库表：" . mysql_error() );  
		$row_num = mysql_num_rows($result);			//获取影响行的数目
		$col_num = mysql_num_fields( $result );     //获取影响字段(列)的数目
		
		//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
		echo "<span id='content' ><table align=\"center\"  border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
		
		echo "<tr>\n";
		for ( $i = 0; $i < $col_num-1; $i++ ) //登记表前面字段
			{   
				$meta = mysql_fetch_field ( $result );  
				echo "<th>{$meta->name}</th>\n";
			}
		if($sql_time!='')
		{
		//取得时间段的周次、星期、节次、房号
		$sql_t_id = "SELECT  ".$sql_time."  FROM time";
		$result_tid = mysql_query($sql_t_id) or die("不能查询指定的数据库表：" . mysql_error());
		$col_num_tid = mysql_num_fields( $result_tid );     //获取影响字段(列)的数目
		for( $i = 0; $i < $col_num_tid; $i++ )
		{
			$meta = mysql_fetch_field ( $result_tid );  
			echo "<th>{$meta->name}</th>\n";
		}
		}
		echo "</tr>\n";
		
		$row = mysql_fetch_array ( $result );    //取得查询出来的多行结果中的一行
		
		//利用双重循环打印出表项的内容(注意{$row[$j]}是具体哪一个表项的显示)
		for ( $i = 0; $i < $row_num; $i++ ) 
			{
				if($sql_time!='')
				{
				$sql_tid = sprintf("SELECT  %s  FROM time WHERE a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$sql_time,$row[$col_num-1]);
				$result_tid = mysql_query($sql_tid) or die("不能查询指定的数据库表：" . mysql_error());
				$num_tid = mysql_num_rows($result_tid);
			
				$row_tid = mysql_fetch_array($result_tid);//读取多行结果中的一行
				}
				else
				{
					$num_tid=1;
				}
				$cont=0;//计数，输出的次数(针对计划学时的合并输出)
				for($num=0;$num<$num_tid;$num++)
					{ 
					
						echo "<tr>\n"; 
					for ( $j = 0; $j < $col_num-1; $j++ ) //登记表前半部分输出
						{
							//对计划学时、实际学时、实验编号、实验项目、年级、专业、班别特别处理
							if($a_apply[$j]=='a_learntime AS 计划学时' or $a_apply[$j]=='a_sid AS 实验编号' or $a_apply[$j]=='a_sname AS 实验项目' or $a_apply[$j]=='a_stype AS 实验类型' or $a_apply[$j]=='a_grade AS 年级' or $a_apply[$j]=='a_major AS 专业' or $a_apply[$j]=='a_people AS 人数' or $a_apply[$j]=='a_class AS 班级')
							{
								if($cont==0)//当变量为0的时候则合并单元格
								{
								echo "<td align=\"center\" id=\"id$j\" rowspan='$num_tid'>{$row[$j]}</td>\n";
								}
							}
							elseif($a_apply[$j]=='a_stime AS 实际学时')
							{
								if($cont==0)//当变量为0的时候则合并单元格
								{
								echo "<td align=\"center\" id=\"id$j\" rowspan='$num_tid'>{$row[$j]}</td>\n";	
								}
							}
							else
							{
								echo "<td align=\"center\" id=\"id$j\">{$row[$j]}</td>\n";
							}
							
						}
						$cont++;
						if($sql_time!='')
						{
					for($j=0;$j < $col_num_tid;$j++)//时间段输出
						{
						$row_j=$col_num;
						echo "<td align=\"center\" id=\"id$row_j\">{$row_tid[$j]}</td>\n";
						}
						}					
					echo "</tr>\n";
					if($sql_time!='')
						{
						$row_tid = mysql_fetch_array($result_tid);//读取多行结果中的一行
						}
			}
			$row = mysql_fetch_array ( $result );
		}
		echo "</table>\n";
	}
		
?>
</span>
<!--单元格竖合并输出BEGIN-->
<script   language="JavaScript">   
     
  function   coalesce_row(obj,s,n,text){   
  var   text   
  table   =   obj;     
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
 
  //生成合并单元格
  if(isset($_POST['btnSubmit']))
  {
	for($i=0;$i<$count_apply;$i++)
	  {
		if($a_apply[$i]=='a_cname AS 课程名称' or $a_apply[$i]=='a_rname AS 老师名称' or $a_apply[$i]=='a_ctype AS 课程类别' or $a_apply[$i]=='a_sbook AS 实验教材')
			echo "coalesce_row(document.all.id".$i.",1,0,'null');";
	  }
  }
  else
  {
  echo "coalesce_row(document.all.id0,1,0,'null');";   //合并单元格  
  echo "coalesce_row(document.all.id1,1,0,'null');";   
  echo "coalesce_row(document.all.id2,1,0,'null');";
  echo "coalesce_row(document.all.id3,1,0,'null');";
  //coalesce_row(document.all.id7,1,0,'null')
  //coalesce_row(document.all.id8,1,0,'null')
  //coalesce_row(document.all.id9,1,0,'null')
  //coalesce_row(document.all.id15,1,0,'null')
  }
    
  
  
  //echo '';
  ?>
  </script>
<!--单元格竖合并输出END-->




</body>
</html>