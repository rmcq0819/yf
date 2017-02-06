<?php 
/**
 * kuaidi.class.php 快递查询类 v1.0
 *
 * @copyright        曙光
 * @license          
 * @lastmodify       2016-03-31
 */
//header("Content-Type:text/html;charset=utf-8");
class Kuaidi{
	/*
     * 网页内容获取方法
    */
    private function getcontent($url)
    {
        if (function_exists("file_get_contents")) {
            $file_contents = file_get_contents($url);
        } else {
            $ch      = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }
	/*
     * 获取对应名称和对应传值的方法
    */
    public function expressname($order)
    {
        $name   = json_decode($this->getcontent("http://www.kuaidi100.com/autonumber/auto?num={$order}"), true);
        $result = $name[0]['comCode'];
		//return $result;
		
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
		
    }
	
	public function curl_get($url){
		$binfo =array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)','Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0','Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; Alexa Toolbar)','Mozilla/4.0(compatible; MSIE 6.0; Windows NT 5.1; SV1)',$_SERVER['HTTP_USER_AGENT']);
		$cip = '218.242.124.'.mt_rand(0,254);
		$xip = '218.242.124.'.mt_rand(0,254);
		$header = array('CLIENT-IP:'.$cip, 'X-FORWARDED-FOR:'.$xip);
		
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt ($ch, CURLOPT_USERAGENT, $binfo[mt_rand(0,3)]);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$content=curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	
	//获取页面指定的一段值  $str获取值的字符串 $start 开始字符串 $end 结束字符串
	public function get_sub_content($str, $start, $end,$a=1,$b=0){
		if ( $start == '' || $end == '' ){
			return;
		}
		$str = explode($start, $str);
		@$str = explode($end, $str[$a]);
		return $str[$b];
	}
	//物流公司代号对照 
	public function show_name($express_name){
		switch ($express_name) {
			case 'zhongtong':
				$name = '中通快递 ';
				break;
			case 'shentong':
				$name = '申通快递 ';
				break;
			case 'shunfeng':
				$name = '顺丰速运 ';
				break;
			case 'yunda':
				$name = '韵达快运 ';
				break;
			case 'yuantong':
				$name = '圆通速递 ';
				break;
			case 'huitongkuaidi':
				$name = '汇通快运 ';
				break;
			case 'debangwuliu':
				$name = '德邦物流 ';
				break;
			case 'youshuwuliu':
				$name = '优速快递 ';
				break;
			case 'suer':
				$name = '速尔快递 ';
				break;
			case 'tiantian':
				$name = '天天快递 ';
				break;
			case 'lianhaowuliu':
				$name = '联昊通速递 ';
				break;
			default:
				$name = '系统繁忙，正在查询...';
				break;
		}
		return $name;
	}
	//快递电话对照 
	public function show_phone($express_name){
		switch ($express_name) {
			case 'zhongtong':
				$name = '95311 ';
				break;
			case 'shentong':
				$name = '95543 ';
				break;
			case 'shunfeng':
				$name = '95338 ';
				break;
			case 'yunda':
				$name = '95546 ';
				break;
			case 'yuantong':
				$name = '95554 ';
				break;
			case 'huitongkuaidi':
				$name = '400-956-5656 ';
				break;
			case 'debangwuliu':
				$name = '95353 ';
				break;
			case 'youshuwuliu':
				$name = '400-1111-119 ';
				break;
			case 'suer':
				$name = '400-882-2168 ';
				break;
			case 'tiantian':
				$name = '4001-888-888 ';
				break;
			case 'lianhaowuliu':
				$name = '0769-88620000 ';
				break;
			default:
				$name = '未知电话';
				break;
		}
		return $name;
	}
	
	//获取物流信息 
	public function getorder($trace_no){
		$postcom = $this->expressname($trace_no);//快递公司参数 
		$url = "http://wap.kuaidi100.com/wap_result.jsp?rand=".date("Ymd")."&id=".$postcom."&fromWeb=null&postid=".$trace_no;
		$post = $this->curl_get($url);
		$post = $this->get_sub_content($post,'<form action="wap_result.jsp" method="get" >','</form>');
		$post = explode('<div class="clear"></div>',$post);
		$str = $post[2];
		$pattern = "/<p>.*?<\/p>/m";
		preg_match_all($pattern,$str,$data);
		$data = $data[0];
		unset($data[0]);
		unset($data[1]);
		$num = count($data);
		$kuaidi = array();
		$ass = array();

		foreach($data as $k=>$v){
			$vv = explode("<br />",$v);
			$kuaidi['time'] = substr($vv[0],3);
			$kuaidi['time'] = substr($kuaidi['time'],8);
			$kuaidi['content'] = substr($vv[1],0,-4);
			$ass[] = $kuaidi; 
		}
		rsort($ass);
		$result = array(
			'tel' => $this->show_phone($postcom),//物流公司电话
			'express_name' => $this->show_name($postcom),//物流公司代号 
			'trace' => $ass//物流信息 
		);
		return $result;
	
	}
	
 
}