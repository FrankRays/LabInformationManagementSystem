<?php
include("../common/session.inc");
include("../common/db_conn.inc");
include("excel.inc");
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
<link rel="stylesheet"  href="../css/tablecloth_forBigTable.css" type="text/css" media="screen" /><!--表格美化CSS-->
<script type="text/javascript" src="../js/tablecloth.js"></script><!--表格美化JS-->
<title>日志查看</title>
<!--全选功能 -->
<script language=javascript>
function unselectall(){
if(document.myform.chkAll.checked){
document.myform.chkAll.checked = document.myform.chkAll.checked&0;
}
}
function CheckAll(form){
for (var i=0;i<form.elements.length;i++){
var e = form.elements[i];
if (e.Name != 'chkAll'&&e.disabled==false)
e.checked = form.chkAll.checked;
}
}
function check_more(form)
{
	var check=document.getElementsByName("id[]");  
	var a=false;
	for(var i=0;i <check.length;i++){  

	if(check[i].checked==false)
	{  
		continue;  
	}  
	else//有一个选上的话就可以删除
		{  
			return confirm('删除将不能恢复！你确定删除所选？');
		}  
	}
	alert("请先择你要删除的内容！");  
	return false;  

	
}
</script>
<!--全选功能 -->
</head>

<body>
&nbsp;&nbsp;
<a href="#" onclick="method1('content');">= 导出到Excel =</a>
<p align="center" style="font-size:14px; color:#1E7ACE; font-weight:bold;"><strong>日志信息</strong></p>
<form  name="myform" method="post" id="myform" action="more.php" onsubmit="return check_more(this)">
<table id="content" width="100%" border="2" cellspacing="0" cellpadding="0" bordercolor="#000000">

  <tr align="center">
  	
    <th>序号</th>
    <th>用户姓名</th>
    <th>操作内容</th>
    <th>操作时间</th>
    <th>IP地址</th>
	<th>操作</th>
	<th>批量删除</th>
  </tr>
<?php
   $page=$_GET["page"];  //获取页码超链接的页码
   if($page=="") $page=$_POST["page"]; //获取表单提交的页码
   if($page=="") $page=1;
   
   $page_size=20;  //每页显示记录数
   $sql="select l_id from log";
   $result=mysql_query( $sql );
   $num_rows=mysql_num_rows( $result ); //获得记录总行数，即记录数
   $page_sum=ceil( $num_rows/$page_size ); //得出总页数
   
    if($page>$page_sum)
   	  	$page=$page_sum;
	if($page<0)
		$page=1;
	
   
   $offset=($page-1)*$page_size;  //此变量作为指针作用
   
   $sql2="select * from log order by l_id DESC limit $offset, $page_size";//desc表示降序查询（ASC表示按升序排序,DESC表示按降序排序 ）
   $result2=mysql_query($sql2 );
  
   if($num_rows<1) 
     echo '<tr align=center><td height=25>---</td>
	                        <td>---</td>
							<td>---</td>
						    <td>没有找到相关资料</td>
						    <td>---</td>
							<td>---</td>
						    </tr>'; 
					
    else
    {
      if($page==1) $j=0;
      else $j=$offset;
	
      for($i=0; $i<$page_size; $i++)
       {  
         $array=mysql_fetch_array( $result2 ); $j++;
        
		//--------判断第2次执行循环记录是否为空,如果为空则跳出循环  -----------------	
		 if($array["l_id"]=="")  break;
		 else
		   {
?>
  <tr>
  	
    <td height="25" align="center" ><?php echo $j; ?></td>
    <td align="center"><?php echo $array["l_rname"]; ?></td>
    <td>&nbsp;<?php echo $array["l_action"]; ?></td>
    <td align="center"><?php echo $array["l_time"]; ?></td>
    <td align="center"><?php echo $array["l_ip"]; ?></td>
	<td align="center">
	<?php echo '<a href=log_del.php?l_id='.$array["l_id"].'&page='.$page.'>删除</a>';?>
	</td>
	<td><input name='id[]' type='checkbox' onclick='unselectall()' id='id' value='<?php echo $array["l_id"]?>'></td>
  </tr>
<?php      } 
        }
	}
?>
</table>
<input name="page" type="hidden" value="<?php echo $page?>" />
<div align="right"><input name='chkAll' type='checkbox' id='chkAll' onclick='CheckAll(this.form)' value='checkbox' />全选/全不选&nbsp;&nbsp;
<input name="delect" type="submit" id="delect"  value="删除" /></div>
</form>
<!--全选的表单 -->
<br />
<form name="form1" id="form1" method="post" action="log.php" onsubmit="return chk(this)">
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left">共：<?php echo $num_rows; ?> 条记录，目前第：<?php echo $page."／".$page_sum;?> 页。</td>
    <td align="left"><?php if($page!=1)
	            echo '<a href=log.php?page=1>首页</a>&nbsp;&nbsp;
				      <a href=log.php?page='.($page-1).'>上一页</a>&nbsp;&nbsp;';
				if($page<$page_sum)
				  {  echo'<a href=log.php?page='.($page+1).'>下一页</a>&nbsp;&nbsp;
				          <a href=log.php?page='.$page_sum.'>尾页</a>';
				  }
				 ?>&nbsp;&nbsp;
	  <input name="Submit" type="submit" value="转到" style="cursor:pointer;">
	第<input name="page" type="text" size="3" <?php echo 'maxlength='.strlen($page_sum);?> > 
	页
	<input name="pages" type="hidden" value="<?php echo $page_sum;?>" >	</td>
	
  </tr>
</table>
</form>

<script language="javascript">
function chk(form)  //---------isNaN是javascript的内置函数，判断变量的类型，是数字返回true,否则返回false
{ 
	if(form.page.value<=0 || form.page.value>form.pages.value  || isNaN(form.page.value) )
    {  
		alert("你输入的页码无效！");
        form.page.focus();
	    return(false);
    }
	return(true);
}
</script>

<?php mysql_close( $conn );?>
</body>
</html>
