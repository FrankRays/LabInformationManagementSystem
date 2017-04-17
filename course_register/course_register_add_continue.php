<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

	include("../common/db_conn.inc");
	include("../common/session.inc");//$_SESSION["u_name"]和$_SESSION["u_type"] 
	if($n<1) 
	echo "<script language='javascript'>alert('你无权进行此操作！');
					     location.href='index.html';</script>";


	$teachername = $_SESSION["u_name"];	//获取真实名称
	$usercode = $_SESSION["u_type"];	//获取权限代码
	
	$a_id = $_GET['a_id'];

	$sql = sprintf ( "SELECT * FROM apply1 WHERE a_id='%d'",$a_id);
	$rs = mysql_query ( $sql ) or die ( "获取a_id时发生错误:" . mysql_error() );
	$row = mysql_fetch_array( $rs );


?>
<head>

<script language="javascript">
 <!--
//屏蔽selected的bug
function ResumeError() { 
return true; 
} 
window.onerror = ResumeError; 
//-->  
</script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/validator.js"></script><!--表单验证JS-->
<script type="text/javascript" src="../js/jquery-1.2.6.js"></script><!--引入JQuery-->
<link rel="stylesheet" type="text/css" media="screen" href="../css/form_frame.css"><!--边框美化CSS-->
<script language="javascript" type="text/javascript" src="../js/niceforms.js"></script><!--表单美化JS-->
<link rel="stylesheet" type="text/css" href="../css/niceforms-default.css"><!--表单美化CSS-->
<!-- script language="javascript" type="text/javascript" src="../js/select2css.js"></script><!--表单下拉列表美化JS-->
<!--link rel="stylesheet" type="text/css" href="../css/select2css.css"><!--表单下拉列表美化CSS-->

<title>继续填写登记表</title>

<!-----------------------------------动态添加时间段BEGIN---------------------------------->
	<script language="javascript" type="text/javascript">
	var new_time_id=1;
	
	function add_time(){ 
		$("#active_add").append(
		'<p id="'+new_time_id+'"><label for="sweek[]">实验时间——周次： </label>' +
		'<select name="sweek[]">' +
		'<option value="1" selected>1</option>' +
		'<option value="2">2</option>' +
		'<option value="3">3</option>' +
		'<option value="4">4</option>' +
		'<option value="5">5</option>' +
		'<option value="6">6</option>' +
		'<option value="7">7</option>' +
		'<option value="8">8</option>' +
		'<option value="9">9</option>' +
		'<option value="10">10</option>' +
		'<option value="11">11</option>' +
		'<option value="12">12</option>' +
		'<option value="13">13</option>' +
		'<option value="14">14</option>' +
		'<option value="15">15</option>' +
		'<option value="16">16</option>' +
		'<option value="17">17</option>' +
		'<option value="18">18</option>' +
		'<option value="19">19</option>' +
		'<option value="20">20</option>' +
		'</select>' +
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
		'<label for="sdate[]">星期：</label> ' +
		'<select name="sdate[]">' +
		'<option value="1" selected>一</option>' +
		'<option value="2">二</option>' +
		'<option value="3">三</option>' +
		'<option value="4">四</option>' +
		'<option value="5">五</option>' +
		'<option value="6">六</option>' +
		'<option value="7">日</option>' +
		'</select>' +
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
		'<label for="sclass[]">节次：</label> ' +
		'<select name="sclass[]">' +
		'<option value="1,2" selected>1-2节</option>' +
		'<option value="3,4">3-4节</option>' +
		'<option value="5,6">5-6节</option>' +
		'<option value="7,8">7-8节</option>' +
		'<option value="1,2,3">1-3节</option>' +
		'<option value="2,3,4">2-4节</option>' +
		'<option value="5,6,7">5-7节</option>' +
		'<option value="6,7,8">6-8节</option>' +
		'<option value="1,2,3,4">1-4节</option>' +
		'<option value="5,6,7,8">5-8节</option>' +
		'<option value="9,10">9-10节</option>' +
		'<option value="1,2,3,4,5,6,7,8">1-8节</option>' +
		'</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
		'<img src="../images/del_time.gif"  style="cursor:hand;" ' +
		'onClick="remove_time(this.parentNode.parentNode,' + new_time_id + ')"/>' +
		'</p>'
		 );
		 new_time_id++;
	}
	
	function remove_time(obj,iid){
		oP = $("p[id="+iid+"]").remove();
	}
	
	</script>
<!-----------------------------------动态添加时间段END------------------------------------>


</head>
<body>


<div id="formwrapper"><!--表单外边框DIV_BEGIN-->


    <form action="course_register_deal.php" method="post" onSubmit="return Validator.Validate(this,3)">
		<fieldset><!--表单第一个内边框DIV_BEGIN-->
        <legend>课程基本信息</legend><!--表单内边框标题-->
        <p>
        <?php
            //根据权限确定用户是否可以输入教师姓名
            if( $usercode=='1' )
            {
                echo "教师姓名：$teachername";
            }
                else if( $usercode=='4' or $usercode=='5')
                {
                    echo '教师姓名：<input name="form_rname" type="text" dataType="Require" msg="教师姓名不能为空" value="'.$row['a_rname'].'" />';
                }
        ?>
        </p>
        
        <p>
        <!--不可修改-->
        课程：<?= $row['a_cname']?><input name="cname" type="hidden" value="<?= $row['a_cname']?>" />
        </p>
    
    
        <p>课程类别：
            <select name="cdirection">
            <?php
                $sql = "SELECT c_direction FROM `course` GROUP BY c_direction";
                $result = mysql_query ( $sql )  or die ( "不能查询指定的数据库表：" . mysql_error() );
                while( $output = mysql_fetch_object($result) )
                {
                    if($row['a_ctype']==$output->c_direction)//判断默认选项
                    {
                        echo '<option value="'.$output->c_direction.'" selected>'.$output->c_direction.'</option>';
                    }
                    echo '<option value="'.$output->c_direction.'">'.$output->c_direction.'</option>';
                }
            ?>
            </select>
        </p>

		<p><label for="sbook">实验教材：</label>
			<textarea name="sbook" rows="2" cols="50" dataType="Require" msg="实验教材详细情况不能为空" ><?php echo $row['a_sbook'];?></textarea></p>   <!--添加一个实验教材-->
     
        <p>&nbsp;</p>
		<p>
            <label for="grade">年级：</label>
            <input name="grade" type="text" value="<?php echo $row['a_grade'];?>" size="10" dataType="Require" msg="年级不能为空" />
            <label for="major">专业：</label>
            <input name="major" type="text" value="<?= $row['a_major']?>" size="10" dataType="Require" msg="专业不能为空" />
            <label for="class">班别：</label>
            <input name="class" type="text" value="<?= $row['a_class']?>" size="10" dataType="Require" msg="班别不能为空" />
        </p>
		</fieldset><!--表单第一个内边框DIV_END-->


		<fieldset><!--表单第二个内边框DIV_BEGIN-->
        <legend>实验基本信息</legend><!--表单内边框标题-->
    
    
        <p>
            <label for="sid">实验编号：实验</label>
            <input name="sid" type="text" value="<?php $row['a_sid']=$row['a_sid']+1; echo $row['a_sid']; ?>" size="2" />
        </p>
    
        <p>
            <label for="sname">实验项目：</label>
            <input name="sname" type="text" dataType="Require" msg="实验项目不能为空" value=""  class="textinput" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label for="stype">实验类型：</label>
                <select name="stype">
                <!--演示、验证、综合、设计研究、其它 -->
                <option value="演示型" <?php if($row['a_stype']=='演示型'){echo 'selected';}?>>演示型</option>
				<option value="验证型" <?php if($row['a_stype']=='验证型'){echo 'selected';}?>>验证型</option>
				<option value="综合型" <?php if($row['a_stype']=='综合型'){echo 'selected';}?>>综合型</option>
				<option value="设计研究型" <?php if($row['a_stype']=='设计研究型'){echo 'selected';}?>>设计研究型</option>
				<option value="其他" <?php if($row['a_stype']=='其他'){echo 'selected';}?>>其他</option>
                </select>
        </p>
    
        <div id ="active_add">
            <p>
            	<label for="sweek[]">实验时间——周次：</label>
                <select name="sweek[]">
                <?php
                    for($i=1 ; $i<=20 ; $i++)
                    {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                ?>
                </select>
         
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label for="sdate[]">星期：</label>
                <select name="sdate[]">
                <option value="1" selected>一</option>
                <option value="2">二</option>
                <option value="3">三</option>
                <option value="4">四</option>
                <option value="5">五</option>
				<option value="6">六</option>
				<option value="7">日</option>
                </select>
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label for="sclass[]">节次：</label>
                <select name="sclass[]">
                <option value="1,2" selected>1-2节</option>
                <option value="3,4">3-4节</option>
                <option value="5,6">5-6节</option>
                <option value="7,8">7-8节</option>
                <option value="1,2,3">1-3节</option>
                <option value="2,3,4">2-4节</option>
                <option value="5,6,7">5-7节</option>
                <option value="6,7,8">6-8节</option>
                <option value="1,2,3,4">1-4节</option>
                <option value="5,6,7,8">5-8节</option>
				<option value="9,10">9-10节</option>
				<option value="1,2,3,4,5,6,7,8">1-8节</option>
                </select>
                
                　<!--＋<a href="#"  onClick="add_time()">为该实验项目增加一个时间</a>＋-->
                <img src="../images/add_time.gif"  style="cursor:hand;" onClick="add_time()"/>
            </p>
        
        </div><!--动态添加End-->
        
      
        <p>
            <label for="tu_num">人数：</label>
            <input name="stu_num" type="text" value="<?= $row['a_people']?>" size="2" dataType="Number" msg="请填写数字" dataType="Number" msg="请用半角数字填写人数" />（请用阿拉伯数字填写）
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label for="learntime">计划学时：</label>
            <input name="learntime" type="text" value="<?= $row['a_learntime']?>" size="2" dataType="Require" msg="计划学时不能为空"/>
			<!-- &nbsp;&nbsp;&nbsp;&nbsp;
			<label for="stime">实际学时：</label>
            <input name="stime" type="text" value="<?= $row['a_learntime']?>" size="2" dataType="Require" msg="实际学时不能为空"/> -->
        </p>   
        
        
        <p>
        <label for="resources">耗材需求：</label>
        <textarea name="resources" rows="2" cols="50" dataType="Require" msg="耗材需求不能为空"><?= $row['a_resources']?></textarea></p>
        
        <p>
        <label for="system">系统需求：</label>
        <textarea name="system" rows="2" cols="50" dataType="Require" msg="系统需求不能为空"><?= $row['a_system']?></textarea></p>
        
        <p>
        <label for="software">软件需求：</label>
        <textarea name="software" rows="2" cols="50" dataType="Require" msg="软件需求不能为空"><?= $row['a_software']?></textarea></p>
            
        
    
		</fieldset><!--表单第二个内边框DIV_END-->
        <p>
            <input name="btnSubmit" type="submit" value="提交"  class="buttonSubmit" />
			<input name="" type="button" value="返回" onclick="history.back();"  class="buttonSubmit" />
        </p>
    </form>

</div><!--表单外边框DIV_END-->

</body>
</html>