<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include("../common/db_conn.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";
	
	$u_type = $_SESSION["u_type"];	//获取权限代码
	$u_name = $_SESSION["u_name"];	//获取真实名称
	
	$sql = sprintf ( "SELECT * FROM user WHERE u_type='%s' AND u_name='%s'",$u_type,$u_name);
	$rs = mysql_query ( $sql ) or die ( "获取u_id时发生错误:" . mysql_error() );
	$row = mysql_fetch_array( $rs );
	
?>
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<script language="javascript" type="text/javascript" src="../js/select2css.js"></script><!--表单下拉列表美化JS-->
<link rel="stylesheet" type="text/css" href="../css/select2css.css"><!--表单下拉列表美化CSS-->

<title>修改个人信息</title>
</head>
<body>



<div id="formwrapper"><!--表单外边框DIV_BEGIN-->

	<form action="edit_personal_info_normal_result.php" method="post" onSubmit="return Validator.Validate(this,3)">
	<fieldset><!--表单内边框DIV_BEGIN-->
        <legend>个人信息修改</legend><!--表单内边框标题-->
		<p>
		<label for="u_name">用户姓名：</label>
		<?= $row['u_name']?><input name="u_name" type="hidden" value="<?= $row['u_name']?>"/>
		<input name="u_id" type="hidden" value="<?= $row['u_id']?>"/>
		</p>
	    
	    <p>
		<label for="u_lname">登&nbsp;&nbsp;录&nbsp;i&nbsp;d：&nbsp;&nbsp;</label>
        <input name="u_lname" type="text" dataType="Require" msg="用户登录id不能为空" value="<?= $row['u_lname']?>" class="textinput" />
		</p>
		
	    <p>
	    <label for="u_password">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
        <input name="u_password" type="password" dataType="Limit" min="3" max="16"  msg="密码必须在6-16位之间" value="<?= $row['u_password']?>" class="textinput" />（3～16位）
	  	</p>
	
	
		<p>
		<label for="u_password">密码确认：</label>
        <input type="password" dataType="Repeat" to="u_password" msg="两次输入的密码不一致" value="<?= $row['u_password']?>" class="textinput" />
		</p>
		
        <p id="uboxstyle">
            <span style="float:left; margin-right:8px;">
            <label for="u_gender">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</label>
            </span>
	        <select name="u_gender">
	        <option value="男" <?php if($row['u_gender']=='男'){echo 'selected';}?>>男</option>
	        <option value="女" <?php if($row['u_gender']=='女'){echo 'selected';}?>>女</option>
	        </select>
	    </p>

		<!----添加字段的显示--->
		<p>
		<label for="u_birthday">出生年月：</label>
        <input name="u_birthday" type="text" value="<?= $row['u_birthday']?>" class="textinput" />(格式为2009年9月)
		</p>
		<p>
		<label for="u_duty">职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;务：</label>
        <input name="u_duty" type="text" value="<?= $row['u_duty']?>" class="textinput" />
		</p>
		<p>
		<label for="u_dutyname">职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</label>
        <input name="u_dutyname" type="text" value="<?= $row['u_dutyname']?>" class="textinput" />
		</p>
		<p>
		<label for="u_dutytime">职称评定时间：</label>
        <input name="u_dutytime" type="text" value="<?= $row['u_dutytime']?>" class="textinput" />(格式为2009年9月)
		</p>
		<p>
		<label for="u_xueli">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历：</label>
        <input name="u_xueli" type="text" value="<?= $row['u_xueli']?>" class="textinput" />
		</p>
		<p>
		<label for="u_degree">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：</label>
        <input name="u_degree" type="text" value="<?= $row['u_degree']?>" class="textinput" />
		</p>
		<p>
		<label for="u_graduate">毕业院校：</label>
        <input name="u_graduate" type="text" value="<?= $row['u_graduate']?>" class="textinput" />
		</p>
		<p>
		<label for="u_speciality">所学专业：</label>
        <input name="u_speciality" type="text" value="<?= $row['u_speciality']?>" class="textinput" />
		</p>
		<p>
		<label for="u_graduatetime">毕业时间：</label>
        <input name="u_graduatetime" type="text" value="<?= $row['u_graduatetime']?>" class="textinput" />(格式为2009年9月)
		</p>
		<p>
		<label for="u_worktime">参加工作时间：</label>
        <input name="u_worktime" type="text" value="<?= $row['u_worktime']?>" class="textinput" />(格式为2009年9月)
		</p>
		<p>
		<label for="u_seminarytime">理工学院工作时间：</label>
        <input name="u_seminarytime" type="text" value="<?= $row['u_seminarytime']?>" class="textinput" />(格式为2009年9月)
		<p>
		<label for="u_workage">教&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;龄：</label>
        <input name="u_workage" type="text" value="<?= $row['u_workage']?>" class="textinput" />
		</p>
		<p>
		<label for="u_appoint">任用情况：</label>
        <input name="u_appoint" type="text" value="<?= $row['u_appoint']?>" class="textinput" />
		</p>
		</p>
		<!---添加字段的显示---->

		<p>
		<label for="u_dept">所在部门：</label>
        <input name="u_dept" type="text" value="<?= $row['u_dept']?>" class="textinput" />
		</p>
	 
	    
		<p>
		<label for="u_cellphone">手机号码：</label>
        <input name="u_cellphone" type="text" value="<?= $row['u_cellphone']?>" dataType="Mobile"  msg="手机号码格式填写不正确" />
		</p>
	    
	    <p>
		<label for="u_email">电子邮箱：</label>
        <input name="u_email" type="text" dataType="Email" msg="邮箱格式不正确" value="<?= $row['u_email']?>" class="textinput" />
		</p>
        
		<p>
		<label for="u_otherphone">其它联系方式：</label>
        <input name="u_otherphone" type="text" value="<?= $row['u_otherphone']?>" class="textinput" />
		</p>
	
	</fieldset><!--表单内边框DIV_END-->
	    <p>
	        <input name="btnSubmit" type="submit" value="修改" class="buttonSubmit"/>
	    </p>
	</form>

</div><!--表单外边框DIV_END-->


</body>
</html>