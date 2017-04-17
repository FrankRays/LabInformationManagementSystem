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
function test_more() {//判断多个单选是否被选
	for (i=0;i<document.form_add.a_cname.length;i++) 
		{
		if (document.form_add.a_cname[i].checked) 
			{
			//alert("请选择要添加实验项目的课程！23"+document.form_add.a_cname.length);
			return true;
			}
		}
		alert("请选择要添加实验项目的课程！");
		return false;
}
function test_one(){//判断一个单选是否被选
	if (document.form_add.a_cname.checked) 
		return true;
	else
	{
		alert("请选择要添加实验项目的课程！");
		return false;
	}
}
//-->  
</script>

<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改登记表显示</title>
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
<!-- 		<p>
		备注：要增加新的实验项目，您可以在“填写登记表”里填上相同课程的名字，然后实验编号按顺序填写即可
		</p> -->

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

	$search_condition = $_GET['turn_back_Scondition']; //获取“返回”的查询条件
	$search_content = urldecode($_GET['turn_back_Scontent']);	//获取“返回”的查询变量
	
	if(!isset($search_condition)) 
	{
		$search_condition = "a_rname"; //初始化搜索条件以保证搜索语句不出错
	}

	//(2010-06-06提交回车修改)if(isset($_POST['btnSubmit']))  //针对“管理员”按下“查看”按钮时所做的处理
	if(isset($_POST['search_content']))  //针对“管理员”按下“查看”按钮时所做的处理
	{
		
		$search_condition = $_POST['search_condition'];
		$search_content = $_POST['search_content'];
	}


	if ($usercode=='1' or $usercode=='2' or $usercode=='3')		  //教师权限直接显示其名下课程
	{
			$search_condition = 'a_rname';		
			$search_content = $_SESSION["u_name"];	//获取真实名称			
	}
	
		else if ($usercode =='5' or $usercode =='4')  //管理员权限5要求先输入教师名称
		{
			echo "请先输入查询条件";
			echo '<form action="course_register_edit_show.php" method="post">';
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
		
	 
	 /*如果选择的查询的条件为老师的话，执行下面的语句END-------*/

		
		$sql = sprintf("SELECT a_cname AS 课程名称, a_rname AS 教师名称, a_sid AS 实验编号,a_sname AS 实验项目名称, a_id AS 操作1 FROM apply1  WHERE %s='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' ORDER BY `a_rname`,`a_cname`,`a_sid`",$search_condition,$search_content);
		$result = mysql_query ( $sql )
		  or die ( "不能查询指定的数据库表：" . mysql_error() );
		  
		$row_num = mysql_num_rows($result);       //获取影响行的数目
		
		
		if($search_condition == 'a_rname') //搜索条件为教师名时输出的标题
		{
			echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'老师已提交的实验室申请如下</div>';
		}
		
		if($search_condition == 'a_cname') //搜索条件为课程名时输出的标题
		{
			echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">'.$search_content.'课程的相关申请如下</div>';
		}
		
		echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
		//echo "<caption>"</caption>\n";
		echo "<tr>\n";
		echo "<th>课程名称</th>";
		echo "<th>老师名称</th>";
		echo "<th>实验编号</th>";
		echo "<th>实验项目</th>";
		echo "<th>周次</th>";
		echo "<th>星期</th>";
		echo "<th>节次</th>";
		echo "<th>修改</th>";
		echo "<th>删除</th>";
		echo "</tr>\n";
		//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
		/*
		for ( $i = 0; $i < $col_num-1; $i++ ) {   
		  $meta = mysql_fetch_field ( $result );  //教师名称和课程名称
		  echo "<th>{$meta->name}</th>\n";
		}*/
		$row = mysql_fetch_array ( $result ); //取得查询出来的多行结果中的一行(每一个实验)
		for($i=0;$i<$row_num;$i++)
		{
		$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[2],$row[4]);
		$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		$row_num_tid = mysql_num_rows($result_tid);       //获取影响行的数目
		$row_tid = mysql_fetch_array($result_tid);
		for($n=0;$n<$row_num_tid;$n++)//应该用时间段的行数
		{
			echo "<tr>";
		    echo "<td align=\"center\" id=\"id0\" >".$row[0]."</td>";//课程名字
		    echo "<td align=\"center\" id=\"id1\" >".$row[1]."</td>";//老师名称
			echo "<td align=\"center\" id=\"id2\" >".$row[2]."</td>";//实验编号
			echo "<td align=\"center\" id=\"id3\" >".$row[3]."</td>";//实验名称
			//时间段
			echo "<td align=\"center\" id=\"id4\">".$row_tid[0]."</td>";//周次
			echo "<td align=\"center\" id=\"id5\">".$row_tid[1]."</td>";//星期
			echo "<td align=\"center\" id=\"id6\">".$row_tid[2]."</td>";//节次

			//单独添加"修改链接"	
			//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
			//$row[4]表示登记表的编号
			echo "<td align=\"center\" id=\"id7\">
		    <img src=\"../images/edit.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_edit_single.php?a_id=$row[4]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='修改'/>
			</td>\n";
			//增加删除操作2
			echo "<td align=\"center\" id=\"id8\">
	        <img src=\"../images/delete.gif\"  style=\"cursor:hand;\" onClick='javascript:if(confirm(\"您确实要删除该记录吗？\"))location=\"course_register_delete_result.php?a_id=$row[4]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='删除'/>
		    </td>\n";
			echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);

		}
		$row = mysql_fetch_array ($result);//获取相同课程的a_id
		}
		echo "</table>\n";
		
	/*如果选择的查询的条件为老师的话，执行下面的语句BEGIN-------*/
	/*主要功能是输出老师所有本学期的上课课程-------*/
	/*---------2009-9-23（广强）-------*/
	 
	 if($search_condition == 'a_rname')//当以老师名字为条件查询时
	 {
		$sql_cname = sprintf("SELECT a_cname FROM apply1  WHERE %s='%s' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'",$search_condition,$search_content);
		//print_r($sql_cname);die();
		$result_cname = mysql_query( $sql_cname ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		$row_num_cname = mysql_num_rows($result_cname);
		$row_cname = mysql_fetch_array ( $result_cname );
		//array $row_a_cname;
		for($i=0;$i<$row_num_cname;$i++)
		{
			
			$row_a_cname[$i] = $row_cname["a_cname"];//存储到数组里		
			$row_cname = mysql_fetch_array ( $result_cname );
			
		}

		 if(count($row_a_cname) > 0)//有课程执行course_register_edit_show_add.php
		 {
			 //对数组的各个值进行比较，只保留不同项(用数组函数array_values，array_unique)
			 $cname = array_values(array_unique($row_a_cname)); 
			 //echo count($cname);//输出课程数组的长度
			
			 echo "<form name='form_add' method='post' action='course_register_edit_show_add.php' onsubmit='";
			 if(count($cname)==1)//用于单选的选择判断条件
				 echo "return test_one()'>";
			 else
				 echo "return test_more()'>";
			 echo "任课老师所有课程:";
			 for($i=0;$i<count($cname);$i++)
			{
				if($cname[$i] != ''){
				echo "<input name='a_cname' type='radio' value='".$cname[$i]."' />".$cname[$i];
				//输出所有老师的课程名称，根据老师课程名称去查找相关的申请数据
				//echo "<input name='a_cname' type='hidden' value='".$cname[$i]."' />";
			}
			//包括老师的一些信息
			
		 } 
		 echo '<input name="a_rname" type="hidden" value="'.$search_content.'" />';
		 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		 echo "<input name='submit_add' type='submit'  style='cursor:hand;' value='添加所选课程的实验项目'>";
		 echo "</form>";
		 }

	 }	
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
  coalesce_row(document.all.id0,1,0,'null')     
  coalesce_row(document.all.id1,1,0,'null')   
  coalesce_row(document.all.id2,1,0,'null') 
  coalesce_row(document.all.id3,1,0,'null')
  //coalesce_row(document.all.id4,1,0,'null')
  coalesce_row(document.all.id7,1,0,'null')
  coalesce_row(document.all.id8,1,0,'null')
  </script>
<!--单元格竖合并输出END-->

</body>
</html>