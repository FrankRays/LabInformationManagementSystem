<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<title>课程方向分类</title>

</head>
<body>
<form name="sc" method="post" action="sc.php">
&nbsp;&nbsp;课程名称：<input name="kw" type="text" size="45" />
  <input name="sb" type="submit" value=" 搜 索 " >
</form>
&nbsp;&nbsp;<a href="c_add.php" class="STYLE1">= 添加记录 =</a>
<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><strong>课程方向分类</strong>（搜索结果）</p>

<table width="100%" border="2" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr align="center">
    <th>序号</th>
    <th>课程名称</th>
    <th>课程类别</th>
    <th>适用专业</th>
    <th>实验室方向</th>
    <th>操作1</th>
	<th>操作2</th>
  </tr>
<?php
   $kw=@$_POST["kw"];
   if($kw=="")
     $kw=@$_GET["kw"];
   
   
   $sql="select * from course where c_cname like '%$kw%'";
   $result=mysql_query( $sql );
   $num_rows=mysql_num_rows( $result ); //获得记录总行数，即记录数
   
   
   
   
   if($num_rows<1) 
     echo '<tr align=center><td height=25>---</td>
	                        <td>---</td>
							<td>---</td>
						    <td>没有找到相关资料</td>
						    <td>---</td>
						    <td>---</td>
						    <td>---</td></tr>'; 
					
   else
   {
      $j=0;
      
      for($i=0; $i<$num_rows; $i++)
      {  
        $array=mysql_fetch_array( $result ); $j++;
        
		//--------判断第2次执行循环记录是否为空,如果为空则跳出循环  -----------------	
		if($array["c_id"]=="")  break;
		else
		  {
	  
?>
  <tr>
    <td height="25" align="center"><?php if($array["c_id"]=="") echo "---"; else echo $j; ?></td>
    <td>&nbsp;<?php if($array["c_cname"]=="") echo "---"; else echo $array["c_cname"]; ?></td>
    <td>&nbsp;<?php if($array["c_direction"]=="") echo "---"; else echo $array["c_direction"]; ?></td>
    <td>&nbsp;<?php if($array["c_major"]=="") echo "---"; else echo $array["c_major"]; ?></td>
    <td>&nbsp;<?php if($array["c_room"]=="") echo "---"; else echo $array["c_room"]; ?></td>
    <td align="center"><?php if($array["c_id"]=="") echo "---"; 
	                         else echo '<a href=c_edit2.php?c_id='.$array["c_id"].'&kw='.urlencode($kw).'>修改</a>';?></td>
	<td align="center"><?php if($array["c_id"]=="") echo "---"; 
	                         else echo '<a href=c_del.php?c_id='.$array["c_id"].'>删除</a>';?></td>
  </tr> 
<?php     }
       }
    }

?>
</table>
<br />
<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><input name="back" id="back" type="button" value=" 返 回 " onClick="javascript:location='course.php'" /></p></p>



<?php mysql_close( $conn );?>
</body>
</html>
