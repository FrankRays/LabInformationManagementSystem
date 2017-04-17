<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<script language="javascript" type="text/javascript" src="../js/select2css.js"></script><!--表单下拉列表美化JS-->
<link rel="stylesheet" type="text/css" href="../css/select2css.css"><!--表单下拉列表美化CSS-->

<title>无标题文档</title>
<LINK rel="stylesheet" href="sty.css" type="text/css" />
</head>
<body>
<div align="center">
<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单内边框DIV_BEGIN-->
        <legend>添加课程信息</legend><!--表单内边框标题-->

<form name="form1" id="form1" method="post" action="c_add_do.php" >
<table width="546" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:12px">
  
  <tr>
    <td width="120" height="25" align="right">课程名称：</td>
    <td width="420"><input name="c_cname" type="text" id="c_cname" size="60" /></td>
  </tr>
  <tr>
    <td height="25" align="right">课程方向：</td>
    <td><input name="c_direction" type="text" id="c_direction" size="60" /></td>
  </tr>
  <tr>
    <td height="25" align="right">适用专业：</td>
    <td><input name="c_major" type="text" id="c_major" size="60" /></td>
  </tr>
  <tr>
    <td height="25" align="right">实验室方向：</td>
    <td><input name="c_room" type="text" id="c_room" size="60" /></td>
  </tr>
</table>
<p align="center"><input name="Submit" id="Submit" type="submit" value="提交" />&nbsp;&nbsp;&nbsp;&nbsp;
<input name="Reset" id="Reset" type="reset" value="重置" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input name="back" id="back" type="button" value="取消" onClick="javascript:location='course.php'" /></p>

</form>

	</fieldset><!--表单内边框DIV_END-->
    <br />
</div><!--表单外边框DIV_END-->
</div><!--居中DIV-->

</body>
</html>
