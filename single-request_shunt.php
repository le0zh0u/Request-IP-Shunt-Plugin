<?php
/*
Template Name: Request Shunt Template
 */
/**
 * 获取本地ip地址的国家
 * @return array 结果数组，包含了country和city
 *PS:本地测试对数据做了处理，真实的服务器下需要再尝试下
 */
 function getLocationInfoByIp(){
  $client = @$_SERVER['HTTP_CLIENT_IP'];
  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $remote = @$_SERVER['REMOTE_ADDR'];
  $result = array('country'=>'', 'city'=>'');
  if(filter_var($client, FILTER_VALIDATE_IP)){
  $ip = $client;
  }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
  $ip = $forward;
  }else{
  $ip = $remote;
  }
  if ($ip!='::1') {
    $ip_data = @json_decode
   (file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
    $result['country'] = $ip_data->geoplugin_countryCode;
    $result['city'] = $ip_data->geoplugin_city;
    }else {
      $result['country'] = 'unknow';
    }
  }else{
    $result['country'] = 'unknow';
  }
  return $result;
 }

 /**
  * 判断是否要使用国内的url
  * @return boolean true: 使用domestic_url, false: 使用abroad_url
  */
 function isUsingChineseUrl(){
   $result = getLocationInfoByIp();
   if ($result['country'] == 'CN' || $result['country'] == 'unknow') {
     return true;
   }else{
     return false;
   }
 }

/**
 * 设置页面跳转的路径
 */
 header("HTTP/1.1 301 Moved Permanently");
 if (isUsingChineseUrl()) {
   header("Location: ".esc_html( get_post_meta( get_the_ID(), 'domestic_url', true ) ));
 }else {
   header("Location: ".esc_html( get_post_meta( get_the_ID(), 'abroad_url', true ) ));
 }

 exit();
 ?>
