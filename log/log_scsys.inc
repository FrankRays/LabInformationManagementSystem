<?php

$l_action="删除了实验信息：id=".$l_id;
$l_ip=$_SERVER["REMOTE_ADDR"]; //获取客户端IP地址
$l_time=date("Y-m-d  H:i:s");
$log_="insert into log (l_rname, l_action, l_time, l_ip) 
      values ('$_SESSION[u_name]', '$l_action', '$l_time', '$l_ip')";
$ok=mysql_query( $log_ );
?>

