<?php
class ApiConfig
{
	//图片路径
	const ITEM_IMG_PATH = "http://yooopay.com/data/upload/item/";
	const FLAG_PATH = "http://yooopay.com/data/upload/flag/";
	const ITEM_SIZE_PATH = "http://yooopay.com/data/upload/item_size/";
	const ADVERT_PATH = "http://yooopay.com/data/upload/advert/";
	const SERVER_PATH = "http://yooopay.com";
	const BG_IMG_PATH = "http://yooopay.com/data/upload/bg_img/";
	const ITEM_CATE_PATH = "http://yooopay.com/data/upload/item_cate/";
	const MORE_FLAG_PATH = "http://yooopay.com/data/upload/bg_img/more.jpg";
	
	
	
	const MCHID = "1392525002";												
	const APPID = "wx8c3ea1363cd06011";												
	//商户支付密钥Key
	const KEY = "GSONGAO459BIW294MGJK0goangnga66b";						
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = "http://api.yooopay.com/yf/api/wxpay/notice.php";
	const TRADE_TYPE = "APP";
}	
?>