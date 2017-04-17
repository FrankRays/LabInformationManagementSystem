<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include("../common/db_conn.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<4) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$teachername = $_SESSION["u_name"];	//获取真实名称
	$usercode = $_SESSION["u_type"];	//获取权限代码

	
	
	
	$a_id = $_GET['a_id'];
	$a_sweek = $_GET['a_sweek'];
	$a_sdate = $_GET['a_sdate'];

	$sql = sprintf ( "SELECT * FROM apply1 WHERE a_id='%s'",$a_id);
	$rs = mysql_query ( $sql ) or die ( "获取a_id时发生错误:" . mysql_error() );
	$row = mysql_fetch_array( $rs );
	//获取时间表数据
	$sql_tid = sprintf ("SELECT * FROM time WHERE a_id='%d' AND s_id='%d' AND a_sweek='%s' AND a_sdate='%s' ORDER BY `a_sweek`",$a_id,$row['a_sid'],$a_sweek,$a_sdate);
	$rs_tid = mysql_query ( $sql_tid ) or die ( "获取t_id时发生错误:" . mysql_error() );
	$num_tid = mysql_num_rows($rs_tid);//获得影响行数
	$row_tid = mysql_fetch_array( $rs_tid );
	
	
?>
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
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<!--script language="javascript" type="text/javascript" src="../js/select2css.js"></script><!--表单下拉列表美化JS-->
<!--link rel="stylesheet" type="text/css" href="../css/select2css.css"><!--表单下拉列表美化CSS-->

<title>修改登记表</title>

</head>


<body>

<div id="formwrapper"><!--表单外边框DIV_BEGIN-->

    <form action="manual_arrange_result.php" method="post" onSubmit="return Validator.Validate(this,3)">
		<fieldset><!--表单第一个内边框DIV_BEGIN-->
        <legend>课程基本信息</legend><!--表单内边框标题-->
        <p>
	<?php
		//根据权限确定用户是否可以输入教师姓名
		if( $usercode=='1' )
		{
			echo "教师姓名：$teachername";
		}
			else if( $usercode=='5' or $usercode=='4' )
			{
				echo '教师姓名：<input name="form_rname" type="text" dataType="Require" msg="教师姓名不能为空" value="'.$row['a_rname'].'" />';
			}
	?>
	</p>
    
    <p>
    <!--不可修改-->
	课程：<?= $row['a_cname']?><input name="cname" type="hidden" value="<?= $row['a_cname']?>" />	
	</p>


    <p>课程类别：
		<select name="cdirection">
		<?php
			$sql = "SELECT c_direction FROM `course` GROUP BY c_direction";
			$result = mysql_query ( $sql )  or die ( "不能查询指定的数据库表：" . mysql_error() );
			while( $output = mysql_fetch_object($result) )
			{
				if($row['a_ctype']==$output->c_direction)//判断默认选项
				{
					echo '<option value="'.$output->c_direction.'" selected>'.$output->c_direction.'</option>';
				}
				echo '<option value="'.$output->c_direction.'">'.$output->c_direction.'</option>';
			}
		?>
        </select>
  	</p>
        
        <p>实验教材：<textarea name="sbook" rows="2" cols="50" ><?= $row['a_sbook']?></textarea>（实验指导书名称，编(写)者，出版社，出版日期）</p>
		</fieldset><!--表单第一个内边框DIV_END-->

		<fieldset><!--表单第二个内边框DIV_BEGIN-->
        <legend>实验基本信息</legend><!--表单内边框标题-->

	<p>
		实验编号：<!--不可修改-->
		实验<?= $row['a_sid']?><input name="sid" type="hidden" value="<?= $row['a_sid']?>" size="2"/>
	</p>

	<p>
        实验项目：<input name="sname" type="text" dataType="Require" msg="实验项目不能为空" value="<?= $row['a_sname']?>" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        实验类型：
            <select name="stype">
            <option value="设计型" <?php if($row['a_stype']=='设计型'){echo 'selected';}?>>设计型</option>
            <option value="基础型" <?php if($row['a_stype']=='基础型'){echo 'selected';}?>>基础型</option>
            <option value="验证型" <?php if($row['a_stype']=='验证型'){echo 'selected';}?>>验证型</option>
            <option value="综合型" <?php if($row['a_stype']=='综合型'){echo 'selected';}?>>综合型</option>
            <option value="演示型" <?php if($row['a_stype']=='演示型'){echo 'selected';}?>>演示型</option>
            </select>
    </p>

	<p>实验时间——周次：
		<select size="1" name="sweek[]">
        <option value="1" <?php if($row_tid['a_sweek']=='1'){echo 'selected';}?>>1</option>
        <option value="2" <?php if($row_tid['a_sweek']=='2'){echo 'selected';}?>>2</option>
        <option value="3" <?php if($row_tid['a_sweek']=='3'){echo 'selected';}?>>3</option>
        <option value="4" <?php if($row_tid['a_sweek']=='4'){echo 'selected';}?>>4</option>
        <option value="5" <?php if($row_tid['a_sweek']=='5'){echo 'selected';}?>>5</option>
        <option value="6" <?php if($row_tid['a_sweek']=='6'){echo 'selected';}?>>6</option>
        <option value="7" <?php if($row_tid['a_sweek']=='7'){echo 'selected';}?>>7</option>
        <option value="8" <?php if($row_tid['a_sweek']=='8'){echo 'selected';}?>>8</option>
        <option value="9" <?php if($row_tid['a_sweek']=='9'){echo 'selected';}?>>9</option>
        <option value="10" <?php if($row_tid['a_sweek']=='10'){echo 'selected';}?>>10</option>
        <option value="11" <?php if($row_tid['a_sweek']=='11'){echo 'selected';}?>>11</option>
        <option value="12" <?php if($row_tid['a_sweek']=='12'){echo 'selected';}?>>12</option>
        <option value="13" <?php if($row_tid['a_sweek']=='13'){echo 'selected';}?>>13</option>
        <option value="14" <?php if($row_tid['a_sweek']=='14'){echo 'selected';}?>>14</option>
        <option value="15" <?php if($row_tid['a_sweek']=='15'){echo 'selected';}?>>15</option>
        <option value="16" <?php if($row_tid['a_sweek']=='16'){echo 'selected';}?>>16</option>
        <option value="17" <?php if($row_tid['a_sweek']=='17'){echo 'selected';}?>>17</option>
        <option value="18" <?php if($row_tid['a_sweek']=='18'){echo 'selected';}?>>18</option>
        <option value="19" <?php if($row_tid['a_sweek']=='19'){echo 'selected';}?>>19</option>
        <option value="20" <?php if($row_tid['a_sweek']=='20'){echo 'selected';}?>>20</option>
        </select>
 		
        &nbsp;&nbsp;&nbsp;&nbsp;
        星期：
        <select name="sdate[]">
        <option value="1" <?php if($row_tid['a_sdate']=='1'){echo 'selected';}?>>一</option>
        <option value="2" <?php if($row_tid['a_sdate']=='2'){echo 'selected';}?>>二</option>
        <option value="3" <?php if($row_tid['a_sdate']=='3'){echo 'selected';}?>>三</option>
        <option value="4" <?php if($row_tid['a_sdate']=='4'){echo 'selected';}?>>四</option>
        <option value="5" <?php if($row_tid['a_sdate']=='5'){echo 'selected';}?>>五</option>
        </select>
        
        &nbsp;&nbsp;&nbsp;&nbsp;
        节次：
		<select name="sclass[]">
        <option value="1,2"   <?php if($row_tid['a_sclass']=='1,2')  {echo 'selected';}?>>1-2节</option>
        <option value="3,4"   <?php if($row_tid['a_sclass']=='3,4')  {echo 'selected';}?>>3-4节</option>
        <option value="5,6"   <?php if($row_tid['a_sclass']=='5,6')  {echo 'selected';}?>>5-6节</option>
        <option value="7,8"   <?php if($row_tid['a_sclass']=='7,8')  {echo 'selected';}?>>7-8节</option>
        <option value="1,2,3" <?php if($row_tid['a_sclass']=='1,2,3'){echo 'selected';}?>>1-3节</option>
        <option value="2,3,4" <?php if($row_tid['a_sclass']=='2,3,4'){echo 'selected';}?>>2-4节</option>
        <option value="5,6,7" <?php if($row_tid['a_sclass']=='5,6,7'){echo 'selected';}?>>5-7节</option>
        <option value="6,7,8" <?php if($row_tid['a_sclass']=='6,7,8'){echo 'selected';}?>>6-8节</option>
        <option value="1,2,3,4" <?php if($row_tid['a_sclass']=='1,2,3,4'){echo 'selected';}?>>1-4节</option>
        <option value="5,6,7,8" <?php if($row_tid['a_sclass']=='5,6,7,8'){echo 'selected';}?>>5-8节</option>
		<option value="9,10" <?php if($row_tid['a_sclass']=='9,10'){echo 'selected';}?>>9-10节</option>
		<option value="1,2,3,4,5,6,7,8" <?php if($row_tid['a_sclass']=='1,2,3,4,5,6,7,8'){echo 'selected';}?>>1-8节</option>
        </select>
	</p>
	<?php 
            //管理员权限下有权手动添加实验室房间号		
            if ($usercode == '4' or $usercode == '5')
            {
                echo '<p>';
                echo '实验室安排：';
                echo '<input name="form_room" type="text" value="'.$row_tid['a_room'].'" size="20" dataType="Require" msg="实验室方向不能为空"/>';
                echo '（请正确填写，注意多个实验室之间必须以半角逗号隔开）';
				//echo "<img src='../images/del_time.gif'  style='cursor:hand;' onClick='javascript:if(confirm(\"您确实要删除该记录吗？\")) location=\"course_register_edit_single.php?t_id=t_id=$row_tid[0]\";'>";
                echo '</p>';
            }
	?>
        <!--动态</div>添加End-->
    
    <p>
        年级：<input name="grade" type="text" value="<?= $row['a_grade']?>" size="10"/>
        专业：<input name="major" type="text" value="<?= $row['a_major']?>" size="10"/>
        班别：<input name="class" type="text" value="<?= $row['a_class']?>" size="10"/>
    </p>
  
    <p>
    	人数：<input name="stu_num" type="text" value="<?= $row['a_people']?>" size="2" dataType="Number" msg="请填写数字" />（请用阿拉伯数字填写）
        &nbsp;&nbsp;&nbsp;&nbsp;
        计划学时：<input name="learntime" type="text" value="<?= $row['a_learntime']?>" size="2" />
		&nbsp;&nbsp;&nbsp;&nbsp;
        实际学时：<input name="stime" type="text" value="<?= $row['a_stime']?>" size="2" />
    </p>   
    
    
    <p>耗材需求：<textarea name="resources" rows="2" cols="50" ><?= $row['a_resources']?></textarea></p>
    
	<p>系统需求：<textarea name="system" rows="2" cols="50" ><?= $row['a_system']?></textarea></p>
    
    <p>软件需求：<textarea name="software" rows="2" cols="50" ><?= $row['a_software']?></textarea></p>
    
    <!--隐藏传送记录登记表编号a_id、时间段的t_id-->
    <input name="a_id" type="hidden" value="<?= $a_id?>" />
	<input name="t_id" type="hidden" value="<?= $row_tid['t_id']?>" />
    

    </fieldset><!--表单第二个内边框DIV_END-->
    <p>
        <input name="btnSubmit" type="submit" value="提交"  class="buttonSubmit" />
        &nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="button" onclick="location.href='collision_detect.php'" class="buttonSubmit" value="返回"/>
    </p>
</form>
</div><!--表单外边框DIV_END-->
</body>
</html>