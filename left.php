<?php
include("./common/session.inc");//获取登陆用户的权限代码（1为教师，2为负责人，3为实验室主任，4为管理员，5为系统管理员）
include("./common/db_conn.inc");
include("./common/publish_state.php");
//print_r($n);
if($n<0) 
echo "<script language='javascript'>alert('你无权进行此操作！');
				     location.href='index.html';</script>";

//生成下拉菜单导航-2010-6-20//
//函数buildNavigation())//
//功能：根据传入的权限编号建立导航//
//*
function buildNavigation($user_code , $publish_state)//publish_state参数是总体安排表的发布情况
{   
		$get_pri_content_rs = mysql_query ( "SELECT * FROM privilege WHERE p_role_code = '$user_code' " ) or die ( "SQL语句执行失败:" . mysql_error() );
		
		$get_pri_content_row = mysql_fetch_array( $get_pri_content_rs );
		
		$pri_content_str = $get_pri_content_row[p_content];//获取权限内容字符串
		
		$pri_content_arr = explode( "," , $pri_content_str); //生成权限列表数组
	if(count($pri_content_arr)!=0)
	{
			//---------------用户管理模块BEGIN-----------------1-3//
			if (in_array("1" , $pri_content_arr) or in_array("2" , $pri_content_arr) or in_array("3" , $pri_content_arr))
			{
				echo '
		        <tr bgcolor="#FFFFFF"> 
		          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
		        </tr>
		
		        <tr> 
		          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(1);" id="1"><strong>用户管理</strong></td>
		        </tr>
		        <tr> 
		          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu1" style="DISPLAY: none">
				  	<table width="98%" border="0">
';

				//---------------1、添加用户功能BEGIN--------------------//
				if(in_array( "1" , $pri_content_arr) or in_array( "2" , $pri_content_arr))
				{
					echo '<tr>';
				if ( in_array( "1" , $pri_content_arr) )
				{
				   echo '
				     
	                <td height="20"><a href="top.php?location=tjyh" target="top" onClick="parent.right.location.href=\'user_manage/add_user.php\'">添加用户</a></td>
				    
';		   
				}
				//---------------1、添加用户功能END--------------------//
				
				//---------------2、修改个人信息BEGIN--------------------//
				if ( in_array( "2" , $pri_content_arr) )
				{
					if($user_code<4)
					   echo '
						<td height="20"><a href="top.php?location=xggrxx" target="top" onClick="parent.right.location.href=\'user_manage/edit_personal_info_normal.php\'">修改个人信息</a></td>';
					else
						echo '
						<td height="20"><a href="top.php?location=xggrxx" target="top" onClick="parent.right.location.href=\'user_manage/edit_personal_info_type.php\'">修改个人信息</a></td>';
				}
				//---------------2、修改个人信息END--------------------//
				echo '</tr>';
				}

				//---------------3、权限管理BEGIN--------------------//
				if ( in_array( "3" , $pri_content_arr) )
				{
				   echo '
	              <tr> 
	                <td height="20"><a href="top.php?location=qxgl" target="top" onClick="parent.right.location.href=\'user_manage/manage_pri.php\'">权限管理</a></td>
';
				}
				//---------------3、权限管理END--------------------//
				echo '
            	   </table>
				</td>
        		</tr>
';
			}
				///---------------用户管理模块END-------------------//

		

			//---------------2实验室申请模块BEGIN-------------------4-10//
			//1填写  2修改  3反馈  4查询  5上传周历
			//相关
			//
			
			if(in_array( "4" , $pri_content_arr) or in_array( "5" , $pri_content_arr) or in_array( "6" , $pri_content_arr) or in_array( "7" , $pri_content_arr) or in_array( "8" , $pri_content_arr) or in_array( "9" , $pri_content_arr))
			{
				echo <<<labapply_module_begin
		       <tr bgcolor="#FFFFFF"> 
		          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
		        </tr>
		
		       <tr> 
		          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(2);" id="2"><strong>实验室申请</strong></td>
		        </tr>
		        <tr> 
		          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu2" style="DISPLAY: none"><table width="98%" border="0">
labapply_module_begin;
				//---------------3、填写登记信息BEGIN--------------------//
				if ( in_array( "4" , $pri_content_arr) )
				{
					//如果已发布，则不可填写申请表及修改申请表
					if($publish_state!='1') {
						echo <<<module_4
	              <tr> 
	                <td height="20"><a href="top.php?location=txdjb" target="top" onClick="parent.right.location.href='course_register/course_register.php'">填写登记表</a></td>
				  </tr>
module_4;
					} else if ($publish_state=='1' && $user_code > 3) {
						echo <<<module_4
	              <tr> 
	                <td height="20"><a href="top.php?location=txdjb" target="top" onClick="parent.right.location.href='course_register/course_register.php'">填写登记表</a></td>
				  </tr>
module_4;
					} else {
						echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="">填写登记表</span></td></tr>
							';
					}
				   
				}
				//---------------3、填写登记信息END--------------------//
				
				//---------------4、修改登记信息BEGIN--------------------//
				if ( in_array( "5" , $pri_content_arr) )
				{
					
					//如果已发布，则不可填写申请表及修改申请表
					if($publish_state!='1') {
						echo <<<module_5
	              <tr> 
					<td height="20"><a href="top.php?location=xgdjb" target="top" onClick="parent.right.location.href='course_register/course_register_edit_show.php'">修改登记表</a></td>
				  </tr>
				   
module_5;
					} else if ($publish_state=='1' && $user_code > 3) {
						echo <<<module_5
	              <tr> 
					<td height="20"><a href="top.php?location=xgdjb" target="top" onClick="parent.right.location.href='course_register/course_register_edit_show.php'">修改登记表</a></td>
				  </tr>
				   
module_5;
					} else {
						echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="">修改登记表</span></td></tr>
							';
					}
					
					
					
				  
//<tr> 
//					<td height="20"><a href="top.php?location=scdjb" target="top" onClick="parent.right.location.href='course_register/course_register_edit_upload.php'">上传登记表</a></td>
//				  </tr>
				}
				//---------------4、修改登记信息END--------------------//				
				


				//---------------6、查询登记表BEGIN--------------------//
				if ( in_array( "6" , $pri_content_arr) )
				{
					$titlename="查询登记表";
					/*
					if($user_code==1)
					{
						$titlename="实验室相关申请简表";
					}*/
				   echo '
	              <tr> 
					<td height="20"><a href="top.php?location=cxjb" target="top" onClick="parent.right.location.href=\'course_register/course_register_search.php\'">'.$titlename.'</a></td>
				  </tr>
';
				}
				//---------------6、查询登记表END--------------------//	

				//---------------8、反馈信息表BEGIN--------------------//
				if ( in_array( "7" , $pri_content_arr) )
				{
					
					if($publish_state=='1' || $user_code > 3)
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
				//---------------8、反馈信息表END--------------------//	


				//---------------7、上传教学周历BEGIN--------------------//
				if ( in_array( "8" , $pri_content_arr) )//针对负责人填写登记表（作为老师功能，才会有上传教学周历）
				{
					if($user_code < 3)
					{
						$sql="select * from apply1 where a_rname='$_SESSION[u_name]'";
						$result=mysql_query( $sql );
						$num_rows=mysql_num_rows( $result );
						if( $num_rows<1)
						  echo'<tr><td height=20 style="color:#888888;" colspan="2" title="填写完登记表后，刷新即可用">上传教学周历</td></tr>';
						  else
						   {
						echo <<<module_8
					<td height="20" colspan="2"><a href="top.php?location=scjxzl" target="top" onClick="parent.right.location.href='course_register/JXZL_upload/file_upload.php'">上传教学周历</a></td>
module_8;
						   }
						 mysql_free_result( $result );
					}
					else
					{
						echo <<<module_8
					<td height="20" colspan="2"><a href="top.php?location=scjxzl" target="top" onClick="parent.right.location.href='course_register/JXZL_upload/file_upload.php'">上传教学周历</a></td>
module_8;
					}
				}
				//---------------7、上传教学周历END--------------------//	
				
				//---------------8、实验室相关申请简表BEGIN--------------------//
				if ( in_array( "9" , $pri_content_arr) )
				{
				  echo <<<module_9
	              <tr> 
					<td height="20"><a href="top.php?location=hzbrsgxxb" target="top" onClick="parent.right.location.href='stat_ana/show_apply_table_self_for_admin.php'">实验室相关申请总表</a></td>
				  </tr>
module_9;
				}
				//---------------8、实验室相关申请简表END--------------------//	


				//---------------9、实验室相关申请总表BEGIN--------------------//
				/*if ( in_array( "9" , $pri_content_arr) )
				{
				   echo <<<module_11
	              <tr> 
					<td height="20"><a href="top.php?location=hzqbxxb" target="top" onClick="parent.right.location.href='data_backup/backup_apply_table_all.php'">全部实验课程信息表</a></td>           
				  </tr>
module_11;
				}*/
				//---------------9、实验室相关申请总表END--------------------//	


				//---------------10、相关课程教学周历BEGIN--------------------//
				if ( in_array( "10" , $pri_content_arr) )
				{
				   echo <<<module_10
	              <tr> 
					<td height="20"><a href="top.php?location=xgjxzl" target="top" onClick="parent.right.location.href='course_register/JXZL_upload/file_upload_for_admin.php'">相关课程教学周历</a></td>
				  </tr>
module_10;
				}//
				//---------------10、相关课程教学周历END--------------------//				
				echo <<<labapply_module_end
            	   </table>
				</td>
        		</tr>
labapply_module_end;
		}
				//---------------实验室申请模块END-------------------//




				//---------------3实验室安排模块BEGIN-------------------11//
				//---------------11、冲突检测BEGIN--------------------//
				if ( in_array( "11" , $pri_content_arr))
				{
					
				   echo <<<module_10
			        <tr> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
					 <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(3);" id="3"><strong>实验室安排</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu3" style="DISPLAY: none"><table width="98%" border="0">
			
						  <tr> 
							<td height="20"><a href="top.php?location=ctjc" target="top" onClick="parent.right.location.href='room_arrange/collision_detect.php'">冲突检测</a></td>
			              </tr>
			            </table></td>
			        </tr>				  
module_10;
				}
				//---------------11、冲突检测END--------------------//	
				//---------------实验室安排模块END-------------------//


				
				//---------------4统计分析模块BEGIN-------------------12-17//
			if( in_array( "12" , $pri_content_arr) or in_array( "13" , $pri_content_arr) or in_array( "14" , $pri_content_arr) or in_array( "15" , $pri_content_arr) or in_array( "16" , $pri_content_arr) or in_array( "17" , $pri_content_arr))
			{
				echo <<<stat_module_begin
		        <tr> 
		          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
		        </tr>
		
		
				<tr> 
		          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(4);" id="4"><strong>统计分析</strong></td>
		        </tr>
		        <tr bgcolor="#FFFFFF"> 
		          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu4" style="DISPLAY: none"><table width="98%" border="0">
stat_module_begin;
				
				//---------------12、总体安排表BEGIN--------------------//
				if ( in_array( "12" , $pri_content_arr) )
				{
					if($publish_state=='1' || $user_code > 3)
					{				
					   echo <<<fuckdgut
		                <tr><td height="20"><a href="top.php?location=ztapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_all.php'">总体安排表</a></td><tr>
						
fuckdgut;
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">总体安排表</span></td></tr>
							';
		            	}
						
						 echo <<<gut
						<tr><td height="20"><a href="top.php?location=lsztapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_all_history.php'">历史总体安排表</a></td><tr>
gut;
						
				}
				//---------------12、总体安排表END--------------------//


				//---------------13、按实验室安排表BEGIN--------------------//
				if ( in_array( "13" , $pri_content_arr) )
				{
					if($publish_state=='1' || $user_code > 3)
					{				
					   echo <<<module_13
		                <tr><td height="20"><a href="top.php?location=asysapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_lab.php'">按实验室安排表</a></td><tr>
module_13;
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">按实验室安排表</span></td></tr>
							';
		            	}
				}
				//---------------13、按实验室安排表END--------------------//


				//---------------14、按房间安排表BEGIN--------------------//
				if ( in_array( "14" , $pri_content_arr) )
				{
					if($publish_state=='1' || $user_code > 3)
					{				
					   echo <<<module_14
		                <tr><td height="20"><a href="top.php?location=afjapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_single_room.php'">按房间安排表</a></td><tr>
module_14;
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">按房间安排表</span></td></tr>
							';
		            	}
				}
				//---------------14、按房间安排表END--------------------//


				//---------------15、按周次安排表BEGIN--------------------//
				if ( in_array( "15" , $pri_content_arr) )
				{
					if($publish_state=='1' || $user_code > 3)
					{				
					   echo <<<module_15
		                <tr><td height="20"><a href="top.php?location=azcapb" target="top" onClick="parent.right.location.href='stat_ana/arrange_result_week.php'">按周次安排表</a></td><tr>
module_15;
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">按周次安排表</span></td></tr>
							';
		            	}
				}
				//---------------15、按周次安排表END--------------------//


				//---------------16、学时数分析BEGIN--------------------//
				if ( in_array( "16" , $pri_content_arr) )
				{
					if($publish_state=='1' || $user_code > 3)
					{				
					   echo <<<module_16
		                <tr><td height="20"><a href="top.php?location=xsfxb" target="top" onClick="parent.right.location.href='stat_ana/ana_table_XS.php'">学时数分析</a></td><tr>
module_16;
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">学时数分析</span></td></tr>
							';
		            	}
				}
				//---------------16、学时数分析END--------------------//


				//---------------17、人时数分析BEGIN--------------------//
				if ( in_array( "17" , $pri_content_arr) )
				{
					if($publish_state=='1' || $user_code > 3)
					{				
					   echo <<<module_17
		                <tr><td height="20"><a href="top.php?location=rsfxb" target="top" onClick="parent.right.location.href='stat_ana/ana_table_RS.php'">人时数分析</a></td><tr>
module_17;
		            } 
		            	else
		            	{
		            		echo '
							<tr><td height="20"><span style="font-size:12px; color:#888" title="请等安排总表发布">人时数分析</span></td></tr>
							';
		            	}
				}
				//---------------17、人时数分析END--------------------//


				//-----------------18、实验课信息登记表汇总BEGIN-----------------------------------//
			/*if( in_array( "18" , $pri_content_arr) )
			{
				echo <<<module_18
	              <tr> 
	                <td height="20"><a href="top.php?location=hzjsdg" target="top" onClick="parent.right.location.href='stat_ana/show_apply_table_single_for_teacher.php'">实验课信息登记表汇总</a></td>
				  </tr>
module_18;
			}*/
				//-----------------18、实验课信息登记表汇总AND-------------------------------------//

				
				
				echo <<<stat_module_end
            	   </table>
				</td>
        		</tr>
stat_module_end;
			}
				//---------------4统计分析模块END-------------------//
				
				

				//---------------5数据操作模块BEGIN-------------------//
				
				//决定是否输出模块标题
				if ( in_array( "18" , $pri_content_arr) or  in_array( "19" , $pri_content_arr) or  in_array( "20" , $pri_content_arr))
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
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu5" style="DISPLAY: none"><table width="98%" border="0">
sys_module_begin;
					
				//第一大类：基本信息（日期、课程分类、实验室基本信息）
				if( in_array( "18" , $pri_content_arr))
				{
					echo'<tr><td height="20" align="center">——基本信息管理——</td></tr>';

				//---------------19、第一周日期BEGIN--------------------//
				  echo <<<module_171
	              <tr> 
	                <td height="20" ><a href="top.php?location=szrq" target="top" onClick="parent.right.location.href='basic_info_manage/set_first_week_date.php'">第一周日期</td>
				  </tr>
module_171;
				//---------------19、第一周日期END--------------------//		
				//---------------20、课程方向分类BEGIN--------------------//
				  echo <<<module_172
	              <tr> 
	                <td height="20" ><a href="top.php?location=kcfxfl" target="top" onClick="parent.right.location.href='basic_info_manage/course.php'">课程方向分类</a></td>
				  </tr>
module_172;
				//---------------20、课程方向分类END--------------------//	
				//---------------21、实验室基本信息BEGIN--------------------//
				  echo <<<module_173
	              <tr> 
	                <td height="20" ><a href="top.php?location=sysjbxx" target="top" onClick="parent.right.location.href='basic_info_manage/room.php'">实验室基本信息</a></td>
				  </tr>
module_173;
				//---------------21、实验室基本信息END--------------------//
				  }


			//第二大类：数据备份（用户信息、全局导出数据、导入数据）
			if(in_array( "19" , $pri_content_arr) or in_array( "20" , $pri_content_arr))
			{
				echo'<tr><td height="20" align="center">——数据备份——</td></tr>';
				if(in_array( "19" , $pri_content_arr))
				{
					  echo <<<module_181
					  <tr> 
						<td height="20" ><a href="top.php?location=bfyhxxb" target="top" onClick="parent.right.location.href='data_backup/backup_user.php'">用户信息表</a></td>
					  </tr>
module_181;
					//---------------26、全局数据库BEGIN--------------------//
					  echo <<<module_182
					  <tr> 
						<td height="20" ><a href="top.php?location=bfqjsjk" target="top" onClick="parent.right.location.href='data_backup/backup_mysql.php'">全局数据库</a></td>
					  </tr>
module_182;
					
					//---------------26、全局数据库END--------------------//	
					//---------------27、导入数据库BEGIN--------------------//
					  echo <<<module_183
					  <tr> 
						<td height="20" ><a href="top.php?location=drsjk" target="top" onClick="parent.right.location.href='data_backup/import_mysql.php'">导入数据库</a></td>
					  </tr>
module_183;
					//---------------27、导入数据库END--------------------//
				}


				//第三大类：信息表备份
				if(in_array( "20" , $pri_content_arr))
				{	
					//查询单个实验课程信息表
						 echo <<<module_191
					  <tr> 
						<td height="20"><a href="top.php?location=hzjsdg" target="top" onClick="parent.right.location.href='data_backup/backup_apply_table_single.php'">查询单个实验课程信息表</a></td>
					  </tr>
module_191;
					//全部实验课程信息表
					  echo <<<module_192
					  <tr> 
						<td height="20" ><a href="top.php?location=bfqbsykcxxb" target="top" onClick="parent.right.location.href='data_backup/backup_apply_table_all.php'">全部实验课程信息表</a></td>
					  </tr>
module_192;
					  //总体安排表
					  echo <<<module_193
					  <tr> 
						<td height="20" ><a href="top.php?location=bfztapb" target="top" onClick="parent.right.location.href='data_backup/backup_arrange_result_all.php'">总体安排表</a></td>
					  </tr>
module_193;
				}
						echo <<<sys_module_end
					   </table>
					</td>
					</tr>
sys_module_end;
						
					}
				}
				//---------------系统管理模块END-------------------//




			//------------------6报表输出BEGIN------------------------//
			if ( in_array( "21" , $pri_content_arr))
				{
					
				   echo <<<module_20
			        <tr> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
					 <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(6);" id="6"><strong>报表输出</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu6" style="DISPLAY: none"><table width="98%" border="0">
			
					  <tr> 
						<td height="20" ><a href="top.php?location=syk" target="top" onClick="parent.right.location.href='backup_table/course_table.php'">实验课开出情况</td>
					  </tr>
					  <tr> 
						<td height="20" ><a href="top.php?location=syxm" target="top" onClick="parent.right.location.href='backup_table/sname_table.php'">实验项目开出情况汇总表</td>
					  </tr>
					  <tr> 
						<td height="20" ><a href="top.php?location=lyl" target="top" onClick="parent.right.location.href='backup_table/room_table.php'">实验室利用率一览表</td>
					  </tr>
					  <tr> 
						<td height="20" ><a href="top.php?location=syjc" target="top" onClick="parent.right.location.href='backup_table/sbook_table.php'">实验教材讲义、指导书总表</td>
					  </tr>
					  <tr> 
						<td height="20" ><a href="top.php?location=gzry" target="top" onClick="parent.right.location.href='backup_table/user_table.php'">实验室工作人员情况表</td>
					  </tr>
					  <tr> 
						<td height="20" ><a href="top.php?location=xrs" target="top" onClick="parent.right.location.href='backup_table/XRS_table.php'">实验教学人时数统计表</td>
					  </tr>
					  <tr> 
						<td height="20" ><a href="top.php?location=dzh" target="top" onClick="parent.right.location.href='backup_table/result.php'">多组合输出汇总表</td>
					  </tr>
			            </table></td>
			        </tr>				  
module_20;
				}
			//------------------报表输出END--------------------------//

			//------------------------系统维护-------------------------//
			if(in_array( "22" , $pri_content_arr) or in_array( "23" , $pri_content_arr))
			{
				//
				echo <<<sys_module_begin
			        <tr bgcolor="#FFFFFF"> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
			        
			         <tr> 
			        <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(7);" id="7"><strong>系统维护</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu7" style="DISPLAY: none"><table width="98%" border="0">
sys_module_begin;
				//---------------21、日志BEGIN--------------------//
				if ( in_array( "22" , $pri_content_arr) )
				{
				  echo <<<module_21
	              <tr> 
					<td height="20" ><a href="top.php?location=rz" target="top" onClick="parent.right.location.href='log/log.php'">日志</td>
				  </tr>
module_21;
				}
				//---------------21、日志END--------------------//

				//---------------22删除本学期数据END--------------------//
				if ( in_array( "23" , $pri_content_arr) )
				{
				  echo <<<module_22
	              <tr>
					<td height="20" ><a href="top.php?location=sjgl" target="top" onClick="parent.right.location.href='log/delete.php'">登记表数据管理</td>
				  </tr>
module_22;
				}
				//---------------22删除本学期数据END--------------------//
				echo <<<sys_module_end
            	   </table>
				</td>
        		</tr>
sys_module_end;
			}
			//------------------------系统维护-------------------------//

			//------------------------8发送邮件-------------------------//
			if(in_array( "24" , $pri_content_arr))
			{
				//
				echo <<<sys_module_begin
			        <tr bgcolor="#FFFFFF"> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
			        
			         <tr> 
			        <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(8);" id="8"><strong>发送邮件</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu8" style="DISPLAY: none"><table width="98%" border="0">
sys_module_begin;
				  echo <<<module_23
	              <tr> 
					<td height="20" ><a href="top.php?location=mail" target="top" onClick="parent.right.location.href='mail/send_email.php'">发送邮件</td>
				  </tr>
module_23;
				
				echo <<<sys_module_end
            	   </table>
				</td>
        		</tr>
sys_module_end;
			}
			//------------------------8发送邮件-------------------------//

			//---------------------------9关于-------------------------------//
			if(in_array( "25" , $pri_content_arr))
			{
				//
				echo <<<sys_module_begin
			        <tr bgcolor="#FFFFFF"> 
			          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
			        </tr>
			        
			        <tr> 
			          <td height="25" background="images/left_13.gif" style="PADDING-LEFT: 5px;CURSOR: hand" onClick="javascript:showsubmenu(9);" id="9"><strong>系统关于</strong></td>
			        </tr>
			        <tr> 
			          <td height="25" align="center" bgcolor="#b5d3f7" id="submenu9" style="DISPLAY: none"><table width="98%" border="0">
sys_module_begin;
			echo <<<module_24
				<tr> 
				<td height="20">       
                	实验课程安排系统
                    <br/>
      				设计与维护：
                    <br/>
       				指导老师：陈杨杨
                    <br/>
       				学生：陈灿、甘伟莨、颜仁亮
       				<br/>计算机科学与技术实验教学中心
       				<br/>电话：22861880
       				<br/>Copyright 
                </td>
				</tr>
module_24;
				
				echo <<<sys_module_end
            	   </table>
				</td>
        		</tr>
sys_module_end;
			}
			
	}//end if
			//---------------------------9关于-------------------------------//

}//function END
//*/
?>
<html>
<head>
<title>框架左侧页面</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<base target="right">
<link rel="stylesheet" href="css/default.css" type="text/css">
<STYLE type=text/css>
.point {
	FONT-SIZE: 12px; CURSOR:pointer; COLOR: #ffffff; FONT-FAMILY: Webdings; POSITION: absolute; BACKGROUND-COLOR: #7396bd
}
.STYLE1 {
	color: #004891;
	font-size: 12px;
}
</STYLE>
<script language=Javascript>

function changeWin(){
	if(parent.main.cols!="175,*")
	{
		parent.main.cols="175,*";
		document.all.menu.style.display="block";
		document.all.id1.style.width=158;
		document.all.menuSwitch.innerHTML="<font class=point>3</font>";
	}
	else
	{
	parent.main.cols="15,*";
		document.all.menu.style.display="none";
		document.all.id1.style.width=5;
		document.all.menuSwitch.innerHTML="<font class=point>4</font>";	
	}
}
</SCRIPT>
</head>
<body bgcolor="7396bd" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
//2010-6-20根据用户权限代码生成js文件//
//
//关于实验室负责人有关权限的导航输出问题--
//根据设定的权限，用户管理、实验室申请、关于是确定导航输出--
//而实验室安排、统计分析、数据库操作、系统维护是根据管理员的设定而选择性输出

echo "<script language=Javascript>";
echo 'function showsubmenu(sid)
{
	whichEl = eval("submenu" + sid);
	if (whichEl.style.display == "none")
	{';
			$user_code = $n;//获取用户权限代码
			$get_pri_content_rs = mysql_query ( "SELECT * FROM privilege WHERE p_role_code = '$user_code' " ) or die ( "SQL语句执行失败:" . mysql_error() );
			$get_pri_content_row = mysql_fetch_array( $get_pri_content_rs );
		
			$pri_content_str = $get_pri_content_row[p_content];//获取权限内容字符串
		
			$pri_content_arr = explode( "," , $pri_content_str); //生成权限列表数组
			//1用户管理1-3
			if(in_array( "1" , $pri_content_arr) or in_array( "2" , $pri_content_arr) or in_array( "3" , $pri_content_arr))
				{
					echo 'eval("submenu1.style.display=\"none\";");
					document.getElementById(1).background="images/left_13.gif";';
				}
			//2实验室申请4-9
			if(in_array( "4" , $pri_content_arr) or in_array( "5" , $pri_content_arr) or in_array( "6" , $pri_content_arr) or in_array( "7" , $pri_content_arr) or in_array( "8" , $pri_content_arr) or in_array( "9" , $pri_content_arr) or in_array( "9" , $pri_content_arr) or in_array( "10" , $pri_content_arr))
			{
				echo 'eval("submenu2.style.display=\"none\";");
				document.getElementById(2).background="images/left_13.gif";';
			}
			//3实验室安排10
			if(in_array( "11" , $pri_content_arr) )
			{
				echo 'eval("submenu3.style.display=\"none\";");
				document.getElementById(3).background="images/left_13.gif";';
			}
			//4统计分析11-16(总体安排、实验室安排、房间安排、周次安排、学时、人时)
			if(in_array( "12" , $pri_content_arr) or in_array( "13" , $pri_content_arr) or in_array( "14" , $pri_content_arr) or in_array( "15" , $pri_content_arr) or in_array( "16" , $pri_content_arr) or in_array( "17" , $pri_content_arr))
			{
				echo 'eval("submenu4.style.display=\"none\";");
				document.getElementById(4).background="images/left_13.gif";';
			}
			//5数据库操作18-20------分三部分---1基本信息--2数据备份--3信息表
			if(in_array( "18" , $pri_content_arr) or in_array( "19" , $pri_content_arr) or in_array( "20" , $pri_content_arr))
			{
				echo 'eval("submenu5.style.display=\"none\";");
				document.getElementById(5).background="images/left_13.gif";';
			}
			//6报表输出21
			if(in_array( "21" , $pri_content_arr) )
			{
				echo 'eval("submenu6.style.display=\"none\";");
				document.getElementById(6).background="images/left_13.gif";';
			}
			//7系统维护22-23
			if(in_array( "22" , $pri_content_arr) or in_array( "23" , $pri_content_arr))
			{
				echo 'eval("submenu7.style.display=\"none\";");
				document.getElementById(7).background="images/left_13.gif";';
			}
			//8发送邮件24
			if(in_array( "24" , $pri_content_arr))
			{
				echo 'eval("submenu8.style.display=\"none\";");
				document.getElementById(8).background="images/left_13.gif";';
			}
			//9关于25
			if(in_array( "25" , $pri_content_arr))
			{
				echo 'eval("submenu9.style.display=\"none\";");
				document.getElementById(9).background="images/left_13.gif";';
			}
			echo 'eval("submenu" + sid + ".style.display=\"\";");
			document.getElementById(sid).background="images/left_unfold.gif";
	}
	else
	{
		eval("submenu" + sid + ".style.display=\"none\";");
		document.getElementById(sid).background="images/left_13.gif";
	}
}';
echo "</script>";

?>       


<SCRIPT type=text/javascript src="js/jquery-1.2.6.js"></SCRIPT> <!--Jquery原文档-->
<SCRIPT type=text/javascript src="js/jquery.corner.js"></SCRIPT> <!--圆角插件-->
<SCRIPT type=text/javascript>
	$(function(){
        $('div.demo').each(function() {
             $(this).corner("tr 10px").corner("br 10px");
        });
	});
</SCRIPT>
<!-- 下面为相关的菜单子栏目输出 -->
<table width="158" height="430" border="0" cellpadding="0" cellspacing="0" id="id1">
  <tr> 
    <td valign="top"><br/>
      <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" id="menu" >
	  <tr> 
            <td height="25" align="center" bgcolor="#7396bd"> 
			<div id="main" class="demo" style="background-color:#b5d3f7; padding-top:10px; padding-bottom:10px; padding-left:5px; padding-right:5px;"> <!--圆角DIV块-->
            <span style="margin-top:5px; line-height:1.7; color:#FF0000; font-size:14px;">
				您好,<?php echo $_SESSION["u_name"];?> 老师<br />
				欢迎使用本系统
            </span> 
			<br/>
          <span height="25" align="center">
            	<a href="frame.php" target="_parent">首页</a>&nbsp;｜
                <a href="common/logout.php">注销</a>&nbsp;｜
                <a href="common/logout_close.php" onClick=" return confirm('您真的要退出系统吗？')">退出</a>
          </span>
          </div>
          </td>
		<!--引入导航建立脚本BEGIN-->
		<?php 
			$user_code = $n;//获取用户权限代码，注意按实际更改
			buildNavigation($user_code , $publish_state);//执行函数
		?>		
		<!--引入导航建立脚本END---->
        <tr> 
          <td height="5" align="center" bgcolor="7396bd">&nbsp;</td>
        </tr>
	  

      </table>
    </td>
    <td onClick="changeWin()" id="menuSwitch" valign="middle"> 
      <font class=point>3</font> </td>
  </tr>
</table>
</body>
</html>