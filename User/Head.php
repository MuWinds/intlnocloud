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
include("../Core/Common.php");

//php防注入和XSS攻击通用过滤. 
/*
$_GET = SafeFilter($_GET);
$_POST = SafeFilter($_POST);
function SafeFilter ($arr){
    $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');
    if (is_array($arr)){
        foreach ($arr as $key => $value){
            if(!is_array($value)){
                if (!get_magic_quotes_gpc()){             //不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
                    $value=addslashes($value);           //给单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）加上反斜线转义
                }
                $value=preg_replace($ra,'',$value);     //删除非打印字符，粗暴式过滤xss可疑字符串
                $arr[$key]     = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为 HTML 实体
            }else{
                SafeFilter($arr[$key]);
            }
        }
    }
}
*/
if($islogin_user==1 and $userrow['pid']){}else exit("<script language='javascript'>window.location.href='/User/Login.php';</script>");
if($conf['webwh']=='开启维护')sysmsg('站点维护中!');
?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?php echo $title?> | <?=$conf['sitename']?>-<?=$conf['title']?></title>
		<meta name="keywords" content="<?php echo $conf['keywords']?>">
		<meta name="description" content="<?php echo $conf['description']?>">
		<link rel="shortcut icon" href="/Core/Assets/Icon/favicon.ico" type="image/x-icon" />
        <link href="/Core/Assets/Assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/Core/Assets/Assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="/Core/Assets/Assets/css/style.css" rel="stylesheet" type="text/css">
       <!-- DataTables -->
        <link href="/Core/Assets/Assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="/Core/Assets/Assets/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//at.alicdn.com/t/font_1139659_6z0njfyxgti.css">
        <body class="fixed-left">
        <div id="wrapper">
            <div class="left side-menu">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="ion-close"></i>
                </button>
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="index.php" class="logo"><i class="mdi mdi-assistant"></i>码支付</a>
                    </div>
                </div>
                <div class="sidebar-inner slimscrollleft">
                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">菜单</li>
                            <li>
                                <a href="index.php" class="waves-effect">
                                    <i class="mdi mdi-airplay"></i>
                                    <span> 用户中心 </span>
                                </a>
                            </li>
                               <li>
                                <a href="Order.php" class="waves-effect">
                                    <i class="mdi mdi-cash-usd"></i>
                                    <span> 订单列表 </span>
                                </a>
                            </li> 
                               <li>
                                <a href="Qrlist.php" class="waves-effect">
                                    <i class="mdi mdi-qrcode"></i>
                                    <span> 三网免挂 </span>
                                </a>
                            </li> 
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account-multiple"></i> <span> 商户管理 </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="Api_Set.php">修改密码/查看对接密钥</a></li>
                                    <li><a href="Pay_Vip.php">充值额度/开通免挂会员</a></li>
                                </ul>
                            </li>  
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account-settings-variant"></i> <span> 其他设置 </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="Pay_Set.php">支付设置</a></li>
                                </ul>
                            <!--l/li> 
                               <li>
                                <a href="Activity.php" class="waves-effect">
                                    <i class="mdi mdi-apple-keyboard-command"></i>
                                    <span> 邀请有礼 </span>
                                </a>
                            </li>  
                            <i class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-shield"></i><span> 安全维护 </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="Api_Uphold.php">接口状态[实时更新]</a></li>
                                </ul>
                            </li-->  
                               <li>
                                <a href="Work.php" class="waves-effect">
                                    <i class="mdi mdi-shape-square-plus"></i>
                                    <span> 工单系统 </span>
                                </a>
                            </li> 
                               <li>
                                <a href="./Login.php?logout" class="waves-effect">
                                    <i class="mdi mdi-logout m-r-5 text-muted"></i>
                                    <span> 安全退出 </span>
                                </a>
                            </li> 
							 <!--li>
                                <a href="oreo_notice.php" class="waves-effect">
                                    <i class="mdi mdi-access-point-network"></i>
                                    <span> 平台公告 </span>
                                </a>
                            </li> 
                            <li class="menu-title">核心</li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-xml"></i><span> 系统参数 </span><span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                <li class="has_sub">
                                    <li><a href="oreo_webset.php">站点信息配置</a></li>
                                    <li><a href="oreo_admin.php">管理员信息配置</a></li>
                                    <li><a href="oreo_applet.php">小程序配置</a></li>
                                    <li><a href="oreo_dispatch.php">短信邮箱配置</a></li>
                                    <li><a href="oreo_shlogin.php">商户登录配置</a></li>
                                    <li><a href="oreo_fwtk.php">服务条款配置</a></li>
                                    <li><a href="oreo_shreg.php">申请商户配置</a></li>
                                    <li><a href="oreo_moneyrate.php">盈利费率配置</a></li>
                                    <li><a href="oreo_fukuan.php">结算转账设置</a></li>
                                    <li><a href="oreo_ddcs.php">订单测试设置</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-shield"></i><span> 安全配置 </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="oreo_safe.php?oreo=intercept">商品拦截配置</a></li>
                                    <li><a href="oreo_safe.php?oreo=log">登录记录</a></li>  
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-key"></i><span> 接口管理 </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
								     <li><a href="oreo_orgin.php">参数与回调</a></li>
                                     <li><a href="oreo_payset.php">支付接口通道配置</a></li>
                                     <li><a href="oreo_lxjk.php">轮询接口设置</a></li>
                                </ul>
                            </li>
                               <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cash-usd"></i><span> 会员功能设置</span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="oreo_vip.php">开通接口设置</a></li>
                                    <li><a href="oreo_vipcx.php">购买列表</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-shape-square-plus"></i> <span> 外观设置 </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="oreo_template.php">首页模板</a></li>
                                    <li><a href="oreo_logo.php">更改logo</a></li>                
                                </ul>
                            </li>
                            <li>
                                <a href="oreo_jiankong.php" class="waves-effect">
                                    <i class="mdi mdi-airplay"></i>
                                    <span> 监控配置 </span>
                                </a>
                            </li>
                        </ul-->
                    </div>
                    <div class="clearfix"></div>
                </div> 
                </div> 
				 <!-- Start right Content here -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <!-- Top Bar Start -->
                    <div class="topbar">
                        <nav class="navbar-custom">
                            <ul class="list-inline float-right mb-0"> 
							 <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                       aria-haspopup="false" aria-expanded="false">
                                        <i class="iconfont icon-tixing" style="font-size: 20px;color: #f7bd01;"></i>
                                        <span class="badge badge-danger noti-icon-badge"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                                        <!-- item-->
                                        <div class="dropdown-item noti-title">
                                            <h5><span class="badge badge-danger float-right"></span>新消息</h5>
                                        </div>
	<!--a href="./oreo_sdjs.php" class="dropdown-item notify-item">
    <div class="notify-icon"><img src="//q3.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$usernums['qq'].'&src_uin='.$usernums['qq'].'&fid='.$usernums['qq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC " alt="user-img" class="img-fluid rounded-circle" /> </div>
    <p class="notify-details"><b>0</b><small class="text-muted">1.</small></p>
    </a-->
                                        <!-- item-->
                                        

                                    </div>
                                </li>
                                <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                       aria-haspopup="false" aria-expanded="false">
                                        <img src="http://q1.qlogo.cn/g?b=qq&nk=<?=$userrow['qq']?>&s=100&t=<?=time()?>" alt="user" class="rounded-circle">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                        <!-- item-->
                                        <div class="dropdown-item noti-title">
                                            <h5><?=getQQNick($userrow['qq'])?></h5>
                                        </div>
                                        <a class="dropdown-item" href="Api_Set.php"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> 修改信息</a>
                                        <a class="dropdown-item" href="Api_Set.php"><i class="mdi mdi-wallet m-r-5 text-muted"></i> 充值额度</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="./Login.php?logout"><i class="mdi mdi-logout m-r-5 text-muted"></i> 安全退出</a>
                                    </div>
                                </li>
                            </ul>
                            <ul class="list-inline menu-left mb-0">
                                <li class="float-left">
                                    <button class="button-menu-mobile open-left waves-light waves-effect">
                                        <i class="mdi mdi-menu"></i>
                                    </button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </nav>
                    </div>
                    <!-- Top Bar End -->  
                    <div class="page-content-wrapper ">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="page-title-box">
                                        <div class="btn-group float-right">
                                            <ol class="breadcrumb hide-phone p-0 m-0">
                                                <li class="breadcrumb-item"><a href="#"><?=$title?></a></li>
                                                <li class="breadcrumb-item active"><?php echo $title?> </li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title"><?php echo $title?> </h4>
                                    </div>
                                </div>
                            </div>