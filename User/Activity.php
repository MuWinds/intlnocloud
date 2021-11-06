<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]**[我知道发出来有人会盗用,但请您留版权]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度            盗用不留版权,你就不配拿去!
// +----------------------------------------------------------------------
// | Date: 2019年08月20日
// +----------------------------------------------------------------------

/**
 * 邀请有礼
**/
$title='邀请有礼';
include './Head.php';
$Activity_Url = short_url('http://'.$_SERVER['HTTP_HOST'].'/User/Login.php?id='.$pid);
?>
<!-- APP MAIN ==========-->
<div class="row">
    <div class="col-sm-12">
        <!-- Profile -->
        <div class="card bg-primary">
            <div class="card-body profile-user-box">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="media">
                            <span class="float-left m-2 mr-4"><img src="<?php echo ($userrow['qq'])?'//q3.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$userrow['qq'].'&src_uin='.$userrow['qq'].'&fid='.$userrow['qq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC':'/assets/images/team-1.jpg'?>"
                                                                   style="height: 100px;" alt=""
                                                                   class="rounded-circle img-thumbnail"></span>
                            <div class="media-body">
                                <h4 class="mt-1 mb-1"><?php echo getQQNick($userrow['key']);?></h4>
                                <p class="font-13"> <?=$conf['sitename']?> <span
                                            class="badge badge-success-lighten ml-2">已认证</span></p>
                                <ul class="mb-0 list-inline">
                                    <li class="list-inline-item mr-3">
                                        <h5 class="mb-1">0人</h5>
                                        <p class="mb-0 font-13">累计邀请人数</p>
                                        <h5 class="mb-1">0次</h5>
                                        <p class="mb-0 font-13">累计领取奖励</p>
                                    </li>
                                </ul>
                            </div> <!-- end media-body-->
                        </div>
                    </div> <!-- end col-->

                    <div class="col-sm-4">
                        <div class="text-center mt-sm-0 mt-3 text-sm-right">
                            <button type="button" class="btn btn-danger btn-rounded btn-code"
                                    data-clipboard-target="#btn_code">
                                <i class="mdi mdi-account-edit mr-1"></i> 复制邀请链接
                            </button>
                            <p class="mt-3 font-16 text-success">
                                每邀请1个真实用户奖励1000积分</p>
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row -->
            </div> <!-- end card-body/ profile-user-box-->
        </div><!--end profile/ card -->
    </div> <!-- end col-->
    <div class="col-xl-6">
        <div class="card text-white bg-danger overflow-hidden">
            <div class="card-body">
                <div class="toll-free-box text-center font-18 ">
                    <i class="layui-icon layui-icon-gift"></i> <span class="mr-1">专属推广链接</span>
					<span id="btn_code"><?=$Activity_Url?></span>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
        <!-- Personal-Information -->
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">专属邀请福利图片
                    <span class="badge badge-primary p-1" onclick="location.reload()"
                          style="cursor: pointer">换种样式 [图片<?php $rand = rand(1, 13);
                        echo $rand; ?>]</span>
                    <a href="Image/api.php?url=<?= base64_encode($Activity_Url) ?>&ids=<?= $rand ?>&images=id<?= $USERDATA['id'] ?>_<?= $rand ?>.jpg"
                       target="_blank"
                       class="badge badge-success p-1">保存图片</a>
                </h4>
                <img class="card-img-top"
                     src="Image/api.php?url=<?= base64_encode($Activity_Url) ?>&ids=<?= $rand ?>&images=id<?= $USERDATA['id'] ?>_<?= $rand ?>.jpg"
                     alt="推广图片">
            </div>
        </div>
        <!-- Personal-Information -->
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">我的邀请</h4>
                <div class="slimscroll" style="min-height: 435px;">
                    <?php foreach ($data_arr as $v) { ?>
                        <div class="media p-2 shadow-sm">
                            <img class="mr-3 rounded-circle" src="<?= $v['image'] ?>" width="40"
                                 alt="Generic placeholder image">
                            <div class="media-body">
                                <h5 class="mt-0 mb-1 text-justify"><?= $v['name'] ?></h5>
                                <span class="font-13"><?= $v['creation_time'] ?>
                            </span>
                                <?php
                                if ($v['award'] == 0) {
                                    echo '<a href="javascript:layer.msg(\'您已经在<font color=#03de01>' . $v['draw_time'] . '</font>领取了奖励点!\')" class="badge badge-primary-lighten">您已经领取奖励</a>';
                                } else {
                                    echo '<a href="?act=get&id=' . $v['id'] . '" class="badge badge-success-lighten" >点我领取邀请奖励</a>';
                                }
                                ?>
                            </div>
                            <a class="badge badge-warning ml-1 text-white mt-3">待领取：<?= $v['award'] ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card-->
</div>
<script src="https://cdn.bootcss.com/clipboard.js/2.0.4/clipboard.js"></script>
<script>
    var clipboard = new ClipboardJS('.btn-code');
    clipboard.on('success', function (e) {
        layer.msg("复制成功<br>快去分享给朋友一起来领免费名片赞吧！", {icon: 1});
        e.clearSelection();
    });

    clipboard.on('error', function (e) {
        layer.msg('专属链接复制失败,请手动复制链接~', {icon: 3});
    });
</script>

<?php
	include './Foot.php';
?>