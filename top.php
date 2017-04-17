<?php
include("./common/session.inc");
include("./common/db_conn.inc");
include("./common/get_first_week_date.inc");
/*----------引入后提供的变量如下:--------------
$frist_week_date	自定义的第一周时间
$date_year			自定义时间中的年
$date_month			自定义时间中的月
$date_day			自定义时间中的日
$now_week			计算后得到的周次	
-----------------------------------------------*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
<!--
body{
	background-color:#2591D1;
	 border:0; 
	 margin:0;
	 font-size:14px;
	 color:#e5f1f4;
}


a:link {/* 未访链接的定义 */
	color: #d0eee3;
	text-decoration: none;
	}
a:visited {/* 已访链接的定义 */
	color: #d0eee3;
	text-decoration: none;
	}
a:hover, a:active {/* 鼠标放到链接时的定义 */
	color: #0000FF;
	text-decoration: none;
	border-bottom-width: 1px;
	border-bottom-style: dotted;
	border-bottom-color: #0000FF;
	}
-->
</style>

<title>顶部页面</title>
</head>

<body>

	<!--<div style=" position:absolute; width:1002px; left:50%;	margin-left:-501px; z-index:-1; "> -->
	<div style=" position:absolute; width:100%; text-align:center; z-index:-1; ">
    	<img src="images/banner.gif"> 
	</div>
	<div style="border:0; margin-top:-15px">
    	<span style="float:left;margin-top:50px; margin-right:10px;"><img src="images/position.gif"></span>
		<span style="float:left;margin-top:60px; border:0;">
		当前位置：		</span>
        <!--span style="float:right;margin-top:50px; "><img src="images/logout.gif"></span>
		<span style="float:right;margin-top:60px; border:0;">
		<a href="common/logout.php">退出系统</a>
		</span-->
        <span style="float:right;margin-top:50px; margin-left:10px;"><img src="images/clock.gif"></span>
        <span style="margin-left:10px; float:right;margin-top:60px; border:0;"><?=$now_week_output ?></span>
        <script src="js/showTime.js"></script>
        <span style="float:left;margin-top:60px; border:0;">
        <?php 
		if ( empty($_GET[location]) ) echo "首页";
		 else 
		 {
		 	switch($_GET[location])
		 	{
		 		case 'tjyh':  $link_location = '用户管理>>添加用户';break;
		 		//case 'xggrxx':  $link_location = '用户管理>>修改个人信息';break;
		 		//case 'scyh':  $link_location = '用户管理>>删除用户';break;
		 		case 'xggrxx':  $link_location = '用户管理>>修改个人信息';break;
		 		case 'qxgl':  $link_location = '用户管理>>权限管理';break;
		 		
		 		case 'txdjb':  $link_location = '实验室申请>>填写登记表';break;
		 		case 'xgdjb':  $link_location = '实验室申请>>修改登记表';break;
				case 'scdjb':  $link_location = '实验室申请>>上传登记表';break;
		 		//case 'scdjb':  $link_location = '实验室申请>>删除登记表';break;
		 		case 'cxdjb':  $link_location = '实验室申请>>查询登记表';break;
		 		case 'fkxxb':  $link_location = '实验室申请>>反馈信息表';break;
		 		case 'scjxzl': $link_location = '实验室申请>>上传教学周历';break;
		 		case 'cxjb':   $link_location = '实验室申请>>查询相关申请简表';break;
		 		case 'cxdgxxb':$link_location = '实验室申请>>查询单个实验课程信息表';break;
		 		case 'hzbrsgxxb':$link_location = '实验室申请>>汇总与本人相关的信息表';break;			 		
		 		case 'hzqbxxb':$link_location = '实验室申请>>汇总全部实验课程信息表';break;
		 		case 'xgjxzl':$link_location = '实验室申请>>相关教学周历';break;
		 		
		 		case 'ctjc':   $link_location = '实验室安排>>冲突检测';break;
		 		
		 		case 'ztapb': $link_location = '统计分析>>总体安排表';break;
				case 'lsztapb': $link_location = '统计分析>>历史总体安排表';break;
		 		case 'asysapb': $link_location = '统计分析>>按实验室安排表';break;
		 		case 'afjapb': $link_location = '统计分析>>按房间安排表';break;
		 		case 'azcapb': $link_location = '统计分析>>按周次安排表';break;
		 		case 'xsfxb': $link_location = '统计分析>>学时数分析';break;
		 		case 'rsfxb': $link_location = '统计分析>>人时数分析';break;
				case 'hzjsdg': $link_location = '统计分析>>实验课信息登记表汇总';break;		 		
		 		
				case 'szrq': $link_location = '数据库操作>>基本信息管理>>第一周日期';break;
		 		case 'kcfxfl': $link_location = '数据库操作>>基本信息管理>>课程方向分类';break;
		 		case 'sysjbxx': $link_location = '数据库操作>>基本信息管理>>实验室基本信息';break;
		 		
		 		case 'bfyhxxb': $link_location = '数据库操作>>数据备份>>用户信息表';break;
		 		case 'bfdgsykcxxb': $link_location = '数据库操作>>数据备份>>单个实验课程信息表';break;
		 		case 'bfqbsykcxxb': $link_location = '数据库操作>>数据备份>>全部实验课程信息表';break;
		 		case 'bfztapb': $link_location = '数据库操作>>数据备份>>总体安排表';break;
		 		//case 'bffxb': $link_location = '系统维护>>数据备份>>分析表';break;
		 		case 'bfqjsjk': $link_location = '数据库操作>>数据备份>>全局数据库';break;
		 		case 'drsjk': $link_location = '数据库操作>>数据备份>>导入数据库';break;

				case 'ayk': $link_location = '报表输出>>实验课开出情况';break;
				case 'syxm': $link_location = '报表输出>>实验项目开出情况汇总表';break;
				case 'lyl': $link_location = '报表输出>>实验室利用率一览表';break;
				case 'syjc': $link_location = '报表输出>>实验教材讲义、指导书总表';break;
				case 'gzry': $link_location = '报表输出>>实验室工作人员情况表';break;
				case 'xrs': $link_location = '报表输出>>实验教学人时数统计表';break;
				case 'dzh': $link_location = '报表输出>>多组合输出总表';break;

				case 'rz': $link_location = '系统维护>>日志';break;
				case 'sjgl': $link_location = '系统维护>>登记表数据管理';break;

				case 'mail': $link_location = '发送邮件>>发送邮件';break;
		 	}	
		 	echo "首页>>".$link_location;
		 }
		?>
        </span><span id="clockLocal" style="float:right;margin-top:60px; border:0;"></span></div>
</body>
</html>
