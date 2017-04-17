<?php
	/*函数buildNavigation())*/
	/*功能：根据传入的权限编号建立导航*/

	function buildNavigation($user_code , $publish_state)//publish_state参数是总体安排表的发布情况
	{   
		$get_pri_content_rs = mysql_query ( "SELECT * FROM privilege WHERE p_role_code = '$user_code' " ) or die ( "SQL语句执行失败:" . mysql_error() );
		
		$get_pri_content_row = mysql_fetch_array( $get_pri_content_rs );
		
		$pri_content_str = $get_pri_content_row[p_content];//获取权限内容字符串
		
		$pri_content_arr = explode( "," , $pri_content_str); //生成权限列表数组
	
		switch ($user_code)
		{
			case "1"://教师的左侧导航
				
				/*---------------用户管理模块BEGIN-----------------*/
				echo <<<usermanage_module_begin
		        <tr bgcolor="#FFFFFF"> 
		          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
		        </tr>
		
		        <tr> 
		          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(2);" id="2"><strong>用户管理</strong></td>
		        </tr>
		        <tr> 
		          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu2" style="DISPLAY: none">
				  	<table width="94%" border="0">
usermanage_module_begin;

				/*---------------1、添加用户功能BEGIN--------------------*/
				if ( in_array( "1" , $pri_content_arr) )
				{
				   echo <<<module_1
				    <tr> 
	                <td height="20"><a href="top.php?location=tjyh" target="top" onClick="parent.right.location.href='user_manage/add_user.php'">添加用户</a></td>
				    </tr>
module_1;
				}
				/*---------------1、添加用户功能END--------------------*/
				
				/*---------------2、修改个人信息(包括修改与删除)BEGIN--------------------*/
				if ( in_array( "2" , $pri_content_arr) )
				{
				   echo <<<module_2
	              <tr> 
	                <td height="20"><a href="top.php?location=xggrxx" target="top" onClick="parent.right.location.href='user_manage/edit_personal_info_normal.php'">修改个人信息</a></td>
				  </tr>
module_2;
				}
				/*---------------2、修改个人信息(包括修改与删除)END--------------------*/
				
				/*---------------3、删除用户BEGIN--------------------*/
				/*if ( in_array( "3" , $pri_content_arr) )
				{
				   echo <<<module_3
	              <tr> 
					<td height="20"><a href="top.php?location=scyh" target="top" onClick="parent.right.location.href='user_manage/del_user.php'">删除用户</a></td>
				  </tr>
module_3;
				}
				/*---------------3、删除用户END--------------------*/
				echo <<<usermanage_module_end
            	   </table>
				</td>
        		</tr>
usermanage_module_end;
				/*---------------用户管理模块END-------------------*/
				
				
				/*---------------实验室申请模块BEGIN-------------------*/
				echo <<<labapply_module_begin
		       <tr bgcolor="#FFFFFF"> 
		          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
		        </tr>
		
		       <tr> 
		          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(8);" id="8"><strong>实验室申请</strong></td>
		        </tr>
		        <tr> 
		          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu8" style="DISPLAY: none"><table width="94%" border="0">
labapply_module_begin;
				
				/*---------------4、填写登记信息BEGIN--------------------*/
				if ( in_array( "3" , $pri_content_arr) )
				{
				   echo <<<module_4
	              <tr> 
	                <td height="20"><a href="top.php?location=txdjb" target="top" onClick="parent.right.location.href='course_register/course_register.php'">填写登记表</a></td>
				  </tr>
module_4;
				}
				/*---------------4、填写登记信息END--------------------*/
				
				/*---------------5、修改登记信息(包括修改与删除)BEGIN--------------------*/
				if ( in_array( "4" , $pri_content_arr) )
				{
				   echo <<<module_5
	              <tr> 
					<td height="20"><a href="top.php?location=xgdjb" target="top" onClick="parent.right.location.href='course_register/course_register_edit_show.php'">修改登记表</a></td>
				  </tr>
module_5;
				}
				/*---------------5、修改登记信息(包括修改与删除)END--------------------*/				
				
				/*---------------6、删除登记表BEGIN--------------------*/
				/*if ( in_array( "6" , $pri_content_arr) )
				{
				   echo <<<module_6
	              <tr> 
						<td height="20"><a href="top.php?location=scdjb" target="top" onClick="parent.right.location.href='course_register/course_register_delete_show_tea.php'">删除登记表</a></td>
				  </tr>
module_6;
				}
				/*---------------6、删除登记表END--------------------*/	


				/*---------------7、查询登记表BEGIN--------------------*/
				if ( in_array( "6" , $pri_content_arr) )
				{
				   echo <<<module_7
	              <tr> 
					<td height="20"><a href="top.php?location=cxdjb" target="top" onClick="parent.right.location.href='course_register/course_register_search.php'">查询登记表</a></td>
				  </tr>
module_7;
				}
				/*---------------7、查询登记表END--------------------*/	



				/*---------------8、反馈信息表BEGIN--------------------*/
				if ( in_array( "5" , $pri_content_arr) )
				{
					
					if($publish_state=='1')
					{				
					   echo '
		                <tr><td height="20"><a href="top.php?location=fkxxb" target="top" onClick="parent.right.location.href=\'stat_ana/feedback.php\'">反馈信息表</a></td></tr>
		                ';
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">反馈信息表</span></td></tr>
							';
		            	}
				}
				/*---------------8、修改登记信息END--------------------*/	


				/*---------------9、上传教学周历BEGIN--------------------*/
				if ( in_array( "7" , $pri_content_arr) )
				{
			        $sql="select * from apply1 where a_rname='$_SESSION[u_name]'";
					$result=mysql_query( $sql );
					$num_rows=mysql_num_rows( $result );
					if( $num_rows<1 )
					  echo'<tr><td height=20 style="color:#888888;" colspan="2" title="填写完登记表后，刷新即可用">上传教学周历 </td></tr>';
					  else
					   {
					echo <<<module_9
				<td height="20" colspan="2"><a href="top.php?location=scjxzl" target="top" onClick="parent.right.location.href='course_register/JXZL_upload/file_upload.php'">上传教学周历</a></td>
module_9;
			      	   }
					 mysql_free_result( $result );
				}
				/*---------------9、上传教学周历END--------------------*/	
				
				/*---------------10、实验室相关申请简表BEGIN--------------------*/
				if ( in_array( "8" , $pri_content_arr) )
				{
				  echo <<<module_10
	              <tr> 
					<td height="20"><span style="font-size:12px; color:#888" >实验室相关申请简表</span></td>
				  </tr>
module_10;
				}
				/*---------------10、实验室相关申请简表END--------------------*/	


				/*---------------11、实验室相关申请总表BEGIN--------------------*/
				if ( in_array( "9" , $pri_content_arr) )
				{
				   echo <<<module_11
	              <tr> 
					<td height="20"><span style="font-size:12px; color:#888" >实验室相关申请总表</span></td>
				  </tr>
module_11;
				}
				/*---------------11、实验室相关申请总表END--------------------*/	


				/*---------------12、相关课程教学周历BEGIN--------------------*/
				if ( in_array( "10" , $pri_content_arr) )
				{
				   echo <<<module_12
	              <tr> 
					<td height="20"><span style="font-size:12px; color:#888" >相关课程教学周历</span></td>
				  </tr>
module_12;
				}
				/*---------------12、相关课程教学周历END--------------------*/	
				
				
				echo <<<labapply_module_end
            	   </table>
				</td>
        		</tr>
labapply_module_end;
				/*---------------实验室申请模块END-------------------*/


				/*---------------实验室管理模块BEGIN-------------------*/
				
				/*---------------11、冲突检测BEGIN--------------------*/
				if ( in_array( "11" , $pri_content_arr) )
				{
					
				   echo <<<module_13
			        <tr> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
					 <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(7);" id="7"><strong>实验室安排</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu7" style="DISPLAY: none"><table width="94%" border="0">
			
						  <tr> 
							<td height="20"><a href="top.php?location=ctjc" target="top" onClick="parent.right.location.href='room_arrange/collision_detect.php'">冲突检测</a></td>
			              </tr>
			            </table></td>
			        </tr>				  
module_13;
				}
				/*---------------11、冲突检测END--------------------*/	
				
				/*---------------实验室管理模块END-------------------*/


				
				/*---------------统计分析模块BEGIN-------------------*/
				echo <<<stat_module_begin
		        <tr> 
		          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
		        </tr>
		
		
				<tr> 
		          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(1);" id="1"><strong>统计分析</strong></td>
		        </tr>
		        <tr bgcolor="#FFFFFF"> 
		          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu1" style="DISPLAY: none"><table width="94%" border="0">
stat_module_begin;
				
				/*---------------12、总体安排表BEGIN--------------------*/
				if ( in_array( "12" , $pri_content_arr) )
				{
					if($publish_state=='1')
					{				
					   echo '
		                <tr><td height="20"><a href="top.php?location=ztapb" target="top" onClick="parent.right.location.href=\'stat_ana/arrange_result_all.php\'">总体安排表</a></td><tr>
		                ';
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">总体安排表</span></td></tr>
							';
		            	}
				}
				/*---------------12、总体安排表END--------------------*/


				/*---------------13、按实验室安排表BEGIN--------------------*/
				if ( in_array( "13" , $pri_content_arr) )
				{
				   echo <<<module_15
	              <tr> 
	                <td height="20"><a href="top.php?location=asysapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_lab.php'">按实验室安排表</a></td>
				  </tr>
module_15;
				}
				/*---------------13、按实验室安排表END--------------------*/


				/*---------------14、按房间安排表BEGIN--------------------*/
				if ( in_array( "14" , $pri_content_arr) )
				{
				   echo <<<module_16
	              <tr> 
	                <td height="20"><a href="top.php?location=afjapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_single_room.php'">按房间安排表</a></td>
				  </tr>
module_16;
				}
				/*---------------14、按房间安排表END--------------------*/


				/*---------------15、按周次安排表BEGIN--------------------*/
				if ( in_array( "15" , $pri_content_arr) )
				{
				   echo <<<module_17
	              <tr> 
	                <td height="20"><a href="top.php?location=azcapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_week.php'">按周次安排表</a></td>
				  </tr>
module_17;
				}
				/*---------------15、按周次安排表END--------------------*/


				/*---------------16、学时数分析BEGIN--------------------*/
				if ( in_array( "16" , $pri_content_arr) )
				{
				   echo <<<module_18
	              <tr> 
	                <td height="20"><a href="top.php?location=xsfxb" target="top" onClick="parent.right.location.href='stat_ana/ana_table_XS.php'">学时数分析</a></td>
				  </tr>
module_18;
				}
				/*---------------16、学时数分析END--------------------*/


				/*---------------17、人时数分析BEGIN--------------------*/
				if ( in_array( "17" , $pri_content_arr) )
				{
				   echo <<<module_19
	              <tr> 
	                <td height="20"><a href="top.php?location=rsfxb" target="top" onClick="parent.right.location.href='stat_ana/ana_table_RS.php'">人时数分析</a></td>
				  </tr>
module_19;
				}
				/*---------------17、人时数分析END--------------------*/

				/*-----------------18、实验课信息登记表汇总BEGIN-----------------------------------*/
			if( in_array( "18" , $pri_content_arr) )
			{
				echo <<<module_21
	              <tr> 
	                <td height="20"><a href="top.php?location=hzjsdg" target="top" onClick="parent.right.location.href='stat_ana/show_apply_table_single_for_teacher.php'">实验课信息登记表汇总</a></td>
				  </tr>
module_21;
			}
				/*-----------------18、实验课信息登记表汇总AND-------------------------------------*/


				echo <<<stat_module_end
            	   </table>
				</td>
        		</tr>
stat_module_end;
				/*---------------统计分析模块END-------------------*/
				
				

				/*---------------数据库管理模块BEGIN-------------------*/
				
				//决定是否输出模块标题
				if (  in_array( "19" , $pri_content_arr) ||  in_array( "20" , $pri_content_arr) ||  in_array( "21" , $pri_content_arr) ||  in_array( "22" , $pri_content_arr) ||  in_array( "23" , $pri_content_arr) ||  in_array( "24" , $pri_content_arr) ||  in_array( "25" , $pri_content_arr) ||  in_array( "26" , $pri_content_arr) ||  in_array( "27" , $pri_content_arr) )
				{
					echo <<<sys_module_begin
			        <tr bgcolor="#FFFFFF"> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
			        
			         <tr> 
			        <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(5);" id="5"><strong>数据库操作</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu5" style="DISPLAY: none"><table width="94%" border="0">
sys_module_begin;
					
				}
				if( in_array( "19" , $pri_content_arr) ||  in_array( "20" , $pri_content_arr) ||  in_array( "21" , $pri_content_arr))
				{
					echo'<tr><td height="20">——基本信息管理——</td></tr>';
				}
				/*---------------19、第一周日期BEGIN--------------------*/
				if ( in_array( "19" , $pri_content_arr) )
				{
				  echo <<<module_28
	              <tr> 
	                <td height="20" ><a href="top.php?location=szrq" target="top" onClick="parent.right.location.href='basic_info_manage/set_first_week_date.php'">第一周日期</td>
				  </tr>
module_28;
				}
				/*---------------19、第一周日期END--------------------*/
				

				/*---------------20、课程方向分类BEGIN--------------------*/
				if ( in_array( "20" , $pri_content_arr) )
				{
				  echo <<<module_26
	              <tr> 
	                <td height="20" ><a href="top.php?location=kcfxfl" target="top" onClick="parent.right.location.href='basic_info_manage/course.php'">课程方向分类</a></td>
				  </tr>
module_26;
				}
				/*---------------20、课程方向分类END--------------------*/	


				/*---------------21、实验室基本信息BEGIN--------------------*/
				if ( in_array( "21" , $pri_content_arr) )
				{
				  echo <<<module_27
	              <tr> 
	                <td height="20" ><a href="top.php?location=sysjbxx" target="top" onClick="parent.right.location.href='basic_info_manage/room.php'">实验室基本信息</a></td>
				  </tr>
module_27;
				}
				/*---------------21、实验室基本信息END--------------------*/	


				//
			if(in_array( "22" , $pri_content_arr) ||  in_array( "23" , $pri_content_arr) ||  in_array( "24" , $pri_content_arr) ||  in_array( "25" , $pri_content_arr) ||  in_array( "26" , $pri_content_arr) ||  in_array( "27" , $pri_content_arr))
			{
				echo'<tr><td height="20">——数据备份——</td></tr>';
			}


				/*---------------22、用户信息表BEGIN--------------------*/
				if ( in_array( "22" , $pri_content_arr) )
				{
				  echo <<<module_20
	              <tr> 
					<td height="20" ><a href="top.php?location=bfyhxxb" target="top" onClick="parent.right.location.href='data_backup/backup_user.php'">用户信息表</a></td>
				  </tr>
module_20;
				}
				/*---------------22、用户信息表END--------------------*/	

				/*---------------23、单个实验课程信息表BEGIN--------------------*/
				if ( in_array( "23" , $pri_content_arr) )
				{
					 echo <<<module_21
	              <tr> 
	                <td height="20"><a href="top.php?location=hzjsdg" target="top" onClick="parent.right.location.href='data_backup/backup_apply_table_single.php'">查询单个实验课程信息表</a></td>
				  </tr>
module_21;
				}
				/*---------------23、单个实验课程信息表END--------------------*/
	

				/*---------------24、全部实验课程信息表BEGIN--------------------*/
				if ( in_array( "24" , $pri_content_arr) )
				{
				  echo <<<module_22
	              <tr> 
					<td height="20" ><a href="top.php?location=bfqbsykcxxb" target="top" onClick="parent.right.location.href='data_backup/backup_apply_table_all.php'">全部实验课程信息表</a></td>
				  </tr>
module_22;
				}
				/*---------------24、全部实验课程信息表END--------------------*/	


				/*---------------25、总体安排表BEGIN--------------------*/
				if ( in_array( "25" , $pri_content_arr) )
				{
				  echo <<<module_23
	              <tr> 
					<td height="20" ><a href="top.php?location=bfztapb" target="top" onClick="parent.right.location.href='data_backup/backup_arrange_result_all.php'">总体安排表</a></td>
				  </tr>
module_23;
				}
				/*---------------25、总体安排表END--------------------*/	


				/*---------------26、全局数据库BEGIN--------------------*/
				if ( in_array( "26" , $pri_content_arr) )
				{
				  echo <<<module_24
	              <tr> 
					<td height="20" ><a href="top.php?location=bfqjsjk" target="top" onClick="parent.right.location.href='data_backup/backup_mysql.php'">全局数据库</a></td>
				  </tr>
module_24;
				}
				/*---------------26、全局数据库END--------------------*/	


				/*---------------27、导入数据库BEGIN--------------------*/
				if ( in_array( "27" , $pri_content_arr) )
				{
				  echo <<<module_25
	              <tr> 
					<td height="20" ><a href="top.php?location=drsjk" target="top" onClick="parent.right.location.href='data_backup/import_mysql.php'">导入数据库</a></td>
				  </tr>
module_25;
				}
				/*---------------27、导入数据库END--------------------*/	

				//决定是否输出模块标题
				if ( in_array( "19" , $pri_content_arr) ||  in_array( "20" , $pri_content_arr) ||  in_array( "21" , $pri_content_arr) ||  in_array( "22" , $pri_content_arr) ||  in_array( "23" , $pri_content_arr) ||  in_array( "24" , $pri_content_arr) ||  in_array( "25" , $pri_content_arr) ||  in_array( "26" , $pri_content_arr) ||  in_array( "27" , $pri_content_arr) )
				{
					echo <<<sys_module_end
            	   </table>
				</td>
        		</tr>
sys_module_end;
					
				}
				/*---------------数据库管理模块END-------------------*/
				

			/*-------------------系统维护模块（日志）---------------------------*/
			if(in_array( "28" , $pri_content_arr))
			{
				echo <<<sys_module_begin
			        <tr bgcolor="#FFFFFF"> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
			        
			         <tr> 
			        <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(17);" id="17"><strong>系统维护</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu17" style="DISPLAY: none"><table width="94%" border="0">
sys_module_begin;
				/*---------------27、日志BEGIN--------------------*/
				//if ( in_array( "27" , $pri_content_arr) )
				//{
				  echo <<<module_29
	              <tr> 
					<td height="20" ><a href="top.php?location=rz" target="top" onClick="parent.right.location.href='log/log.php'">日志</td>
				  </tr>
module_29;
				

				echo <<<sys_module_end
            	   </table>
				</td>
        		</tr>
sys_module_end;
				/*---------------27、日志END--------------------*/	
			}



			/*-------------------系统维护模块（日志）---------------------------*/

				break;				
			case "2"://负责人的左侧导航



				break;				
			case "3"://管理员的左侧导航
			
			
				break;
			
		}//switch END
	}//function END
	
	buildNavigation($user_code , $publish_state);//执行函数
    /*-----------------------------------------------*/	

?>