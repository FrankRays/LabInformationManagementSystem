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

<!-----------------------------------自动完成功能BEGIN---------------------------------->

<script type="text/javascript" src="../js/jquery-1.2.6.js"></script>
<script type="text/javascript">
	function chooseall()
	{ 
	 var arr=document.getElementsByName("field[]");
	 var sum=arr.length;
	 for (var i=0;i<sum;i++)
	 {
		if(arr[i].checked==true)
		arr[i].checked =false;
		else 
		arr[i].checked =true;
	 }
	 var arr=document.getElementsByName("field[]");
	 var sum=arr.length;
	 for (var i=0;i<sum;i++)
	 {
		if(arr[i].checked==true)
		arr[i].checked =false;
		else 
		arr[i].checked =true;
	 }
	}
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

<!-----------------------------------批量修改字段功能END------------------------------------>


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
	
	//2017-04-13获取搜索添加，作保存后返回默认
	$search_condition = $_POST['search_condition'];
	$search_content = $_POST['search_content'];
	
	/***************保存数据***************/
	if($_POST["savesub"])
	{  
	  $arrfield=explode(",",$_POST["arrfield"]); //print_r($arrfield);
	  $arrtid=$_POST["arrtid"];
	  $arraid=$_POST["arraid"];//echo $arraid;echo "<hr>";
	  foreach($arrfield as $field)
	  {
	     
		if($field=="a_sweek" || $field=="a_sdate" || $field=="a_sclass" || $field=="a_sid" || $field=="a_room")  //对应 time 表
		{		  
		  if( $field=="a_sid" )
		  { 
			$sql2.=" s_id='".$_POST["$field"]."',";  
			$sql1.=" a_sid='".$_POST["$field"]."',";}
		  else
		  { $sql2.=" $field='".$_POST["$field"]."',";}
		}
		else
		{
			  $sql1.=" $field='".$_POST["$field"]."',";  // 对应 apply1 表
		}
	  } 
	  $sql1=substr($sql1,0,strlen($sql1)-1); //去掉最后一个逗号
	  $sql2=substr($sql2,0,strlen($sql2)-1); //去掉最后一个逗号
	  
	 
	  
	  
	  $dosql1="UPDATE apply1 SET ".$sql1."  WHERE a_id IN(".$arraid.")";//echo $dosql1."<br>";
	 
	  $dosql2="UPDATE time SET ".$sql2." WHERE t_id IN(".$arrtid.")";//echo $dosql2."<br>";
	  $res1=@mysql_query($dosql1);
	  $res2=@mysql_query($dosql2); 
	  
	  //计算并更新实际学时
	  $reset_arraid=explode(',',$arraid); //将申请实验主键分成数组形式
	  //print_r( $reset_arraid); 
	  //从time表获取时间段
	  foreach($reset_arraid as $aid)
	  {
		  $allaid="";
		  $theres=mysql_query("SELECT a_sclass FROM time WHERE a_id={$aid}");
		  while($rowaid = mysql_fetch_array($theres))
		  {
			  $allaid.=$rowaid[0].',';
		  }
		  $allaid=substr($allaid,0,strlen($allaid)-1);  //去掉最后一个逗号
		  
		  $countaid=count(explode(',',$allaid));
		  
		  mysql_query("UPDATE apply1 SET  a_stime={$countaid} WHERE a_id={$aid}"); //更新实际学时
	  }
	  
	  
	  if( $res1 || $res2 )
	  {
	   echo "<script language=\"javascript\">alert(\"字段信息修改成功!\");\";</script>";  
	  }
	   echo "<script language=\"javascript\">location.href=\"course_register_edit_show.php?turn_back_Scondition=".${search_condition}."&turn_back_Scontent=".${search_content}."\"; </script>";  
	   exit();  
	}
	/***************保存数据END**************/
	
	//print_r($_POST['field']);//print_r($_POST['record']);
	
	echo '<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><p>批量修改字段</p>注意：修改字段后房间不会重新分配,请按照”填写登记表“的格式填写</div>';
	if($_POST['field'])
	{ $arrfield=implode(",",$_POST['field']); } //用逗号链接字段信息
	if($_POST['record'])
	{ 
	  $arrtid=implode(",",$_POST['record']);  //用逗号链接time表的记录t_id
	  $gaidsql="SELECT DISTINCT a_id FROM time WHERE t_id IN(".$arrtid.")";
	  $gaidsqlres=mysql_query( $gaidsql);
	 
	  while($row=mysql_fetch_array($gaidsqlres))
	  {
		  $arraid.=$row[0].",";
	  }
      $arraid=substr($arraid,0,strlen($arraid)-1); //用逗号链接apply1表的记录a_id
	  
	}
	//echo $arraid;echo $arrfield;

	/********************zyc修改(批量字段管理)************************/
	echo "<form action=\"\" method=\"post\">";
	
	echo "<input type='hidden' name='search_condition' value='${search_condition}'>";
	echo "<input type='hidden' name='search_content' value='${search_content}'>";
	
	echo "<input type=\"hidden\" name=\"arrfield\" value=\"$arrfield\">";
	echo "<input type=\"hidden\" name=\"arrtid\" value=\"$arrtid\">";
	echo "<input type=\"hidden\" name=\"arraid\" value=\"$arraid\">";
	echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
	//echo "<caption>"</caption>\n";
	echo "<tr>\n";
	echo "<th>字段</th>";
	$arrfield=empty($_POST['field'])?array():$_POST['field'];
	foreach($arrfield as  $field)
	{
		switch($field)
		{
		case "a_cname":
		            echo "<th>课程名称</th>"; 
					break;	
		case "a_rname":
		            echo "<th>教师名称</th>"; 
					break;
		case "a_ctype":
		            echo "<th>课程类别</th>"; 
					break;
		case "a_sbook":
		            echo "<th>实验教材</th>"; 
					break;
		case "a_sid":
		            echo "<th>实验编号</th>"; 
					break;				
		case "a_sname":
		            echo "<th>实验项目</th>"; 
					break;
		case "a_stype":
		            echo "<th>实验类型</th>"; 
					break;

		case  "a_sweek":
		            echo "<th>周次</th>"; 
					break;
		
		case "a_sdate":
		            echo "<th>星期</th>"; 
					break;
						
		case "a_sclass":
		            echo "<th>节次</th>"; 
					break;
		case "a_grade":
		            echo "<th>年级</th>"; 
					break;
		
		case "a_major":
		            echo "<th>专业</th>"; 
					break;
		case "a_class":
		            echo "<th>班别</th>"; 
					break;
		
		case "a_people":
		            echo "<th>人数</th>"; 
					break;
		case "a_learntime":
		            echo "<th>计划学时</th>"; 
					break;
		
		case "a_stime":
		            echo "<th>实际学时</th>"; //实际上不会显示，实际学时通过计算得到
					break;
		case "a_resources":
		            echo "<th>耗材需求</th>"; 
					break;
		case "a_system":
		            echo "<th>系统需求</th>"; 
					break;
		case "a_software":
		            echo "<th>软件需求</th>"; 
					break;
		case "a_room":
		            echo "<th>实验室安排</th>"; 
					break;
		 default: ;
	   }
      }
	echo "</tr>";
	echo "<tr>";
	echo "<th>修改值</th>";if($_POST['a_cname'])
	reset($arrfield); //数组指针重置，用于下次循环
	foreach($arrfield as  $field)
	{
	   switch($field)
	   {
		 case 'a_ctype': //课程类别
		             echo "<th><select name=\"$field\">";
                    
                      $sql = "SELECT c_direction FROM `course` GROUP BY c_direction";
                      $result = mysql_query ( $sql )  or die ( "不能查询指定的数据库表：" . mysql_error() );
                       while( $output = mysql_fetch_object($result) ){
                            echo '<option value="'.$output->c_direction.'">'.$output->c_direction.'</option>';
                        }
                    echo "</select></th>";
                    break;
          case 'a_stype':  //实验类型
		            echo "<th><select name=\"$field\">";
                    echo "<option value=\"演示型\">演示型</option>";
				    echo "<option value=\"验证型\">验证型</option>";
				    echo "<option value=\"综合型\">综合型</option>";
				    echo "<option value=\"设计研究型\">设计研究型</option>";
				    echo "<option value=\"其他\">其他</option>";
                    echo "</select></th>";
		            break;
		  case "a_sweek":  //周次
		            echo "<th><select name=\"$field\">";
                
                    for($i=1 ; $i<=20 ; $i++)
                    {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    }       
                   echo "</select></th>";
		           break;
		  case  "a_sclass": //节次
		           echo "<th><select name=\"$field\">";
                   echo '<option value="1,2" selected>1-2节</option>
                            <option value="3,4">3-4节</option>
                            <option value="5,6">5-6节</option>
                            <option value="7,8">7-8节</option>
                            <option value="1,2,3">1-3节</option>
                            <option value="2,3,4">2-4节</option>
                            <option value="5,6,7">5-7节</option>
                            <option value="6,7,8">6-8节</option>
                            <option value="1,2,3,4">1-4节</option>
                            <option value="5,6,7,8">5-8节</option>
                            <option value="9,10">9-10节</option>
                            <option value="1,2,3,4,5,6,7,8">1-8节</option>';				 
                   echo "</select></th>";
				   break;
		  case "a_sdate": //星期
		           echo "<th><select name=\"$field\">";
                   echo "<option value=\"1\" selected>一</option>";
                   echo "<option value=\"2\">二</option>";
                   echo "<option value=\"3\">三</option>";
                   echo "<option value=\"4\">四</option>";
                   echo "<option value=\"5\">五</option>";
                   echo "<option value=\"6\">六</option>";
                   echo "<option value=\"7\">日</option>";
                   echo "</select></th>";
				   break;
		  
		  default:
                   echo "<th><input type=\"text\" name=\"$field\" value=\"\" style=\"width:120px;\"></th>"; 
	   }
	}
	echo "</tr>";
	echo "</table>";
	echo "<input type=\"submit\" name=\"savesub\" value=\"确定\">&nbsp;<input type=\"button\" name=\"back\" onclick=\"window.history.back(); \" value=\"返回\">";
	echo "</form>";
?>
</body>
</html>