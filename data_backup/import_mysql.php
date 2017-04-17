<?php
include("../common/session.inc");
include("../common/db_conn.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->

<title>导入数据库</title>
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body style="margin-top:100px;">
<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;">导入数据库</p>

<table width="90%" border="2" align="center" cellpadding="10" cellspacing="0"  bordercolor="#000000">
  <tr>
    <td><p align="center"><span class="STYLE1">注意：</span>新导入的数据会把原来的数据覆盖，导入前请务必先导出（备份）数据库！</p>
    <form name="form2" id="form2" method="post" enctype="multipart/form-data" action="import_mysql.php" >
	<p align="center">选择数据库备份文件：<input name="sql_file" type="file" id="sql_file" />
	</p>
	<p align="center"><input name="Submit" id="Submit" type="submit" value="开始导入" /></p>
	</form>
	</td>
  </tr>
</table>
<?php
$start = getMicroTime();
$dir='./'; //存放上传文件目录
if($_POST["Submit"]=="开始导入")
{  
   if(!$_FILES['sql_file']['name']=='')
     { 
	   $newname="up.sql";
	   move_uploaded_file( $_FILES['sql_file']['tmp_name'],$dir.$newname ) or die("上传失败！");
 
////--------------------------------数据库导入部分--------------------------

       $database="lab_test";
       $rs=mysql_list_tables($database);  //把所有的表列出来，返回一个数据集
       $num_rows=mysql_num_rows( $rs ); //统计有几个表

//导入数据前，先把原来的数据清空
       for($i=0; $i<$num_rows; $i++)
         { 
           $seek=mysql_data_seek($rs,$i);  //定位到第i行记录,然后立刻调用 mysql_fetch_row() 函数，返回这一行记录的结果
           $array=mysql_fetch_row( $rs ); //返回这一行的结果(数组)  
           $sql="delete from $array[0]"; //删除该表记录
           $result=mysql_query( $sql );
           //$num_affected=mysql_affected_rows($conn); //统计一次循环删除了几条记录
           //$total+=$num_affected; //统计总共删除几条记录
          }
       //echo "总共删除了： ".$total." 条记录！<br /><br />";


       $filename=$dir.$newname;  //文件路径
       $content=file( $filename ); //把整个文件内容读入到数组变量中
       $rs2=count($content);  //统计数组元素个数

       for($j=0; $j<$rs2; $j++)
          { 
		    //echo $content[$j];
            $result2=mysql_query($content[$j]); //把记录一行一行的插入
            $num_affected2=mysql_affected_rows($conn);
            $total2+=$num_affected2;
          }
	   //echo "<script language=javascript>alert('数据库导入完毕');</script>";
       echo '<p align=center>'."总共导入了： ".$total2." 条记录！".'</p>';
	  unlink( $filename ); //从服务器上删除文件 
     }
}
$end = getMicroTime();
	echo "执行时间为：".getRunTime($start, $end);
	echo "开始时间：".$start;
	echo "结束时间：".$end;

function getMicroTime()
{
    $time = microtime();
    list($msec, $sec) = explode(" ", $time);
    return (float)$sec+(float)$msec;
}

function getRunTime($start, $end)
{
    return $end - $start;
}
mysql_close( $conn );
?>
<p>&nbsp;</p>
</body>
</html>
