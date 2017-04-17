<?php

$l_action="修改了实验室信息：id=".$r_id;
$l_ip=$_SERVER["REMOTE_ADDR"]; //获取客户端IP地址
$l_time=date("Y-m-d  H:i:s");
$log_="insert into log (l_rname, l_action, l_time, l_ip) 
      values ('$_SESSION[u_name]', '$l_action', '$l_time', '$l_ip')";
$ok=mysql_query( $log_ );
?>

