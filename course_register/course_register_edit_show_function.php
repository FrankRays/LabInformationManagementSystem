<?php
function show($result, $row_num, $usercode, $search_condition, $search_content){
	
	$row_a_cname = array();
	$row_a_cname_count = 0;
	

	//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
	/*
	for ( $i = 0; $i < $col_num-1; $i++ ) {   
	  $meta = mysql_fetch_field ( $result );  //教师名称和课程名称
	  echo "<th>{$meta->name}</th>\n";
	}*/
	
	$isTheSameTask = '';//2017-04-20判断是否为同一个实验
	
	$row = mysql_fetch_array ( $result ); //取得查询出来的多行结果中的一行(每一个实验)
	
	//2017-3-7不同课程判断条件
	$differentCourse = $row[0].$row[1].$row[7].$row[8].$row[9];
	
	$flag = false;
	$flag1 = false;
	
	$a_rname1=$row[1];//存放上一条记录的教师名称
	$a_cname1=$row[0];//存放上一条记录的课程名称
	$a_major1=$row[8];//存放上一条记录的专业名称
	$a_class1=$row[9];//存放上一条记录的班级名称
	$a_grade1=$row[7];//存放上一条记录的年级名称
	
	for($i=0;$i<$row_num;$i++){
		//2017-04-20判断是否为同一个实验
		if ($isTheSameTask != $row[4].$row[5]) {
				
			$isTheSameTask = $row[4].$row[5];
			//2017-3-7输出空行，以分割不同课程
			if($differentCourse != $row[0].$row[1].$row[7].$row[8].$row[9]){
				$flag = false;
			} 
		
			if(!$flag) {
				if($flag1) {
					echo "</table>\n";
					echo "<input type=\"submit\" name=\"sub\" value=\"批量修改\">";
					echo "</form>";
					
					echo "<form method='post' style='margin-left:80px;margin-top: -20px;' action='course_register_edit_show_add.php'>
					<input name='a_rname' type='hidden' value='${a_rname1}'/>
					<input name='a_cname' type='hidden' value='${a_cname1}'/>
					<input name='a_major' type='hidden' value='${a_major1}'/>
					<input name='a_class' type='hidden' value='${a_class1}'/>
					<input type='submit'  style='cursor:hand;' value='添加该课程的实验项目'>
					</form>";
				
					echo "<hr/><div style='height:40px;'></div>";
					
					$row_a_cname[$row_a_cname_count] = array("${a_rname1}", "${a_cname1}", "${a_major1}", "${a_class1}", "${a_grade1}");
					$row_a_cname_count++;
				
				}
			
				$flag1 = true;
				
				//2017-3-7修改
				echo "<form action=\"course_register_edit_save_allfield.php\" method=\"post\">";
				
				echo "<input type='hidden' name='search_condition' value='${search_condition}' />";
				echo "<input type='hidden' name='search_content' value=${search_content} />";
				
				echo "<table align=\"center\" border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
				//echo "<caption>"</caption>\n";
				echo "<tr>\n";
				echo "<th><input type=\"checkbox\" name=\"choose\" onclick=\"chooseall(this);\">全选/反选</th>";
				echo "<th>课程名称&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_cname\"></th>";
				echo "<th>教师名称&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_rname\"></th>";
				echo "<th>课程类别&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_ctype\"></th>";
				echo "<th>实验教材&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_sbook\"></th>";
				echo "<th>实验编号&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_sid\"></th>";
				echo "<th>实验项目&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_sname\"></th>";
				echo "<th>实验类型&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_stype\"></th>";
				echo "<th>周次&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_sweek\"></th>";
				echo "<th>星期&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_sdate\"></th>";
				echo "<th>节次&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_sclass\"></th>";
				echo "<th>年级&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_grade\"></th>";
				echo "<th>专业&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_major\"></th>";
				echo "<th>班别&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_class\"></th>";
				echo "<th>人数&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_people\"></th>";
				echo "<th>计划学时&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_learntime\"></th>";
				echo "<th>实际学时&nbsp;<!--<input type=\"checkbox\" name=\"field[]\" value=\"a_stime\">--></th>";
				echo "<th>耗材需求&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_resources\"></th>";
				echo "<th>系统需求&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_system\"></th>";
				echo "<th>软件需求&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_software\"></th>";
			
				//2017-3-7如果是管理员或者超级管理员，显示可以修改实验室安排
				if($usercode > 3) {
					echo "<th>实验室安排&nbsp;<input type=\"checkbox\" name=\"field[]\" value=\"a_room\"></th>";
				}	
				echo "<th>修改</th>";
				echo "<th>删除</th>";
				echo "</tr>\n";
				
				$flag = true;
			}
			$differentCourse = $row[0].$row[1].$row[7].$row[8].$row[9];
			
			/*	$sql_tid = sprintf("SELECT a_sweek AS 周次,a_sdate AS 星期,a_sclass AS 节次 FROM time WHERE s_id='%d' AND a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[4],$row[4]);*/
			$sql_tid = sprintf("SELECT a_sweek AS 周次, a_sdate AS 星期, a_sclass AS 节次,t_id FROM time WHERE a_id='%d' ORDER BY `a_sweek` , `a_sdate`",$row[16]);
			$result_tid = mysql_query ( $sql_tid ) or die ( "不能查询指定的数据库表：" . mysql_error() );
			$row_num_tid = mysql_num_rows($result_tid);       //获取影响行的数目
			$row_tid = mysql_fetch_array($result_tid);
			$cont=0;//计数，输出的次数(针对计划学时的合并输出)
	
	
			//应该用时间段的行数
			for($n=0;$n<$row_num_tid;$n++) {
				
				echo "<tr>";
				echo "<td align=\"center\" id=\"idc\"><input type=\"checkbox\" name=\"record[]\" value=\"$row_tid[3]\"></td>";//选择该条记录$row[4]
				echo "<td align=\"center\" id=\"id0\" >".$row[0]."</td>";//课程名字
				echo "<td align=\"center\" id=\"id1\" >".$row[1]."</td>";//老师名称
				echo "<td align=\"center\" id=\"id2\" >".$row[2]."</td>";//课程类别
				echo "<td align=\"center\" id=\"id3\" >".$row[3]."</td>";//实验教材
				
				//实验编号、实验项目名称、实验类型合并
				if($cont==0) {
					echo "<td align=\"center\" id=\"id4\"  rowspan='$row_num_tid'>".$row[4]."</td>";//实验编号
					echo "<td align=\"center\" id=\"id5\"  rowspan='$row_num_tid'>".$row[5]."</td>";//实验项目名称
					echo "<td align=\"center\" id=\"id6\"  rowspan='$row_num_tid'>".$row[6]."</td>";//实验类型
				}
				//时间段
				echo "<td align=\"center\" id=\"id7\">".$row_tid[0]."</td>";//周次
				echo "<td align=\"center\" id=\"id8\">".$row_tid[1]."</td>";//星期
				echo "<td align=\"center\" id=\"id9\">".$row_tid[2]."</td>";//节次
				//合并
				if($cont==0) {
					echo "<td align=\"center\" id=\"id10\" rowspan='$row_num_tid' >".$row[7]."</td>";//年级
					echo "<td align=\"center\" id=\"id11\" rowspan='$row_num_tid'>".$row[8]."</td>";//专业
					echo "<td align=\"center\" id=\"id12\" rowspan='$row_num_tid'>".$row[9]."</td>";//班别
					echo "<td align=\"center\" id=\"id13\" rowspan='$row_num_tid'>".$row[10]."</td>";//人数
					echo "<td align=\"center\" id=\"id14\" rowspan='$row_num_tid'>".$row[11]."</td>";//计划学时
					echo "<td align=\"center\" id=\"id15\" rowspan='$row_num_tid'>".$row[12]."</td>";//实际学时
				}
				echo "<td align=\"center\" id=\"id16\" >".$row[13]."</td>";//耗材需求
				echo "<td align=\"center\" id=\"id17\" >".$row[14]."</td>";//系统需求
				echo "<td align=\"center\" id=\"id18\" >".$row[15]."</td>";//软件需求
			   
				//2017-3-7如果是管理员或者超级管理员，显示可以修改实验室安排
				if($usercode > 3) {
					echo "<td align=\"center\" id=\"id21\" >".$row[17]."</td>";//实验室安排
				}
			
			
				//单独添加"修改链接"	
				//注意下面直接用了超链接显式传参数的格式(用了&符号区分多个变量),在接收页要用$_GET接收
				//$row[16]表示登记表的编号
				//合并
				if($cont==0) {
					echo "<td align=\"center\" id=\"id19\" rowspan='$row_num_tid'>
					<img src=\"../images/edit.gif\"  style=\"cursor:hand;\" onClick='location.href=\"course_register_edit_single.php?a_id=$row[16]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='修改'/>
					</td>\n";
					
					//增加删除操作2
					echo "<td align=\"center\" id=\"id20\" rowspan='$row_num_tid'>
					<img src=\"../images/delete.gif\"  style=\"cursor:hand;\" onClick='javascript:if(confirm(\"您确实要删除该记录吗？\"))location=\"course_register_delete_result.php?a_id=$row[16]&search_condition=$search_condition&search_content=".urlencode($search_content)."\"' title='删除'/>
					</td>\n";
				}
				echo "</tr>\n";
				$cont++;	
				$row_tid = mysql_fetch_array($result_tid);

			}
		
			$a_rname1=$row[1];//存放上一条记录的教师名称
			$a_cname1=$row[0];//存放上一条记录的课程名称
			$a_major1=$row[8];//存放上一条记录的专业名称
			$a_class1=$row[9];//存放上一条记录的班级名称
			$a_grade1=$row[7];//存放上一条记录的年级名称
		}
		$row = mysql_fetch_array ($result);//获取相同课程的a_id
		
	}
	if($flag1) {
		echo "</table>\n";
		echo "<input type=\"submit\" name=\"sub\" value=\"批量修改\">";
		echo "</form>";
		
		echo "<form method='post' style='margin-left:80px;margin-top: -20px;' action='course_register_edit_show_add.php'>
			<input name='a_rname' type='hidden' value='${a_rname1}'/>
			<input name='a_cname' type='hidden' value='${a_cname1}'/>
			<input name='a_major' type='hidden' value='${a_major1}'/>
			<input name='a_class' type='hidden' value='${a_class1}'/>
			<input type='submit'  style='cursor:hand;' value='添加该课程的实验项目'>
			</form>";
		
		echo "<hr/><div style='height:40px;'></div>";
		$row_a_cname[$row_a_cname_count] = array("${a_rname1}", "${a_cname1}", "${a_major1}", "${a_class1}", "${a_grade1}");
		$row_a_cname_count++;
	}
	return $row_a_cname;	
}