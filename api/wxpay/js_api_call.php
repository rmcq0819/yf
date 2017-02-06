<?php
session_start();
if(isset($_SESSION["pay_url"])){
	$pay_url = $_SESSION["pay_url"];
}
//echo $_GET["total_fee"];exit;
//print_r($_SESSION);exit;
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	include_once("./WxPayPubHelper/WxPayPubHelper.php");
	
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	/*
	if (!isset($_GET['code']))
	{
		$redirect_url = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		//触发微信返回code码
		//$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
		$url = $jsApi->createOauthUrlForCode($redirect_url);
		//echo $url;exit;
		Header("Location: $url"); 
	}else
	{
		//获取code码，以获取openid
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
	}
	*/
	
	//sail
	$openid = $_SESSION["user_info"]["wechatid"];//微信ID
	
	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");//微信ID
	//自定义订单号，此处仅作举例
	//$timeStamp = time();
	//$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	//$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
	$out_trade_no = $_GET["out_trade_no"];
	//echo $out_trade_no."-".$openid;exit;
	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
	$unifiedOrder->setParameter("total_fee",$_GET["total_fee"]*100);//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	
	//var_dump(WxPayConf_pub::APPID);exit;
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
	$body = "";
	if(isset($_GET["body"])) $body = $_GET["body"];
	$unifiedOrder->setParameter("body",$body);//商品描述
	
	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
		
	$jsApi->setPrepayId($prepay_id);
	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>

	<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg == "get_brand_wcpay_request:ok" ) {
						alert("支付成功!");
						location.href="http://yooopay.com/index.php?m=User&a=index&status=2&orderid=<?php echo $out_trade_no ?>";
						//alert(json.stringify(res));
					} else {
						//alert(res.err_code+res.err_desc+res.err_msg);
						var str = "<?php echo $out_trade_no ?>";
						len = str.length;
						//支付不成功或者支付失败,继续调取该支付
						alert("支付不成功!"); 
						if(len == 14){
							location.href="http://yooopay.com/index.php?m=User&a=index";
						}else{
							location.href="http://yooopay.com/index.php?m=Order&a=pay&orderId=<?php echo $out_trade_no ?>";
						}
					}
					//alert(res.err_code+res.err_desc+res.err_msg);
				}
			);
		}
		
		document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
			callpay();
		});

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
</head>
<body>
</body>
</html>