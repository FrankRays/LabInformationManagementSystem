<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include("../common/db_conn.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$teachername = $_SESSION["u_name"];	//获取真实名称
	$usercode = $_SESSION["u_type"];		  //获取权限代码
	
	$search_condition = $_GET['search_condition']; //用于返回到上一个页面的“搜索条件”
	$search_content = urldecode($_GET['search_content']); //用于返回到上一个页面的“搜索内容”
	
	$a_id = $_GET['a_id'];

	$sql = sprintf ( "SELECT * FROM apply1 WHERE a_id='%s'",$a_id);
	$rs = mysql_query ( $sql ) or die ( "获取a_id时发生错误:" . mysql_error() );
	$row = mysql_fetch_array( $rs );
	
	
?>

<head>

<script language="javascript">
 <!--
//屏蔽URL的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->

<title>登记表详细信息显示</title>

</head>


<body>
<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单第一个内边框DIV_BEGIN-->
			
        <legend>课程基本信息</legend><!--表单内边框标题-->
        <br />

        <p>教师姓名：<?=$row['a_rname']?></p>
        
        <p>课程：<?= $row['a_cname']?></p>
    
        <p>课程类别：<?= $row['a_ctype']?></p>

        <p>实验教材：<?= $row['a_sbook']?></p>
        </fieldset><!--表单第一个内边框DIV_END-->

		<fieldset><!--表单第二个内边框DIV_BEGIN-->
		<legend>实验基本信息</legend>
		<br/>


        <p>实验编号：实验<?= $row['a_sid']?></p>
    
        <p>实验项目：<?= $row['a_sname']?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            实验类型：<?= $row['a_stype']?>
        </p>
		<!---------------------------时间段输出BEGIN------------------------------------->
		<?php
		$sql_tid = sprintf ( "SELECT * FROM time WHERE a_id='%s'",$a_id);
		 $rs_tid = mysql_query($sql_tid) or die("不能查询指定的数据库表：".mysql_error());
		 $num_tid = mysql_num_rows($rs_tid);		//获取影响行数
		 $row_tid = mysql_fetch_array($rs_tid);	//获取多行数据的一行
		 for($i=0;$i<$num_tid;$i++)
		 {
		?>
        <p>实验时间——周次：<?= $row_tid['a_sweek']?> 		
            &nbsp;&nbsp;&nbsp;&nbsp;
            星期：<?= $row_tid['a_sdate']?>        
            &nbsp;&nbsp;&nbsp;&nbsp;
            节次：<?= $row_tid['a_sclass']?>
        </p>
		 <p>实验室安排：<?= $row_tid['a_room']?></p>
		 <?php
		 $row_tid = mysql_fetch_array($rs_tid);	//获取多行数据的一行
		 }
		 ?>
        <!------------------------------END----------------------------------->
        <p>
            年级：<?= $row['a_grade']?>
            专业：<?= $row['a_major']?>
            班别：<?= $row['a_class']?>
        </p>
      
        <p>
            人数：<?= $row['a_people']?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            计划学时：<?= $row['a_learntime']?>
			&nbsp;&nbsp;&nbsp;&nbsp;
            实际学时：<?= $row['a_stime']?>
        </p>   
        
        
        <p>耗材需求：<?= $row['a_resources']?></p>
        
        <p>系统需求：<?= $row['a_system']?></p>
        
        <p>软件需求：<?= $row['a_software']?></p>
        
       

        </fieldset><!--表单第二个内边框DIV_END	-->
		<br />
		<!--a href="course_register_search.php">返回</a-->
		<?php
		if( $_SESSION["u_type"] == '1' or $_SESSION["u_type"] == '2')//给教师及负责人的链接
		{
			echo '<input type="button" onclick="location.href=\'course_register_search.php\';" class="buttonSubmit" value="返回"/>';
		}
		else//管理员的链接
			{
				echo "<input type=\"button\" onclick=\"location.href='course_register_search.php?turn_back_Scondition=".$search_condition."&turn_back_Scontent=".urlencode($search_content)."';\" class=\"buttonSubmit\" value=\"返回\"/>";
			}

        ?>
        <br /><br />
</div><!--表单外边框DIV_END-->

</body>
</html>