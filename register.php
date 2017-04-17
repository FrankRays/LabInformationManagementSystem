<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<script language="javascript" type="text/javascript" src="../js/select2css.js"></script><!--表单下拉列表美化JS-->
<link rel="stylesheet" type="text/css" href="../css/select2css.css"><!--表单下拉列表美化CSS-->

<title>用户账号申请</title>
</head>
<body>

<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单内边框DIV_BEGIN-->
        <legend>请填写以下信息</legend><!--表单内边框标题-->
        
        
        <form action="register_result.php" method="post" onSubmit="return Validator.Validate(this,3)">
 
            <p>
            <label for="u_name">用户姓名：</label>
            <input name="u_name" type="text" dataType="Require" msg="用户姓名不能为空" value="" class="textinput" />
            </p>
            
            <p>
            <label for="u_lname">登&nbsp;&nbsp;录&nbsp;i&nbsp;d：&nbsp;&nbsp;</label>
            <input name="u_lname" type="text" dataType="Require" msg="用户登录id不能为空" value="" class="textinput"/>
            
            <p>
            <label for="u_password">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
            <input name="u_password" type="text" dataType="Require" msg="密码不能为空" value="" class="textinput" />
            </p>
            </p>
        <!--
            <p>
            密码：<input name="u_password" type="password" dataType="Limit" min="6" max="16"  msg="密码必须在6-16位之间">（6～16位）
            </p>
        
        
            <p>
            密码确认:<input type="password" dataType="Repeat" to="u_password" msg="两次输入的密码不一致" >
            </p>
        -->

            <p id="uboxstyle">
            	<span style="float:left; margin-right:8px;">
            	<label for="u_type">用户类型：</label>
                </span>
                <select name="u_type">
                <option value="1" selected>普通教师</option>
                <option value="2">实验室负责人</option>
                <option value="3">实验室主任</option>
				<option value="4">管理员</option>
				<?php
				if($n>4)
				{
				?>
				<option value="5">系统管理员</option>
				<?php
				}
				?>
                </select>
            </p>
            
            <p id="uboxstyle">
            	<span style="float:left; margin-right:8px;">
                <label for="u_gender">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</label>
                </span>
                <select name="u_gender">
                    <option value="男" selected>男</option>
                    <option value="女">女</option>
                </select>
         	</p>
            <!--增加一个u_birthday出生年月begin-->
			<!--php代码写年月-->
			<p id="uboxstyle">
			  <span style="float:left; margin-right:8px;">
               <label for="u_birthday">出生年月：</label>
			  </span>
			  <input name="u_year" type="text"  dataType="Require" msg="不能为空" value="" size="8"/> 年
			  <input name="u_match" type="text"  value="" size="8"/> 月
			  <!--
               <select name="u_match">
			     <option value="1" selected>1</option>
			     <option value="2">2</option>
			     <option value="3">3</option>
			     <option value="4">4</option>
			     <option value="5">5</option>
			     <option value="6">6</option>
			     <option value="7">7</option>
			     <option value="8">8</option>
			     <option value="9">9</option>
			     <option value="10">10</option>
			     <option value="11">11</option>
			     <option value="12">12</option>
               </select>
            </p>-->
			<!--增加一个出生年月end-->
			<!---增加的字段-->
			<p>
            <label for="u_duty">职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;务：</label>
            <input name="u_duty" type="text" value="" dataType="Require" msg="不能为空"   class="textinput"/>
            </p>
			<p>
            <label for="u_dutyname">职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</label>
            <input name="u_dutyname" type="text" value="" dataType="Require" msg="不能为空"   class="textinput"/>
            </p>
			<p>
            <label for="u_dutytime">职称评定时间：</label>
            <input name="u_dutytime" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>(格式为2009年9月)
            </p>
			<p>
            <label for="u_xueli">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历：</label>
            <input name="u_xueli" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>
            </p>
			<p>
            <label for="u_degree">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：</label>
            <input name="u_degree" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>
            </p>
			<p>
            <label for="u_graduate">毕业院校：</label>
            <input name="u_graduate" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>
            </p>
			<p>
            <label for="u_speciality">所学专业：</label>
            <input name="u_speciality" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>
            </p>
			<p>
            <label for="u_graduatetime">毕业时间：</label>
            <input name="u_graduatetime" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>(格式为2009年9月)
            </p>
			<p>
            <label for="u_worktime">参加工作时间：</label>
            <input name="u_worktime" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>(格式为2009年9月)
            </p>
			<p>
            <label for="u_seminarytime">理工学院工作时间：</label>
            <input name="u_seminarytime" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>(格式为2009年9月)
            </p>
			<p>
            <label for="u_workage">教&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;龄：</label>
            <input name="u_workage" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>
            </p>
			<p>
            <label for="u_appoint">任用情况：</label>
            <input name="u_appoint" type="text" value=""  dataType="Require" msg="不能为空"  class="textinput"/>
            </p>
			<!----->
            <p>
            <label for="u_dept">所在部门：</label>
            <input name="u_dept" type="text" value="计算机学院" class="textinput"/>
            </p>
         
            
            <p>
            <label for="u_cellphone">手机号码：</label>
            <input name="u_cellphone" type="text" value="" dataType="Mobile"  msg="手机号码格式填写不正确" class="textinput"/>
            </p>
            
            <p>
            <label for="u_email">电子邮箱：</label>
            <input name="u_email" type="text" dataType="Email" msg="邮箱格式不正确" class="textinput"/>
            </p>            
            
            <p>
            <label for="u_otherphone">其它联系方式：</label>
            <input name="u_otherphone" type="text" value="" class="textinput" />
            <br /><br />
            </p>
            
		</fieldset><!--表单内边框DIV_END-->
              
            <p>
            <input name="btnSubmit" type="submit" value="提交" class="buttonSubmit" />
            </p>
            
            <div id="stylesheetTest"></div>
        </form>
    
    
</div><!--表单外边框DIV_END-->




</body>
</html>