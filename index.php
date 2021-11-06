<?php
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    die('require PHP > 5.6 !');
}
include("./Core/Common.php");

$mod = isset($_GET['mod'])?$_GET['mod']:'index';
$loadfile = \lib\Template::load($mod);
include $loadfile;