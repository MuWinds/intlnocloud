<?php
/**
 * 登录日志
**/
$title='操作日志';
include './Head.php';
?>
<form onsubmit="return searchRecord()" method="GET" class="form-inline">
  <div class="form-group">
    <label>搜索</label>
	<select name="column" class="form-control"><option value="uid">商户号</option></select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容（0为管理员）">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button>
  <a href="javascript:listTable('start')" class="btn btn-default" title="刷新登录日志"><i class="fa fa-refresh"></i></a>
</form>

<div id="listTable"></div>
    </div>
  </div>
<script>
function listTable(query){
	var url = window.document.location.href.toString();
	var queryString = url.split("?")[1];
	query = query || queryString;
	if(query == 'start' || query == undefined){
		query = '';
		history.replaceState({}, null, './Log.php');
	}else if(query != undefined){
		history.replaceState({}, null, './Log.php?'+query);
	}
	layer.closeAll();
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'log-table.php?'+query,
		dataType : 'html',
		cache : false,
		success : function(data) {
			layer.close(ii);
			$("#listTable").html(data)
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function searchRecord(){
	var column=$("select[name='column']").val();
	var value=$("input[name='value']").val();
	if(value==''){
		listTable();
	}else{
		listTable('column='+column+'&value='+value);
	}
	return false;
}
$(document).ready(function(){
	listTable();
})
</script>