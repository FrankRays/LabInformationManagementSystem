<?php
include("../common/session.inc");
include("../common/db_conn.inc");
include("excel.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/tablecloth_forBigTable.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->

<title>实验基本信息</title>

</head>
<body>
	<form name="form2" method="post" action="room.php">
		请先输入查询条件：<select name="search_condition" size="1">
		<option selected="selected" value="r_name">实验室名称</option>
		<option value="r_admin">负责人</option>
		<option value="r_number">房间号</option>
		</select>
		<input name="search_content" type="text" id="search_content" />
		<input name="submit" type="submit" id="submit" value="搜索"/>
	</form>
&nbsp;&nbsp;
<a href="r_add.php" >= 添加记录 =</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" onclick="method1('content');">= 导出到Excel =</a>
<div align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><strong>实验室基本信息</strong></div>

<table id="content" width="100%" border="2" align="center" cellpadding="0" cellspacing="0" style=" border-color:#000000">
  <tr align="center">
    <th>序号</th>
    <th>实验室名称</th>
    <th>负责人</th>
    <th>房间号</th>
    <th>电脑数</th>
    <th>实验箱</th>
    <th>容纳人数</th>
    <th>状态</th>
    <th>操作1</th>
	<th>操作2</th>
  </tr>
<?php
   $page=$_GET["page"];  //获取页码超链接的页码
   if($page=="") $page=$_POST["page"]; //获取表单提交的页码
   if($page=="") $page=1;
   
   
   $page_size=10;  //每页显示记录数
   if($_POST["search_content"])
   {
	    $search_condition = $_POST["search_condition"];
		$search_content = $_POST["search_content"];	   
      	$sql="select r_id from room where {$search_condition}='{$search_content}'";
		//echo $sql;
		//sql
	   $result=mysql_query( $sql )or die ( mysql_error() );
	   $num_rows=mysql_num_rows( $result ); //获得记录总行数，即记录数
	   $page_sum=ceil( $num_rows/$page_size ); //得出总页数
	   $offset=($page-1)*$page_size;  //此变量作为指针作用
	   
	   //sql2
	   $sql2="select * from room where {$search_condition}='{$search_content}' order by r_id asc limit $offset, $page_size";
	   $result2=mysql_query( $sql2 );
   }
   else
   {
	   $sql="select r_id from room";
	   //sql
	   $result=mysql_query( $sql )or die ( mysql_error() );
	   $num_rows=mysql_num_rows( $result ); //获得记录总行数，即记录数
	   $page_sum=ceil( $num_rows/$page_size ); //得出总页数
	   $offset=($page-1)*$page_size;  //此变量作为指针作用
	   
	   //sql2
	   $sql2="select * from room order by r_id asc limit $offset, $page_size";
	   $result2=mysql_query( $sql2 );
   }
   
   
   
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
						    <td>---</td></tr>'; 
					
   else
   {
      if($page==1) $j=0;
      else $j=$offset;
	
      for($i=0; $i<$page_size; $i++)
      {  
        $array=mysql_fetch_array( $result2 ); $j++;
        
		//--------判断第2次执行循环记录是否为空,如果为空则跳出循环  -----------------	
		if($array["r_id"]=="")  break;
		else
		  {
	  
?>
  <tr>
    <td height="25" align="center"><?php if($array["r_id"]=="") echo "---"; else echo $j; ?></td>
    <td>&nbsp;<?php if($array["r_name"]=="") echo "---"; else echo $array["r_name"]; ?></td>
    <td>&nbsp;<?php if($array["r_admin"]=="") echo "---"; else echo $array["r_admin"]; ?></td>
    <td>&nbsp;<?php if($array["r_number"]=="") echo "---"; else echo $array["r_number"]; ?></td>
    <td>&nbsp;<?php if($array["r_pcnum"]=="") echo "---"; else echo $array["r_pcnum"]; ?></td>
    <td>&nbsp;<?php if($array["r_embnum"]=="") echo "---"; else echo $array["r_embnum"]; ?></td>
    <td>&nbsp;<?php if($array["r_capacity"]=="") echo "---"; else echo $array["r_capacity"]; ?></td>
    <td>&nbsp;<?php if($array["r_state"]=="") echo "---"; else echo $array["r_state"]; ?></td>
    <td align="center"><?php if($array["r_id"]=="") echo "---"; 
	                         else echo '<a href=r_edit.php?r_id='.$array["r_id"].'>修改</a>';?></td>
	<td align="center"><?php if($array["r_id"]=="") echo "---"; 
	                         else echo '<a href=r_del.php?r_id='.$array["r_id"].'>删除</a>';?></td>
  </tr> 
<?php     }
       }
    }

?>
</table>
<br />
<form name="form1" id="form1" method="post" action="room.php" onsubmit="return chk(this)">
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left">共：<?php echo $num_rows; ?> 条记录，目前第：<?php echo $page."／".$page_sum;?> 页。</td>
    <td align="left"><?php if($page!=1)
	            echo '<a href=room.php?page=1>首页</a>&nbsp;&nbsp;
				      <a href=room.php?page='.($page-1).'>上一页</a>&nbsp;&nbsp;';
				if($page<$page_sum)
				  {  echo'<a href=room.php?page='.($page+1).'>下一页</a>&nbsp;&nbsp;
				          <a href=room.php?page='.$page_sum.'>尾页</a>';
				  }
				 ?>&nbsp;&nbsp;
	  <input name="Submit" type="button" value="转到">
	第<input name="page" type="text" size="2"  <?php echo 'maxlength='.strlen($page_sum);?> > 页
	<!--<input name="pages" value="<?php //echo $page_sum;?>"> -->	</td>
  </tr>
</table>
</form>

<script language="javascript">
function chk(form)  //---------isNaN是javascript的内置函数，判断变量的类型，是数字返回true,否则返回false
{ if(form.page.value<=0 || form.page.value>form.pages.value  || isNaN(form.page.value) )
   {  alert("你输入的页码无效！");
      form.page.focus();
	  return(false);
    }
	return(true);
}
</script>

<?php mysql_close( $conn );?>
</body>
</html>
