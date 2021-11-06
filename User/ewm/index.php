<?php
/*
 * @Descripttion: 
 * @version: 1
 * @Author: wlkjyy
 * @Date: 2021-05-13 08:05:04
 */
/**
 * 生成二维码
 * author:wlkjyy
 * qq:3139505131
 */

 //载入二维码类库
 include './qrcode.php';
 $text = isset($_GET['text']) ? trim(urldecode($_GET['text'])) : exit('{"code"-1,:"message":"Need Text Message"}');
 $value = $url;                  //二维码内容  
 $errorCorrectionLevel = 'L';    //容错级别   
 $matrixPointSize = 10;           //生成图片大小    
 //生成二维码图片  

//  if(base64_decode($text)) $text = base64_decode($text);
 $QR = QRcode::png($text,false,$errorCorrectionLevel, $matrixPointSize, 2);  
?>