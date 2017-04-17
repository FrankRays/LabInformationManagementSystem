<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("../common/session.inc");
include("../common/db_conn.inc");
$database = "lab_test"; //选择数据库

// 设置要导出的表
$tables = list_tables($database);

//--------------- 设置输出名称---------------------
$s_time=date("Y-m-d");
$newname="./data/lab_test_".$s_time.".sql";

$filename = sprintf($newname, $database);
$fp = @fopen($filename, 'w');
foreach ($tables as $table) {
    dump_table($table, $fp);
}
@fclose($fp);
mysql_close();

echo "<script language='javascript'>alert('数据已成功导出！');location.href='backup_mysql.php';</script>";
exit;


//---------读取数据库下表信息----------
function list_tables($database)
{
    $rs = mysql_list_tables($database);
    $tables = array();
    while ($row = mysql_fetch_row($rs)) {
        $tables[] = $row[0];
		echo "$row[0]\n";
    }
    mysql_free_result($rs);
    return $tables;
}

//---------------  把每个表的内容读出来 --------------------
function dump_table($table, $fp = null)
{
    $need_close = false;
    if (is_null($fp)) {
        $fp = fopen($table . '.sql', 'w');
        $need_close = true;
    }
    @fwrite($fp, "-- \n-- {$table}\n-- \n");  //可以在该行生成delete from $table 语句
    $rs = mysql_query("SELECT * FROM `{$table}`");
    while ($row = mysql_fetch_row($rs)) {
        @fwrite($fp, get_insert_sql($table, $row));
    }
    mysql_free_result($rs);
    if ($need_close) {
        fclose($fp);
    }
    @fwrite($fp, "\n\n");  //读完每个表格后换两行
}

//-------------生成 insert 语句------------------
function get_insert_sql($table, $row)
{
    $sql = "INSERT INTO `{$table}` VALUES (";
    $values = array();
    foreach ($row as $value) {
        $values[] = "'" . mysql_real_escape_string($value) . "'";
    }
    $sql .= implode(', ', $values) . ");\n";
    return $sql;
}


?>