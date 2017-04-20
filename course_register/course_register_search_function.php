<?php
function showHeader($title, $col_num) {
	echo "<table id='content' align=\"center\"  border=\"2\" width=\"90%\" bordercolor=\"#000000;\">\n";
		
		echo "<tr>\n";
		
		//利用上面获取的列数结合mysql_fetch_field()函数取出并显示字段名(列名)
		for ( $i = 0; $i < 7; $i++ ) //登记表前面字段
			{   
				
				echo "<th>{$title[$i]->name}</th>\n";
			}
		echo "<th>周次</th>\n";
		echo "<th>星期</th>\n";
		echo "<th>节次</th>\n";
		for($i=7;$i<$col_num;$i++)//登记表后面字段
		{
			//2017-04-20如果不是a_id列则输出
			if ($title[$i]->name != '')
				echo "<th>{$title[$i]->name}</th>\n";
		}
		echo "</tr>\n";
		
		
		
}