<?php
/**
*
*打印教师名字为$teachername,课程名称为$cname,年级为$grade,专业为$major,班级不为$class的课程信息
*
*/
function displayAllOtherClass($teachername, $cname, $grade, $major, $class, $valid_time_range_begin_date, $valid_time_range_end_date){
	$sql_edit1 = "SELECT * FROM `apply1` where a_rname='{$teachername}' AND a_cname='{$cname}' AND a_grade='{$grade}' AND a_major='${major}' AND a_class!='${class}' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}' GROUP BY a_class";
	$rs_edit = mysql_query ( $sql_edit1 ) or die ( "不能查询指定id：" . mysql_error() );
	$row_num = mysql_num_rows($rs_edit); //影响数目
	
	for($i=0;$i<$row_num;$i++){
		$row = mysql_fetch_array( $rs_edit );
		display($teachername, $cname, $grade, $major, $row[11], $valid_time_range_begin_date, $valid_time_range_end_date);
	}
}
/**
*
*打印教师名字为$teachername,课程名称为$cname,年级为$grade,专业为$major,班级为$class的课程信息
*
*/
function display($teachername, $cname, $grade, $major, $class, $valid_time_range_begin_date, $valid_time_range_end_date) {

	$sql_edit = "SELECT a_cname AS 课程名称, a_rname AS 教师名称, a_sid AS 实验编号,a_sname AS 实验项目名称, a_id AS 操作1, a_grade AS 年级, a_major AS 专业, a_class AS 班级, a_id FROM `apply1` where a_rname='{$teachername}' AND a_cname='{$cname}' AND a_grade='{$grade}' AND a_major='${major}' AND a_class='${class}' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
	
	$rs_edit = mysql_query ( $sql_edit ) or die ( "不能查询指定id：" . mysql_error() );
	$row_num = mysql_num_rows($rs_edit); //影响数目
	$row = mysql_fetch_array( $rs_edit );
	$row_aid = $row[8];
	echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
	//echo "<caption>"</caption>\n";
	echo "<tr>\n";
	echo "<th>课程名称</th>";
	echo "<th>老师名称</th>";
	echo "<th>班级</th>";
	echo "<th>实验编号</th>";
	echo "<th>实验项目</th>";
	echo "<th>周次</th>";
	echo "<th>星期</th>";
	echo "<th>节次</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>\n";
		//$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[2],$row[4]);
	for($i=0;$i<$row_num;$i++){

		
		$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[2],$row[4]);
		$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
		$row_num_tid = mysql_num_rows($result_tid);       //获取影响行的数目
		$row_tid = mysql_fetch_array($result_tid);
		

		for($n=0;$n<$row_num_tid;$n++)//应该用时间段的行数
		{
			echo "<tr>";
		    echo "<td align=\"center\" id=\"id0\" >".$row[0]."</td>";//课程名字
		    echo "<td align=\"center\" id=\"id1\" >".$row[1]."</td>";//老师名称
			echo "<td align=\"center\" id=\"id1\" >".$row[5].$row[6].$row[7]."</td>";//班级
			echo "<td align=\"center\" id=\"id2\" >".$row[2]."</td>";//实验编号
			echo "<td align=\"center\" id=\"id3\" >".$row[3]."</td>";//实验名称
			//时间段
			echo "<td align=\"center\" id=\"id4\">".$row_tid[0]."</td>";//周次
			echo "<td align=\"center\" id=\"id5\">".$row_tid[1]."</td>";//星期
			echo "<td align=\"center\" id=\"id6\">".$row_tid[2]."</td>";//节次

			//单独添加"修改链接"	
			//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
			//$row[4]表示登记表的编号
			$search_condition = "a_rname";
			$search_content = $teachername;
			echo "<td align=\"center\" id=\"id7\">
		    <img src=\"../images/edit.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_edit_single_add.php?a_id=$row[4]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='修改'/>
			</td>\n";
			//增加删除操作2
			echo "<td align=\"center\" id=\"id8\">
	        <img src=\"../images/delete.gif\"  style=\"cursor:hand;\" onClick='javascript:if(confirm(\"您确实要删除该记录吗？\"))location=\"course_register_delete_result.php?a_id=$row[4]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='删除'/>
		    </td>\n";
			echo "</tr>\n";
			$row_tid = mysql_fetch_array($result_tid);

		}
		$row = mysql_fetch_array( $rs_edit );//获取相同课程的a_id
	}
	echo "</table>";
	
	
	
	echo "<img src=\"../images/add_this_course_another.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_add_continue.php?a_id=$row_aid\"'/>";
	
	echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<img src=\"../images/end_add_course.gif\" target='_parent'  style=\"cursor:hand;\" onClick='location.href=\"../frame.php\"'/>";
	//echo "<a href='../frame.php' target='_parent' style='margin-left: 20px;padding: 0 11px;background: #02bafa;border: 1px #26bbdb solid;border-radius: 3px;display: inline-block;text-decoration: none;font-size: 12px;outline: none;'>结束填写课程信息</a>";
	
	echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	
	echo "<img src='../images/add_another_course.gif'  style='cursor:hand;' onClick='location.href=\"course_register.php\"'/><br /><br />";
	
}
?>