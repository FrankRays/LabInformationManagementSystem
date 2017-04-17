<?php
include("../common/session.inc");//获取管理权限
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="javascript">
 <!--
//未知的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<title>备份用户信息表</title>
</head>
<body>
&nbsp;&nbsp;
<a href="#" onclick="method1('content');">= 导出到Excel =</a>
<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><strong>用户信息表</strong></p>

<table id="content" width="90%" border="2" cellspacing="0" cellpadding="0" bordercolor="#000000">

  <tr align="center">
    <th>序号</th>
    <th>用户姓名</th>
    <th>登录id</th>
    <th>职务（权限）</th>
    <th>性别</th>
	<th>出生年月</th>
	<th>职务</th>
	<th>职称</th>
	<th>职称评定时间</th>
	<th>学历</th>
	<th>学位</th>
	<th>毕业院校</th>
	<th>所学专业</th>
	<th>毕业时间</th>
	<th>参加工作时间</th>
	<th>理工工作时间</th>
	<th>教龄</th>
	<th>任用情况</th>
    <th>所在部门</th>
    <th>手机</th>
    <th>其它号码</th>
    <th>邮箱</th>
  </tr>
  <?php
  //$n代表权限
  $sql="select * from user WHERE u_type<=$n";
  $result=mysql_query( $sql );
  $num_rows=mysql_num_rows( $result );
  
  if($num_rows<1) 
     echo '<tr align=center><td height=25>---</td>
	                        <td>---</td>
							<td>---</td>
							<td>---</td>
							<td>---</td>
						    <td>没有找到相关资料</td>
						    <td>---</td>
							<td>---</td>
						    <td>---</td>
						    </tr>'; 
   else
   {  $j=0;
      for($i=0; $i<$num_rows; $i++)
       {  $array=mysql_fetch_array( $result ); $j++;
     
  ?>
  <tr align="center">
    <td height="25" ><?php echo $j; ?></td>
    <td>&nbsp;<?php echo $array["u_name"]; ?></td>
    <td>&nbsp;<?php echo $array["u_password"]; //需要改为password?></td>
    <td>&nbsp;<?php if($array["u_type"]!='4')//1是老师，2是负责人，3是实验室主任，4是管理员
                     {
						 if($array["u_type"]!='3')
						 {
							 if($array["u_type"]!='2')                        
							 {  
								 if($array["u_type"]!='5')
	                            { 
								 if($array["u_type"]!='1') 
									 echo "---";
								 else 
									 echo"老师";
								 }
								 else
									 echo "超级管理员";
		                      
	                         }
	                         else echo "实验室管理员";
						 }
						 else echo "实验室主任";
                      } 
                    else echo "系统管理员";  ?></td>
    <td><?php echo $array["u_gender"]; ?></td>
	<td><?php echo $array["u_birthday"]; ?></td>
	<td><?php echo $array["u_duty"]; ?></td>
	<td><?php echo $array["u_dutyname"]; ?></td>
	<td><?php echo $array["u_dutytime"]; ?></td>
	<td><?php echo $array["u_xueli"]; ?></td>
	<td><?php echo $array["u_degree"]; ?></td>
	<td><?php echo $array["u_graduate"]; ?></td>
	<td><?php echo $array["u_speciality"]; ?></td>
	<td><?php echo $array["u_graduatetime"]; ?></td>
	<td><?php echo $array["u_worktime"]; ?></td>
	<td><?php echo $array["u_seminarytime"]; ?></td>
	<td><?php echo $array["u_workage"]; ?></td>
	<td><?php echo $array["u_appoint"]; ?></td>
    <td><?php echo $array["u_dept"]; ?></td>
    <td><?php if($array["u_cellphone"]=="")  echo "---"; else echo $array["u_cellphone"]; ?></td>
	<td><?php if($array["u_otherphone"]=="")  echo "---"; else echo $array["u_otherphone"]; ?></td>
    <td><?php if($array["u_email"]=="")  echo "---"; else echo $array["u_email"]; ?></td>
  </tr>
<?php   }
    }
?>
</table>
<p>
  <?php 
  mysql_close( $conn );
  include("excel.inc");?>
</p>
<p>&nbsp;</p>
</body>
</html>
