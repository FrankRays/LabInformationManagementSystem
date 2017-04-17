<?php
include("../common/session.inc"); 
include("../common/db_conn.inc");

//代码开始
$dbname="lab_test"; //选择数据库
$filename="lab_test.sql"; //设置输出文件名称 

function sqldumptable($table) {
global $DB_site;
$tabledump = "DROP TABLE IF EXISTS $table;\n";
$tabledump .= "CREATE TABLE $table (\n";
$firstfield=1;
$fields = mysql_query("SHOW FIELDS FROM $table");
while ($field = mysql_fetch_array($fields)) {
if (!$firstfield) {$tabledump .= ",\n";} else {$firstfield=0;}
$tabledump .= " $field[Field] $field[Type]";
if (!empty($field["Default"])) {$tabledump .= " DEFAULT '$field[Default]'";}
if ($field[Null] != "YES") {$tabledump .= " NOT NULL";}
if ($field[Extra] != "") {$tabledump .= " $field[Extra]";}
}
mysql_free_result($fields);
$keys = mysql_query("SHOW KEYS FROM $table");
while ($key = mysql_fetch_array($keys)) {
$kname=$key['Key_name'];
if ($kname != "PRIMARY" and $key['Non_unique'] == 0) { $kname="UNIQUE|$kname";}
if(!is_array($index[$kname])) { $index[$kname] = array();}
$index[$kname][] = $key['Column_name'];
}
mysql_free_result($keys);

// 获取每个键的内容
while(list($kname, $columns) = @each($index)){
$tabledump .= ",\n";
$colnames=implode($columns,",");

if($kname == "PRIMARY"){ $tabledump .= " PRIMARY KEY ($colnames)";} 
else {
if (substr($kname,0,6) == "UNIQUE") {

// 键值是唯一
$kname=substr($kname,7);
}

$tabledump .= " KEY $kname ($colnames)";

}
}

$tabledump .= "\n);\n\n";

// 读取数据
$rows = mysql_query("SELECT * FROM $table");
$numfields=mysql_num_fields($rows);
while ($row = mysql_fetch_array($rows)) {
$tabledump .= "INSERT INTO $table VALUES(";

$fieldcounter=-1;
$firstfield=1;
// 获取每列数据
while (++$fieldcounter<$numfields) {
if (!$firstfield) {
$tabledump.=",";
} else {
$firstfield=0;
}

if (!isset($row[$fieldcounter])) {
$tabledump .= "NULL";
} else {
$tabledump .= "'".addslashes($row[$fieldcounter])."'";
}
}

$tabledump .= ");\n";
}
mysql_free_result($rows);

return $tabledump;
}
//函数结束

$result = mysql_list_tables ($dbname);
$i = 0;
while ($i < mysql_num_rows ($result)) {
$tb_names[$i] = mysql_tablename ($result, $i);
$dump.=sqldumptable($tb_names[$i])."\n\n\n";
$i++;
}

//可以在浏览器里输出
//echo $dump;
$filehandle=fopen($filename,"w");
fwrite($filehandle,$dump."\n\n\n");
fclose($filehandle);

echo "<script language='javascript'>alert('数据库已导出！');</script>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
</body>
</html>