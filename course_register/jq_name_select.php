<?php
	include("../common/db_conn.inc");
	$queryString = $_POST['queryString'];
	$query = mysql_query("SELECT c_cname FROM v_cname WHERE c_cname LIKE '$queryString%' ");
  	while( $result = mysql_fetch_object($query) ){
		echo '<li onClick="fill(\''.$result->c_cname.'\');">'.$result->c_cname.'</li>';
	}
?>