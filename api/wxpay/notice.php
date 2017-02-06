<?php
    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
 
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//以log文件形式记录回调信息
	$log_ = new Log_();
	$log_name="./notice.log";//log文件路径
	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
			$xmlObj = simplexml_load_string($xml);
			$out_trade_no = (string)$xmlObj->out_trade_no;
			if(!empty($out_trade_no)){
				$conn = mysql_connect("localhost","root","gqjsj123654");
				$time = time();
		    	mysql_select_db("tyf",$conn);
				
		    	$sql_status = mysql_query("select * from weixin_item_order where orderId LIKE '".$out_trade_no."%'",$conn);
				while($r=mysql_fetch_array($sql_status)){
						$status = $r['status'];
				}
				if($status == 1){
					mysql_query("update weixin_item_order set status=2,support_time=".$time." where status=1 and orderId LIKE '".$out_trade_no."%'",$conn);
					//更新购买数量
					$sql = mysql_query("select * from weixin_order_detail where orderId LIKE '%".$out_trade_no."%'",$conn);
					while($row=mysql_fetch_array($sql)){
						
						//获取商品的ID
						$itemId = $row['itemId'];
						$sql2 =  mysql_query("select * from weixin_item where id=".$itemId,$conn);
						while($row2=mysql_fetch_array($sql2)){
							$buy_num = $row2['buy_num'];
						}
						//获取改商品的购买数量
						$quantity = $row['quantity']+$buy_num;
						//更新下单的库存
						mysql_query("update weixin_item set buy_num=".$quantity." where id=".$itemId,$conn);
						
					}
				
					$url1 = 'http://api.yooopay.com/yf/index.php?m=payend&a=pay_end&orderId='.$out_trade_no;
					$ret = $notify->get_http($url1);
				}
			} 
		}
	}

	/**
	 * 所有接口的基类
	 */
	class Common_util_pub
	{
		function __construct() {
		}

		function trimString($value)
		{
			$ret = null;
			if (null != $value) 
			{
				$ret = $value;
				if (strlen($ret) == 0) 
				{
					$ret = null;
				}
			}
			return $ret;
		}
		
		/**
		 * 	作用：产生随机字符串，不长于32位
		 */
		public function createNoncestr( $length = 32 ) 
		{
			$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
			$str ="";
			for ( $i = 0; $i < $length; $i++ )  {  
				$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
			}  
			return $str;
		}
		
		/**
		 * 	作用：格式化参数，签名过程需要使用
		 */
		function formatBizQueryParaMap($paraMap, $urlencode)
		{
			$buff = "";
			ksort($paraMap);
			foreach ($paraMap as $k => $v)
			{
				if($urlencode)
				{
				   $v = urlencode($v);
				}
				//$buff .= strtolower($k) . "=" . $v . "&";
				$buff .= $k . "=" . $v . "&";
			}
			$reqPar = "";
			if (strlen($buff) > 0) 
			{
				$reqPar = substr($buff, 0, strlen($buff)-1);
			}
			return $reqPar;
		}
		
		/**
		 * 	作用：生成签名
		 */
		public function getSign($Obj)
		{
			foreach ($Obj as $k => $v)
			{
				$Parameters[$k] = $v;
			}
			//签名步骤一：按字典序排序参数
			ksort($Parameters);
			$String = $this->formatBizQueryParaMap($Parameters, false);
			//echo '【string1】'.$String.'</br>';
			//签名步骤二：在string后加入KEY
			$String = $String."&key=".'GSONGAO459BIW294MGJK0goangnga66b';
			//echo "【string2】".$String."</br>";
			//签名步骤三：MD5加密
			$String = md5($String);
			//echo "【string3】 ".$String."</br>";
			//签名步骤四：所有字符转为大写
			$result_ = strtoupper($String);
			//echo "【result】 ".$result_."</br>";
			return $result_;
		}
		
		/**
		 * 	作用：array转xml
		 */
		function arrayToXml($arr)
		{
			$xml = "<xml>";
			foreach ($arr as $key=>$val)
			{
				 if (is_numeric($val))
				 {
					$xml.="<".$key.">".$val."</".$key.">"; 

				 }
				 else
					$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
			}
			$xml.="</xml>";
			return $xml; 
		}
		
		/**
		 * 	作用：将xml转为array
		 */
		public function xmlToArray($xml)
		{		
			//将XML转为array        
			$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
			return $array_data;
		}

		/**
		 * 	作用：以post方式提交xml到对应的接口url
		 */
		public function postXmlCurl($xml,$url,$second=30)
		{	
			//初始化curl        
			$ch = curl_init();
			//设置超时
			curl_setopt($ch, CURLOP_TIMEOUT, $second);
			//这里设置代理，如果有的话
			//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
			//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
			file_put_contents(dirname(__FILE__)."/test.txt",$url);
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
			//设置header
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			//要求结果为字符串且输出到屏幕上
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//post提交方式
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			//运行curl
			$data = curl_exec($ch);
			//curl_close($ch);
			//返回结果
			if($data)
			{	
				
				curl_close($ch);
				return $data;
			}
			else 
			{ 
				$error = curl_errno($ch);
				echo "curl出错，错误码:$error"."<br>"; 
				echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
				curl_close($ch);
				return false;
			}
		}
		
		/**
		 * 	作用：使用证书，以post方式提交xml到对应的接口url
		 */
		function postXmlSSLCurl($xml,$url,$second=30)
		{
			$ch = curl_init();
			//超时时间
			curl_setopt($ch,CURLOPT_TIMEOUT,$second);
			//这里设置代理，如果有的话
			//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
			//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
			//设置header
			curl_setopt($ch,CURLOPT_HEADER,FALSE);
			//要求结果为字符串且输出到屏幕上
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			//默认格式为PEM，可以注释
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			//证书路径,注意应该填写绝对路径
			curl_setopt($ch,CURLOPT_SSLCERT, '');
			//默认格式为PEM，可以注释
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			//证书路径,注意应该填写绝对路径
			curl_setopt($ch,CURLOPT_SSLKEY, '');
			//post提交方式
			curl_setopt($ch,CURLOPT_POST, true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
			$data = curl_exec($ch);
			//返回结果
			if($data){
				curl_close($ch);
				return $data;
			}
			else { 
				$error = curl_errno($ch);
				echo "curl出错，错误码:$error"."<br>"; 
				echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
				curl_close($ch);
				return false;
			}
		}
		
		/**
		 * 	作用：打印数组
		 */
		function printErr($wording='',$err='')
		{
			print_r('<pre>');
			echo $wording."</br>";
			var_dump($err);
			print_r('</pre>');
		}
	}



	/**
	 * 响应型接口基类
	 */
	class Wxpay_server_pub extends Common_util_pub 
	{
		public $data;//接收到的数据，类型为关联数组
		var $returnParameters;//返回参数，类型为关联数组
		
		/**
		 * 将微信的请求xml转换成关联数组，以方便数据处理
		 */
		function saveData($xml)
		{
			$this->data = $this->xmlToArray($xml);
		}
		
		function checkSign()
		{
			$tmpData = $this->data;
			unset($tmpData['sign']);
			$sign = $this->getSign($tmpData);//本地签名
			if ($this->data['sign'] == $sign) {
				return TRUE;
			}
			return FALSE;
		}
		
		public function get_http($url){
			$ch1 = curl_init();
			curl_setopt($ch1, CURLOPT_URL, $url);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch1, CURLOPT_HEADER, 0);
			$result = curl_exec($ch1);
			curl_close($ch1);
			return $result;
		}
	
		/**
		 * 获取微信的请求数据
		 */
		function getData()
		{		
			return $this->data;
		}
		
		/**
		 * 设置返回微信的xml数据
		 */
		function setReturnParameter($parameter, $parameterValue)
		{
			$this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
		}
		
		/**
		 * 生成接口参数xml
		 */
		function createXml()
		{
			return $this->arrayToXml($this->returnParameters);
		}
		
		/**
		 * 将xml数据返回微信
		 */
		function returnXml()
		{
			$returnXml = $this->createXml();
			return $returnXml;
		}
	}


	/**
	 * 通用通知接口
	 */
	class Notify_pub extends Wxpay_server_pub 
	{
	}


	/**
	 * 打印log接口
	 */
	class Log_
	{
		function  log_result($file,$word) 
		{
			$fp = fopen($file,"a");
			flock($fp, LOCK_EX) ;
			fwrite($fp,"执行日期：".strftime("%Y-%m-%d %H: %M: %S",time())."\n".$word."\n\n");
			flock($fp, LOCK_UN);
			fclose($fp);
		}
	}
?>