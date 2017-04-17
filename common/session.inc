<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$n=-1;
@session_start();
if($_SESSION["u_type"]!='6')
{
if($_SESSION["u_type"]!='5')
{
if($_SESSION["u_type"]!='4')
{  
   if($_SESSION["u_type"]!='3')
     {  
     if($_SESSION["u_type"]!='2')

	      {
	      if($_SESSION["u_type"]!='1')
	      {
		echo "<script language='javascript'>alert('你无权进行操作，请登陆后再进行操作！');
				     location.href='index.html';</script>";
	      }
	      else $n=1;
	      }
	      else $n=2;
	   }
	  else $n=3;
} 
else $n=4; 
}
else $n=5; 
}
?>