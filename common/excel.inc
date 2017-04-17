<script language=javascript>
function method1(tableid) {//整个表格拷贝到EXCEL中 

var curTbl = document.getElementById(tableid); 

var oXL = new ActiveXObject("Excel.Application"); //创建AX对象excel 

var oWB = oXL.Workbooks.Add(); //获取workbook对象 

var oSheet = oWB.ActiveSheet; //激活当前sheet 

var sel = document.body.createTextRange(); 

sel.moveToElementText(curTbl); //把表格中的内容移到TextRange中 

sel.select(); //全选TextRange中内容 

sel.execCommand("Copy"); //复制TextRange中内容 

oSheet.Paste(); //粘贴到活动的EXCEL中 

oXL.Visible = true; //设置excel可见属性 
} 
</script>
