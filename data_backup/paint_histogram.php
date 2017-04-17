<?php
include ('../common/histogram_class.inc');

$room_arr_trans = $_GET['room_arr_trans'];//获取URL的房间号数组参数
$ana_result_arr_trans = $_GET['ana_result_arr_trans'];//获取URL的统计结果数组参数

//将结果重新组合成数组的形式
$ana_result_arr = explode(",", $ana_result_arr_trans);
$room_arr = explode(",", $room_arr_trans);

//开始绘制图形
$bar = new Bar(700, 350, $ana_result_arr , $room_arr);
$bar->setTitle('                     统计结果的柱状图如下所示：');
$bar->stroke();
?>


