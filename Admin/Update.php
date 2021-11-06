<?php
$title='更新检测';
include './Head.php';
// $checkurl=$conf['Instant_url'].'Api_Check.php?url='.$_SERVER['HTTP_HOST'].'&authcode='.trim($Authcode).'&ver='.$Version;
// //函数
// function zipExtract($src, $dest)
// {
// $zip = new ZipArchive();
// if ($zip->open($src)===true)
// {
// $zip->extractTo($dest);
// $zip->close();
// return true;
// }
// return false;
// }
?>
			<div class="col-sm-12 col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-globe"></span> 在线更新</h4>
					</div>
					<div class="panel-body">
							<a class="list-group-item">写入文件①（推荐）<?php if (is_writable('./')) {
								echo '<font color="green">可用√</font>';
							} else {
								echo '<font color="black">不支持</font>';
							} ?></a><div class="well">都破解了，还想更新呢？</div>

		<p><iframe src="../readme.txt" style="width:100%;height:465px;"></iframe></p>
</blockquote>
    </div>
  </div>
<?php include'foot.php';?>