<?php
class ErpsignAction extends Action {
	
	private $secret = "AJSDS123IOJASDNB1233";
    private $app_key = "1982321";
	private $format = "JSON";
	private $sign_method = "MD5";
	/**
	 * 生成签名
	 *  @return 签名
	 */
	public function MakeSign($params){
		//签名步骤一：按字典序排序数组参数
		ksort($params);
		$string = $this->ToUrlParams($params);
		//签名步骤二：在string后加入KEY
		$string = $this->secret.$string.$this->secret;
		print_r($string);
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
	/**
	 * 将参数拼接为url: keyvaluekeyvalue
	 * @param   $params
	 * @return  string
	 */
	public function ToUrlParams($params){
		$string = '';
		if( !empty($params) ){
			$array = array();
			foreach( $params as $key => $value ){
				$string .= $key.$value;
			}
		}
		return $string;
	}
	
	
	public function test(){
		//系统参数
		$sysParams['app_key'] = $this->app_key;
		$sysParams['format'] = $this->format;
		$sysParams['timestamp'] = 199182318773;
		$sysParams['sign_method'] = $this->sign_method;
	
		//业务参数
		$bizParams['goods_code'] = "123434";
		$bizParams['size'] = 300;
		$bizParams['method'] = "hupun.open.inventory.retInv";
		
		$params = array_merge($sysParams,$bizParams);
		$string = $this->MakeSign($params);
		print_r($string);
		echo json_encode(array('status'=>1));
	}
	
}