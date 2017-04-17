
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>删除本学期所有的登记表</title>
<link rel="stylesheet"  href="../css/tablecloth_forBigTable.css" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<style type="text/css">
#formwrapper {
    width:700px;
    margin:15px auto;
    padding:10px 25px 0px 25px;
    text-align:center;
    border:1px #1E7ACE solid;
	background:#FFFFFF;
}
</style>
<script language="javascript">
 <!--
//屏蔽table.length的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
function check(){//判断一个单选是否被选
	if (confirm("您确实要删除该学期登记表记录吗？")) 
		return true;
	else
	{
		//alert("请选择要添加实验项目的课程！");
		return false;
	}
}
//-->  
</script>
</head>
<body>
<?php
include("../common/session.inc");
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
	//该页使用者的权限（系统管理员才有这样的才有这样的权限usercode=5）
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<4) 
	echo "<script language='javascript'>alert('你无权进行此操作！');location.href='index.html';</script>";
?>
<div align="center" style="font-size:14px; color:red; font-weight:bold;">
请注意：删除本学期数据时，请对系统本学期登记表的数据备份！
</div>
<div id="formwrapper" >
<form  method="post" action="" onsubmit="return check()">
<input name="begin_date" type="hidden"  value="<?= $valid_time_range_begin_date?>">
<input name="end_date" type="hidden"  value="<?= $valid_time_range_end_date?>">
<?php echo $table_title."登记表数据"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="submit" type="submit" value="删除本学期数据">
</form></div>
<?php
//javascript:if(confirm("您确实要删除该记录吗？") return true; else ruturn flase;)alert("hhhs")
if(isset($_POST['submit']))//按下删除
{
$valid_time_range_begin_date = $_POST['begin_date'];
$valid_time_range_end_date = $_POST['end_date'];
$sql = "SELECT a_id FROM `apply1` WHERE  a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
$rs_aid = mysql_query( $sql ) or die ("不能查询指定数据库：" . mysql_error());
$row_num = mysql_num_rows( $rs_aid );			//获取影响行的数目
$row_aid = mysql_fetch_array ( $rs_aid );    //取得查询出来的多行结果中的一行
for($i=0;$i<$row_num;$i++)
	{
	$sql_tid = "DELETE FROM `time` WHERE a_id=$row_aid[0]";
	mysql_query( $sql_tid ) or die ("不能查询指定数据库表：" . mysql_error());
	if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断插入记录是否成功
		{
			$sql_aid = "DELETE FROM `apply1` WHERE a_id=$row_aid[0]";
			mysql_query( $sql_aid ) or die ("不能查询指定数据库表：" . mysql_error());
			echo $row_aid[0]."-------";
		}
	$row_aid = mysql_fetch_array ( $rs_aid );    //取得查询出来的多行结果中的一行
	}
if(mysql_affected_rows($conn))   //通过判断上述操作影响的行数以判断删除记录是否成功
  { 
	  include("../log/log_delete_all.inc");
      echo "<script language='javascript'>alert('记录已删除！');
				     location.href='delete.php';</script>";	
					 
  }
  else 
    {  echo "<script language='javascript'>alert('记录删除失败！');</script>";			 
       echo '<p align=center><a href=javascript:history.back()>请点击返回</a>';
	 }
mysql_close( $conn );
}
?>
</body>
</html>