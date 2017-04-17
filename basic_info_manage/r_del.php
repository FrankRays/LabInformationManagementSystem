<?php
include("../common/session.inc");
include("../common/db_conn.inc");

$r_id=$_GET["r_id"];
$sql="select * from room where r_id='$r_id' ";
$result=mysql_query( $sql );
$array=mysql_fetch_array( $result );
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
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
-->
</style>
</head>
<body>
<div align="center">
<div id="formwrapper"><!--表单外边框DIV_BEGIN-->
	<fieldset><!--表单内边框DIV_BEGIN-->
        <legend>删除实验室基本信息</legend><!--表单内边框标题-->

<form name="form1" id="form1" method="post" action="r_del_do.php" >
<table width="546" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:12px">
  <tr>
    <td width="120" height="25" align="right">序号：</td>
    <td width="420"><input name="r_id" type="text" id="r_id" size="60" value="<?php echo $r_id;?>" readonly="true" />
	</td>
  </tr>
  <tr>
    <td width="120" height="25" align="right">实验室名称：</td>
    <td width="420"><input name="r_name" type="text" id="r_name" size="60" value="<?php echo $array["r_name"];?>" /></td>
  </tr>
  <tr>
    <td height="25" align="right">负责人：</td>
    <td><input name="r_admin" type="text" id="r_admin" size="60" value="<?php echo $array["r_admin"];?>" /></td>
  </tr>
  <tr>
    <td height="25" align="right">房间号：</td>
    <td><input name="r_number" type="text" id="r_number" size="60" value="<?php echo $array["r_number"];?>" /></td>
  </tr>
  <tr>
    <td height="25" align="right">电脑数：</td>
    <td><input name="r_pcnum" type="text" id="r_pcnum" size="60" value="<?php echo $array["r_pcnum"];?>" /></td>
  </tr>
  <tr>
    <td height="25" align="right">实验箱：</td>
    <td><input name="r_embnum" type="text" id="r_embnum" size="60" value="<?php echo $array["r_embnum"];?>" /></td>
  </tr>
  <tr>
    <td height="25" align="right">容纳人数：</td>
    <td><input name="r_capacity" type="text" id="r_capacity" size="60" value="<?php echo $array["r_capacity"];?>" /></td>
  </tr>
  <tr>
    <td height="25" align="right">状态：</td>
    <td><input name="r_state" type="text" id="r_state" size="60" value="<?php echo $array["r_state"];?>" /></td>
  </tr>
</table>
<p align="center">
  <input name="Submit" id="Submit" type="submit" value="删除" />&nbsp;&nbsp;&nbsp;&nbsp;
  <input name="back" id="back" type="button" value="取消" onClick="javascript:location='room.php'" /></p>
</form>
<?php mysql_close( $conn );?>
	</fieldset><!--表单内边框DIV_END-->
    <br />
</div><!--表单外边框DIV_END-->
</div><!--居中DIV-->
</body>
</html>
