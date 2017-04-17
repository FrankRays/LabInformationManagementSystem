<?php
include ('../common/histogram_class_for_RS.inc');

$RS_lab_type_arr_trans = urldecode($_GET['RS_lab_type_arr_trans']);//获取URL的房间号数组参数
$RS_num_arr_trans = $_GET['RS_num_arr_trans'];//获取URL的统计结果数组参数

//将结果重新组合成数组的形式
$RS_num_arr = explode(",", $RS_num_arr_trans);
$RS_lab_type_arr = explode(",", $RS_lab_type_arr_trans);

array_pop($RS_num_arr);
array_pop($RS_lab_type_arr);


//开始绘制图形
$bar = new Bar(800, 350, $RS_num_arr , $RS_lab_type_arr);
$bar->setTitle('                各实验室人时数统计结果柱状图如下所示：');
$bar->stroke();
?>


