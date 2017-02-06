<?php
/**
* 
*/

include_once('DB.php');
$sql="select * from ".$arr['DB_PREFIX']."pay WHERE ( `pay_type` = 'wxpay' )";
$result=mysql_query($sql,$conn);
$row2=mysql_fetch_array($result);
$row=unserialize($row2['config']);
$appid=strval($row['appid']);
$secret=strval($row['appsecret']);
$partnerkey=strval($row['partnerkey']);
$signkey=strval($row['signkey']);
$partner=strval($row['partnerid']);
define("APPID" , $appid);  //appid
define("APPKEY" ,$signkey); //paysign key
define("SIGNTYPE", "sha1"); //method
define("PARTNERKEY",$partnerkey);//通加密串
define("APPSERCERT", $secret);
define("PARTNERID", $partner);
define("MCHID", $partner);


/*define(APPID , "wxf8b4f85f3a794e77");  //appid
define(APPKEY ,"2Wozy2aksie1puXUBpWD8oZxiD1DfQuEaiC7KcRATv1Ino3mdopKaPGQQ7TtkNySuAmCaDCrw4xhPY5qKTBl7Fzm0RgR3c0WaVYIXZARsxzHV2x7iwPPzOz94dnwPWSn"); //paysign key
define(SIGNTYPE, "sha1"); //method
define(PARTNERKEY,"8934e7d15453e97507ef794cf7b0519d");//通加密串
define(APPSERCERT, "09cb46090e586c724d52f7ec9e60c9f8");*/

?>