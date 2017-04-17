<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- <script type="text/javascript" src="../js/validator.js"></script> --><!--表单验证JS-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" /><!--表格美化CSS-->
<!-- <script language="javascript" type="text/javascript" src="../js/select2css.js"></script> --><!--表单下拉列表美化JS-->
<!-- <link rel="stylesheet" type="text/css" href="../css/select2css.css"> --><!--表单下拉列表美化CSS-->

<title>权限管理</title>
</head>
<body>

<?php
	include("../common/db_conn.inc");//被引入时注意取消
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 

	/*--------------------按下提交按钮后执行的部分BEGIN---------------------------*/

	if ( isset ( $_POST["btnSubmit"] )) {    //在按下本页的提交按钮后执行以下操作(提交按钮的name是btnSubmit)

		if(!empty($_POST["teacher_pri"]))
		{
			$teacher_pri_arr = $_POST["teacher_pri"];//获取教师权限数组
			$teacher_pri_arr_count = count($teacher_pri_arr);//生成老师权限数组长度
			$teacher_pri_content = "";//初始化权限内容
			for( $i=0; $i<$teacher_pri_arr_count; $i++ )
			{
				//生成权限内容
				$teacher_pri_content .= $teacher_pri_arr[$i].",";
			}
			
			//去掉最后的逗号
			$teacher_pri_content = substr($teacher_pri_content,0,strlen($teacher_pri_content)-1);
							
			//echo $teacher_pri_content."<br/>";
			
			//更新数据库老师权限数据
			$teacher_pri_update = mysql_query("update privilege set p_content = '$teacher_pri_content' WHERE p_role_code=1");
				
			
		}
		else if($n==1){} //老师登录时此选项不用修改
		else//使得权限值问空
		{
			$teacher_pri_update = mysql_query("update privilege set p_content = '' WHERE p_role_code=1");
		}
	
		if(!empty($_POST["labmanager_pri"]))
		{
			$labmanager_pri_arr = $_POST["labmanager_pri"];//获取负责人权限数组
			$labmanager_pri_arr_count = count($labmanager_pri_arr);//生成负责人权限数组长度
			$labmanager_pri_content = "";//初始化权限内容
			for( $i=0; $i<$labmanager_pri_arr_count; $i++ )
			{
				//生成权限内容
				$labmanager_pri_content .= $labmanager_pri_arr[$i].",";
			}
			
			//去掉最后的逗号
			$labmanager_pri_content = substr( $labmanager_pri_content,0,strlen( $labmanager_pri_content)-1);
			
			//echo $labmanager_pri_content."<br/>";
			
			//更新数据库负责人权限数据
			$labmanager_pri_update = mysql_query("update privilege set p_content = '$labmanager_pri_content' WHERE p_role_code=2");
			
		}
		else if($n<=2){} //负责人、老师登录时此选项不用修改
		else//使得权限值问空
		{
			$labmanager_pri_update = mysql_query("update privilege set p_content = '' WHERE p_role_code=2");
		}

		//获取实验室主任权限数组
		if(!empty($_POST["labdirector_pri"]))
		{
			$labdirector_pri_arr = $_POST["labdirector_pri"];//获取实验室主任权限数组
			$labdirector_pri_arr_count = count($labdirector_pri_arr);//生成实验室主任权限数组长度
			$labdirector_pri_content = "";//初始化权限内容
			for( $i=0; $i<$labdirector_pri_arr_count; $i++ )
			{
				//生成权限内容
				$labdirector_pri_content .= $labdirector_pri_arr[$i].",";
			}
			
			//去掉最后的逗号
			$labdirector_pri_content = substr( $labdirector_pri_content,0,strlen( $labdirector_pri_content)-1);
			
			//echo $labmanager_pri_content."<br/>";
			
			//更新数据库实验室主任权限数据
			$labdirector_pri_update = mysql_query("update privilege set p_content = '$labdirector_pri_content' WHERE p_role_code=3");
			
		}
		else if($n<=3){} //主任或负责人、老师登录时此选项不用修改
		else//使得权限值问空
		{
			$labdirector_pri_update = mysql_query("update privilege set p_content = '' WHERE p_role_code=3");
		}

		//管理员权限数组
		if(!empty($_POST["sysmanagerr_pri"]))
		{	
			$sysmanagerr_pri_arr = $_POST["sysmanagerr_pri"];//获取实验室主任权限数组
			$sysmanagerr_pri_arr_count = count($sysmanagerr_pri_arr);//生成管理员权限数组长度
			$sysmanagerr_pri_content = "";//初始化权限内容
			for( $i=0; $i<$sysmanagerr_pri_arr_count; $i++ )
			{
				//生成权限内容
				$sysmanagerr_pri_content .= $sysmanagerr_pri_arr[$i].",";
			}
			
			//去掉最后的逗号
			$sysmanagerr_pri_content = substr( $sysmanagerr_pri_content,0,strlen( $sysmanagerr_pri_content)-1);
			
			//echo $labmanager_pri_content."<br/>";
			
			//更新数据库实验室主任权限数据
			$sysmanagerr_pri_update = mysql_query("update privilege set p_content = '$sysmanagerr_pri_content' WHERE p_role_code=4");
			
		}
		else if($n<=4){} //管理员或主任、负责人、老师登录时此选项不用修改
		else//使得权限值问空
		{
			$sysmanagerr_pri_update = mysql_query("update privilege set p_content = '' WHERE p_role_code=4");
		}
		/*
		if(!empty($_POST["admin_pri"]))
		{
			$admin_pri_arr = $_POST["admin_pri"];//获取管理员权限数组
			$admin_pri_arr_count = count($admin_pri_arr);//生成管理员权限数组长度
			$admin_pri_content = "";//初始化权限内容
			for( $i=0; $i<$admin_pri_arr_count; $i++ )
			{
				//生成权限内容
				$admin_pri_content .= $admin_pri_arr[$i].",";
			}
			
			//去掉最后的逗号
			$admin_pri_content = substr( $admin_pri_content,0,strlen( $admin_pri_content)-1);
			
			//echo $admin_pri_content."<br/>";
			
			//更新数据库管理员权限数据
			$admin_pri_update = mysql_query("update privilege set p_content = '$admin_pri_content' WHERE p_role_code=4");
			
		}
		*/
		
		if( $teacher_pri_update == true || $labmanager_pri_update == true || $labdirector_pri_update==true || $sysmanagerr_pri_update==true )
		{
			echo "<script>alert('权限更新成功!');</script>";	
		}
		else
		{
			echo "<script>alert('权限更新失败!');</script>";
		}

	}

	/*--------------------按下提交按钮后执行的部分END-----------------------------*/
	
	
	

echo '<div  style="font-size:14px; text-align:center;  color:#1E7ACE; font-weight:bold;">用户权限管理</div>';	
echo '<form method="post" action="">';//表单以post方式提交到本页处理


    /*--------------------获取全权限数组BEGIN---------------------------*/
	$pri_list_rs = mysql_query ( "SELECT * FROM privilege WHERE p_role_code=6" ) //角色代号6所在行
	or die ( "SQL语句执行失败:" . mysql_error() );
	
	$pri_list_row = mysql_fetch_array( $pri_list_rs );
	
	$pri_list_str = $pri_list_row[p_content];//获取权限内容字符串
	
	$pri_list_arr = explode( "," , $pri_list_str); //▲生成权限列表数组
	
	$pri_list_count = count($pri_list_arr);//▲得到列表数组的个数
	
	echo "<br/>";
	echo "<table align=\"center\" border=\"1\" width=\"100%\" bordercolor=\"#000000;\">\n";
	echo "<tr  align=\"center\"><th>用户</th>";
	for($i=0;$i<$pri_list_count;$i++)
	{
		$checkbox_temp_value = $pri_list_arr[$i];//取得权限的临时显示文字
					
		//去掉权限临时显示文字的前三个字符,即两个数字及一个连字符
		$checkbox_value = substr( $checkbox_temp_value,3,strlen($checkbox_temp_value) );
		echo "<th>".$checkbox_value."</th>";
	}
	echo "</tr>";
	//读取各个用户的权限
	$result_1 = mysql_query ( "SELECT * FROM privilege" ) or die ( "SQL语句执行失败:" . mysql_error() );
	for($j=1;$j<$n;$j++)
	{
		switch($j)
		{
			case 1: {$name = "老师"; $name_pri = "teacher_";break;}
			case 2: {$name = "负责人"; $name_pri = "labmanager_";break;}
			case 3: {$name = "实验室主任"; $name_pri = "labdirector_";break;}
			case 4: {$name = "管理员"; $name_pri = "sysmanagerr_";break;}
			//case 5: {$name = "超级用户"; $name_pri = "sysmanagerr_";break;}
		}
		echo "<tr  align=\"center\" ><td>".$name."</td>";
		$row_1 = mysql_fetch_array ( $result_1 );
		$pri_str = $row_1[p_content]; //权限字符串				
		$pri_arr = explode ( "," , $pri_str ); //生成权限数组

		//
		for($i=0;$i<$pri_list_count;$i++)
		{
			$checkbox_temp_value = $pri_list_arr[$i];//取得权限的临时显示文字
					
			//去掉权限临时显示文字的前三个字符,即两个数字及一个连字符
			$checkbox_value = substr( $checkbox_temp_value,3,strlen($checkbox_temp_value) );
			$trans_value = $i+1;//定义CheckBox的value值
			echo "<td>";		
			//如果权限的列表(位置数字)存在于全权限列表中,则进行勾选输出
			
			if( in_array($i+1, $pri_arr) )
				{
					echo '<input type="checkbox" name="'.$name_pri.'pri[]" value="'.$trans_value.'" checked="checked">';
				}	
			//否则不进行普通输出
			else
				{					
					echo '<input type="checkbox" name="'.$name_pri.'pri[]" value="'.$trans_value.'">';
				}
			echo "</td>";
		}
		echo "</tr>";
	}
    /*--------------------获取全权限数组END-----------------------------*/
	
	
		
    /*-------------------显示权限的checkbox列表BEGIN----------------------------*/
	
	/*
	$result_1 = mysql_query ( "SELECT * FROM privilege" ) or die ( "SQL语句执行失败:" . mysql_error() );
	
	for($i_1=0; $i_1<=2 ; $i_1++)//取结果集中的前三行，即老师，负责人,实验室主任、系统管理员
	{
		$row_1 = mysql_fetch_array ( $result_1 );
		
		switch ($i_1) 
		{
			case 0://第一行为"教师"行
				//echo "教师<br/>";
				echo "<fieldset>
				<legend>教师</legend>";
				
				$teacher_pri_str = $row_1[p_content]; //教师权限字符串
				
				$teacher_pri_arr = explode ( "," , $teacher_pri_str ); //生成教师权限数组
				
				//进行全权限数组列表的循环
				for ( $j_1=0 ; $j_1 < $pri_list_count ; $j_1++ )
				{
					$checkbox_temp_value = $pri_list_arr[$j_1];//取得权限的临时显示文字
					
					//去掉权限临时显示文字的前三个字符,即两个数字及一个连字符
					$checkbox_value = substr( $checkbox_temp_value,3,strlen($checkbox_temp_value) );
					
					$trans_value = $j_1+1;//定义CheckBox的value值
					
					//如果教师权限的列表(位置数字)存在于全权限列表中,则进行勾选输出
					if( in_array($j_1+1, $teacher_pri_arr) )
					{
						echo <<<teacher_checkbox
				<input type="checkbox" name="teacher_pri[]" value="$trans_value" checked="checked">-$checkbox_value
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
teacher_checkbox;
					}	
						//否则不进行普通输出
						else
						{					
							echo <<<teacher_checkbox
						<input type="checkbox" name="teacher_pri[]" value="$trans_value">-$checkbox_value
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
teacher_checkbox;
						}
					//输出CheckBox的过程中每隔4个换一行
					if ( ($j_1+1)%4 == 0)
					{
						echo "<br/><br/>";
					}


				}				
				echo "</fieldset><br/>";				
				break;
				
				
			case 1://第二行为"负责人"行
			
				//echo "负责人<br/>";
				echo "<fieldset>
				<legend>负责人</legend>";

				$labmanager_pri_str = $row_1[p_content]; //负责人权限字符串
				
				$labmanager_pri_arr = explode ( "," , $labmanager_pri_str );//生成负责人权限数组
				
				//进行全权限数组列表的循环
				for ( $j_1=0 ; $j_1 < $pri_list_count ; $j_1++ )
				{
					$checkbox_temp_value = $pri_list_arr[$j_1];//取得权限的临时显示文字
					
					//去掉权限临时显示文字的前三个字符,即两个数字及一个连字符
					$checkbox_value = substr( $checkbox_temp_value,3,strlen($checkbox_temp_value) );
					
					$trans_value = $j_1+1;//定义CheckBox的value值
					
					//如果负责人权限的列表(位置数字)存在于全权限列表中,则进行勾选输出
					if( in_array($j_1+1, $labmanager_pri_arr) )
					{
						echo <<<labmanager_checkbox
			<input type="checkbox" name="labmanager_pri[]" value="$trans_value" checked="checked">-$checkbox_value
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
labmanager_checkbox;
					}	
						//否则不进行普通输出
						else
						{					
							echo <<<labmanager_checkbox
						<input type="checkbox" name="labmanager_pri[]" value="$trans_value">-$checkbox_value
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
labmanager_checkbox;
						}
					//输出CheckBox的过程中每隔4个换一行
					if ( ($j_1+1)%4 == 0)
					{
						echo "<br/><br/>";
					}


				}				
				echo "</fieldset><br/>";	
				break;

			case 2://第三行为"实验室主任"行
				echo "<fieldset>
				<legend>实验室主任</legend>";

				$labdirector_pri_str = $row_1[p_content]; //实验室主任权限字符串
				
				$labdirector_pri_arr = explode ( "," , $labdirector_pri_str );//生成实验室主任权限数组
				
				//进行全权限数组列表的循环
				for ( $j_1=0 ; $j_1 < $pri_list_count ; $j_1++ )
				{
					$checkbox_temp_value = $pri_list_arr[$j_1];//取得权限的临时显示文字
					
					//去掉权限临时显示文字的前三个字符,即两个数字及一个连字符
					$checkbox_value = substr( $checkbox_temp_value,3,strlen($checkbox_temp_value) );
					
					$trans_value = $j_1+1;//定义CheckBox的value值
					
					//如果实验室主任权限的列表(位置数字)存在于全权限列表中,则进行勾选输出
					if( in_array($j_1+1, $labdirector_pri_arr) )
					{
						echo <<<labmanager_checkbox
			<input type="checkbox" name="labdirector_pri[]" value="$trans_value" checked="checked">-$checkbox_value
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
labmanager_checkbox;
					}	
						//否则不进行普通输出
						else
						{					
							echo <<<labmanager_checkbox
						<input type="checkbox" name="labdirector_pri[]" value="$trans_value">-$checkbox_value
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
labmanager_checkbox;
						}
					//输出CheckBox的过程中每隔4个换一行
					if ( ($j_1+1)%4 == 0)
					{
						echo "<br/><br/>";
					}


				}				
				echo "</fieldset><br/>";	
				break;
			/*
			case 2://第三行为"管理员"行
			
				//echo "管理员<br/>";
				echo "<fieldset>
				<legend>管理员</legend>";

				$admin_pri_str = $row_1[p_content]; //管理员权限字符串
				
				$admin_pri_arr = explode ( "," , $admin_pri_str );//生成管理员权限数组
				
				//进行全权限数组列表的循环
				for ( $j_1=0 ; $j_1 < $pri_list_count ; $j_1++ )
				{
					$checkbox_temp_value = $pri_list_arr[$j_1];//取得权限的临时显示文字
					
					//去掉权限临时显示文字的前三个字符,即两个数字及一个连字符
					$checkbox_value = substr( $checkbox_temp_value,3,strlen($checkbox_temp_value) );
					
					$trans_value = $j_1+1;//定义CheckBox的value值
					
					//如果管理员权限的列表(位置数字)存在于全权限列表中,则进行勾选输出
					if( in_array($j_1+1, $admin_pri_arr) )
					{
						echo <<<admin_checkbox
			<input type="checkbox" name="admin_pri[]" value="$trans_value" checked="checked">-$checkbox_value
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
admin_checkbox;
					}	
						//否则不进行普通输出
						else
						{					
							echo <<<admin_checkbox
						<input type="checkbox" name="admin_pri[]" value="$trans_value">-$checkbox_value
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
admin_checkbox;
						}
					//输出CheckBox的过程中每隔4个换一行
					if ( ($j_1+1)%4 == 0)
					{
						echo "<br/><br/>";
					}


				}				
				echo "</fieldset><br/>";
				break;
				*/
		//}
			
	//}
	
    /*-------------------显示权限的checkbox列表BEGIN----------------------------*/

	
	//清空用到的数组
//	unset($teachers_arr);
//	unset($teacher_course_arr);
//	unset($teacher_course_week_arr);
//	unset($all_week_arr);
//	unset($cd_week_arr);

?>
</table>
		<input type="submit" value="提交" name="btnSubmit" class="buttonSubmit"/>
		</form>
<!--表单</div>外边框DIV_END-->

</body>
</html>

