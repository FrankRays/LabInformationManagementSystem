<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<style type="text/css">
ol{
list-style-type:none;
}
li{
border-bottom:1px dashed #3399FF;
padding-top:5px;
line-height:30px; 
}
</style>

<title>读取excel并插入数据表manage</title>

</head>
<body>
<div id="formwrapper" style="width:90%"><!--表单外边框DIV_BEGIN-->
<form name="getfile" method="post" action="course_register_edit_upload.php" enctype="multipart/form-data">
<fieldset><!--表单内边框DIV_BEGIN-->
  <legend>上传登记表</legend><!--表单内边框标题-->
<div style="line-height:40px">请选择要上传的excel文件：&nbsp;<input name="inputExcel" type="file" /></div>
 <input name="sub" type="submit" value="提交"  class="buttonSubmit" />
 <input name="ret" type="button" value="返回" onclick="history.back();"  class="buttonSubmit" />
</fieldset>
<br>
</form>
<form name="upres">
 <fieldset><!--表单第二个内边框DIV_BEGIN-->
  <legend>上传结果</legend><!--表单内边框标题-->
<?php 
     include("../common/db_conn.inc");
	
     include("../common/valid_time_range.inc");
		/*----------------------------------------------------
		引入后提供的变量如下:
		$first_week_date				自定义的第一周时间
		$date_year						自定义时间中的年
		$date_month						自定义时间中的月
		$date_month_int					数字化后的月份
		$date_day						自定义时间中的日
		$now_week						计算后得到的周次
		$valid_time_range_begin_date	有效时间区间的开始日期
		$valid_time_range_end_date		有效时间区间的结束日期
		$table_title					当前的年度及学期标题
		-----------------------------------------------------*/
        
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	{
	   echo "<script language='javascript'>alert('你无权进行此操作！');
						 location.href='index.html';</script>";
	   exit();
	}
		
   if($_POST['sub'])    //有文件上传执行操作
   {
	  $filename = $_FILES['inputExcel']['name']; 
	  $uploadfile = $_FILES['inputExcel']['tmp_name']; //文件被上传后在服务端储存的临时文件名
	  //echo $uploadfile;
	  //下面的路径按照你PHPExcel的路径来修改
	  require_once('./phpexcel/PHPExcel.php');
	  require_once('./phpexcel/PHPExcel/IOFactory.php');
	  require_once('./phpexcel/PHPExcel/Reader/Excel5.php');
	  require_once('./phpexcel/PHPExcel/Reader/Excel2007.php');
   
	  $extend=strrchr ($filename,'.'); //获取上传文件的扩展名
	  if($extend != ".xls" && $extend != ".xlsx")  //判断是不是excel文件
	  {
	      echo "<br></fieldset></form><br></div>";
		  echo "<script language=javascript>alert('您上传的不是EXCEL文件!');history.back();</script>";
		  exit();
	  }
	  //move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false
	  //$result=move_uploaded_file($tmp_name,$uploadfile);//假如上传到当前目录下
	  if(is_uploaded_file($uploadfile))   //如果是上传文件，就执行导入excel操作$result
	  {
		  $extend == ".xlsx" ? $reader_type = "Excel2007":$reader_type = "Excel5";
		  $objReader = PHPExcel_IOFactory::createReader($reader_type);  //设置读取格式
		  $objPHPExcel = $objReader->load($uploadfile);          //加载excel文件
		  $objWorksheet = $objPHPExcel->getSheet(0);          //读取一表
		  $highestRow = $objWorksheet->getHighestRow();       //取得总行数
		  $highestColumn = $objWorksheet->getHighestColumn(); //取得总列
          $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数转换成数字
		  $objWorksheet = $objPHPExcel->getActiveSheet();
		  
		  //获取excel表中的房间号（数组）
		  //定义列对应的字段名
		  	  
		  echo "<ol>";
		  for ($row = 1; $row <= $highestRow; $row++)
		  {  
		      unset($showstr,$excelData,$arrdata,$weekdayclass);
			  $arrdata=array();
			  for ($col = 0; $col < $highestColumnIndex; $col++)
			  {   			   
				$excelData = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				if($excelData=="*")  //*标记为暂时空
				{ $excelData="无"; }
				else if($excelData=="")  //处理表格合并情况，当表格检测为空时，自动使用上一个单元格的内容
				{
				  $excelData=$objWorksheet->getCellByColumnAndRow($col, $row-1)->getValue();
				  $objWorksheet->getCellByColumnAndRow($col, $row)->SetValue($excelData);  //复制上一个单元格值到当前单元格
				} 
				
				 $arrdata[$col]=$excelData;
				//$showstr.="&nbsp;".$excelData."&nbsp;-";						
			  } 
			  
			  if($row==1)  //第一行为标题,不存入数组
			  {
			    echo "<li>".implode("-",$arrdata)."</li>";
			  }
			  else
			  {
			  
			    /*--------------------------判断课程名称是否在规定范围内BEGIN--------------------------------*/
					$cname_check_result = mysql_query("SELECT c_cname FROM v_cname WHERE c_cname='$arrdata[1]'");
					$cname_check_result_row_num = mysql_num_rows($cname_check_result);//取得结果的行数
					if ($cname_check_result_row_num == 0 ) //没有结果，即还没有该课程的安排
					{						
				      echo "<li>".($row-1).".&nbsp;".implode("-",$arrdata)."&nbsp;&nbsp;<font style=\"color:red;\">本系统中目前还没有课程，跳过···</font></li>";
					}
			     else{
			  
			   $sql = "SELECT a_id FROM `apply1` WHERE a_rname = '$arrdata[0]' AND a_sid = '$arrdata[5]' AND a_cname = '$arrdata[1]' AND a_grade='$arrdata[10]' AND a_major='$arrdata[11]' AND a_date BETWEEN '{$valid_time_range_begin_date}' AND '{$valid_time_range_end_date}'";
               $result = mysql_query ( $sql ) or die ( mysql_error() );  //向数据库执行SQL语句		 
			 //  echo "<hr>".$sql.mysql_num_rows($result)."<hr>";      
               if(mysql_num_rows($result) > 0)//判断是否重复申请
               { 
			      echo "<li>".($row-1).".&nbsp;".implode("-",$arrdata)."&nbsp;&nbsp;<font style=\"color:red;\">记录有冲突，跳过···</font></li>";
			   }
			   else  //没有重复数据则添加到apply1和time表中
			   {    	  
				  //处理第八列的 周-星期-节次，格式（1/4/1-4#2/2/4-6）
				  /*--------------------------获取课程名字对应的实验室方向BEGIN------------------------*/
                  $c_result = mysql_query ( "SELECT c_room FROM `course` WHERE c_cname = '$arrdata[1]'" );
                  $c_array = mysql_fetch_array ( $c_result ); 
                  $direction = $c_array['c_room'];
				  
				  $inapply="INSERT INTO apply1(a_rname,a_cname,a_cdirection,a_ctype,a_sbook,a_sid,a_sname,a_stype,a_grade,a_major,a_class,a_people,a_learntime,a_stime,a_resources,a_system,a_software,a_date)  VALUES('$arrdata[0]','$arrdata[1]', {$direction},'$arrdata[2]','$arrdata[3]','$arrdata[4]',$arrdata[5],'$arrdata[6]','$arrdata[7]','$arrdata[10]','$arrdata[11]','$arrdata[12]',$arrdata[13],0,$arrdata[14],$arrdata[15],'$arrdata[16]',CURDATE())";
				  $inres=mysql_query($inapply);
//				  if($inres) { echo "插入成功";}
//				  else { echo "插入失败！";}
				  $aid=mysql_insert_id();
			 		  
				 $weekdayclass=str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), $arrdata[8]);	  //删除该单元格的所有空格
				 //echo $weekdayclass;
				 unset($arr_weekdayclass);
				 $arr_weekdayclass=explode("#",$weekdayclass);
				 $count=count($arr_weekdayclass);  
				 
				 unset($arrclass,$stime);
				 for($i=0; $i<$count; $i++)  //开始循环插入数据
				 {
				   $class="";
				   list($week,$date,$class)=explode("/",$arr_weekdayclass[$i]);   //周 星期 节次
				   if($week>20||$week<=0) {echo $week;  echo "周数范围不正确！"; exit();}
				   if($date>8||$week<=0)  { echo "星期范围不正确！"; exit();}echo $class;
				   switch($class) //根据节次拆分成逗号形式
				   {
					   case '1-2': $class="1,2"; break; 
					   case '3-4': $class="3,4"; break; 
					   case '5-6': $class="5,6"; break;
					   case '7-8': $class="7,8"; break; 
					   case '1-3': $class="1,2,3"; break; 
					   case '2-4': $class="2,3,4"; break; 
					   case '5-7': $class="5,6,7"; break; 
					   case '6-8': $class="6,7,8"; break; 
					   case '1-4': $class="1,2,3,4"; break; 
					   case '5-8': $class="5,6,7,8"; break; 
					   case '9-10': $class="9,10"; break; 
					   case '1-8': $class="1,2,3,4,5,6,7,8"; break; 
					   default: echo "节次形式不正确！"; exit();
				   }
                    $arrclass.=$class.",";  //获取所有的几次，计算实际学时

				   //根据插入结果输出提示
				   $intime="INSERT INTO time(a_id,s_id,a_sweek,a_sdate,a_sclass,a_room) VALUES($aid,$arrdata[5],$week,$date,{$class},{$arrdata[8]})"; //echo $intime."eee";
				   $intimeres=mysql_query($intime);
				   $arrdata[9]=$week.'/'.$date.'/'.$class;
				   if($intimeres)
				   { 
				     echo "<li>".($row-1).".&nbsp;".implode("-",$arrdata)."&nbsp;<font style=\"color:blue;\">插入成功···</font></li>";
				   }
				   else
				   {		     
				     echo "<li>".($row-1).".&nbsp;".implode("-",$arrdata)."&nbsp;<font style=\"color:red;\">插入失败···</font></li>";
				   }
				   unset($intimeres);
				 }
				  $arrclass.=$class.",";  //获取所有的节次，计算实际学时
				  $arrclass=substr($arrclass,0,strlen($arrclass)-1);
				  $stime=count(explode(',', $arrclass));  //获得实际学时
				  mysql_query("UPDATE apply1 SET a_stime={$stime} WHERE a_id={$aid}");  //更新实际学时
			  	  
			  }
			}
		   }
		  }	  
		   echo "<li><strong>&nbsp;".$filename."文件导入完毕！</strong></li>";    
		   echo "</ol>";    
	  }
  }
  
?>
&nbsp;<br>
</fieldset>
</form>
<br>
</div>
</body>
</html>
