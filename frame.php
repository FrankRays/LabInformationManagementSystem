<?php
include("./common/session.inc");
$usercode = $_SESSION["u_type"];//获取权限代码

include("./common/db_conn.inc");
include("./common/publish_state.php");

?>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>实验课程安排系统</title>
</head>
<frameset name="container" rows="80,*" cols="*" frameborder="yes" border="1" FrameSpacing=2 BorderColor="#000000"><!--#286bab-->

	<frame name="top" src="top.php?id=<?=$_GET[location]?> " scrolling="No" noresize="noresize" id="topFrame" title="topFrame" frameborder="no"/>

	<frameset name="main" cols="175,*" frameborder="yes" border="1" FrameSpacing=2 BorderColor="#000000" >
		<frame name="left" cols="50,*" 
		<?php 
		echo'src="left.php"';
		?>
		 scrolling="auto" frameborder="no" noresize="noresize">
		<frame name="right" 
		<?php 
			//if($publish_state=='1' || $usercode=='3'||$usercode=='4') //将管理员的权限代码改为4、5
			if($publish_state=='1' || $usercode=='4' || $usercode=='5')
			echo 'src="stat_ana/arrange_result_week.php"';
				else echo 'src="stat_ana/arrange_result_week_empty.php"';			
		?> 
		scrolling="auto" frameborder="no">
	  <noframes>
	  <body>
	  <p>此网页使用了框架，但您的浏览器不支持框架。</p>
	  </body>
	  </noframes>
	</frameset>

</frameset>
</html>