<?php
	include("../common/db_conn.inc");
	$queryString = $_POST['queryString'];
	$query = mysql_query("SELECT u_name FROM v_rname WHERE u_name LIKE '$queryString%' ");
  	while( $result = mysql_fetch_object($query) ){
		echo '<li onClick="fill(\''.$result->u_name.'\');">'.$result->u_name.'</li>';
	}
?>