<?php 
/** 
 *   发送微信模版信息
 *   @author  vany
 *   date    2015-12-2 
 *       
 */
class Wxsend{
	 //升级通知模版
	public function SJ($openid,$username,$phone_mob,$hq_status,$do_time){
    $access_token = $this->get_token();
    $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		
	 $data = '{
   "touser":"'.$openid.'",
   "template_id":"REdWzEJY41vik98I8K_7UXVqz0Gs3e4lcxJCF_gN4vs",
   "url":"http://yooopay.com/index.php?m=User&a=index&isshop=1",';
    if ($hq_status == 1) {
      	$data.='"data":{
               "first": {
                   "value":"恭喜'.$username.',您的升级申请已经审核通过！",
                   "color":"#000"
               },
			    "keyword1":{
                   "value":"'.$username.'",
                   "color":"#000"
               },
               "keyword2": {
                   "value":"'.$phone_mob.'",
                   "color":"#000"
               },
               "keyword3": {
                   "value":"'.$do_time.'",
                   "color":"#000"
               }
            } 
       	}';          
    }else{
    	$data.='"data":{
              "first": {
                 "value":"很遗憾'.$username.',您的升级申请未审核通过！",
                 "color":"#000"
              },
			   "keyword1":{
                   "value":"'.$username.'",
                   "color":"#000"
               },
               "keyword2": {
                   "value":"'.$phone_mob.'",
                   "color":"#000"
               },
              "keyword3": {
                 "value":"'.$do_time.'",
                 "color":"#000"
              }
            }  
       	}';
    }          
	  $res = $this->https_post($url,$data);
	  return json_decode($res);
	}
  //审核通知模版
	public function SH($openid,$username,$wxname,$phone_mob,$hq_status,$do_time){
    $access_token = $this->get_token();
    $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		
	 $data = '{
   "touser":"'.$openid.'",
   "template_id":"REdWzEJY41vik98I8K_7UXVqz0Gs3e4lcxJCF_gN4vs",
   "url":"http://yooopay.com/index.php?m=User&a=index&isshop=1",';
    if ($hq_status == 1) {
      	$data.='"data":{
               "first": {
                   "value":"恭喜'.$username.',您申请已经审核成功！欢迎加入我们团洋范大平台,我将送您到微商的下一个风口!",
                   "color":"#000"
               },
               "keyword1":{
                   "value":"'.$username.'",
                   "color":"#000"
               },
               "keyword2": {
                   "value":"'.$phone_mob.'",
                   "color":"#000"
               },
               "keyword3": {
                   "value":"'.$do_time.'",
                   "color":"#000"
               },
               "remark":{
                   "value":"微信号:'.$wxname.',您可以通过菜单【卖家】-【邀请链接】获取您的邀请链接(用于发展代理商).卖家中心首次登陆密码:123456,为了账号安全,请登录后修改",
                   "color":"#000"
               }
            } 
       	}';          
    }else{
    	$data.='"data":{
              "first": {
                 "value":"很遗憾'.$username.',您的申请未审核通过！",
                 "color":"#000"
              },
              "keyword1":{
                 "value":"'.$username.'",
                 "color":"#000"
              },
              "keyword2": {
                 "value":"'.$phone_mob.'",
                 "color":"#000"
              },
              "keyword3": {
                 "value":"'.$do_time.'",
                 "color":"#000"
              },
              "remark":{
                 "value":"微信号:'.$wxname.',感谢您关注团洋范!",
                 "color":"#000"
              }
            }  
       	}';
    }          
	  $res = $this->https_post($url,$data);
	  return json_decode($res);
	}
   //发送代理通知模板信息
  public function DLTZ($openid,$data){
	  $access_token = $this->get_token();
    $url='https://api.weixin.qq.Com/cgi-bin/message/template/send?access_token='.$access_token;
	  $data = '{
			   "touser":"'.$openid.'",
			   "template_id":"k1SFJXNwo0go0_dwj0NHCLEwDEeOmb_Ih2w8WnZ9fjY",
			   "url":"http://yooopay.com/index.php?m=User&a=index&isshop=1",
			   "data":{
				   "first": {
					   "value":"'.$data["text"].'",
					   "color":"#000"
				   },
				   "keyword1":{
					   "value":"'.$data["username"].'",
					   "color":"#000"
				   },
				   "keyword2": {
					   "value":"'.$data["wxname"].'",
					   "color":"#000"
				   },
				   "keyword3": {
					   "value":"'.$data["phone_mob"].'",
					   "color":"#000"
				   },
				   "remark":{
					   "value":"点击通知查看详情,如有疑问请联系客服！",
					   "color":"#000"
				   }
				}
       	}';
    $res = $this->https_post($url,$data);
    return json_decode($res);  
  }
  //发送收入模板信息
  public function SR($openid,$price,$do_time,$data="店铺零售奖金"){
	  $access_token = $this->get_token();
    $url='https://api.weixin.qq.Com/cgi-bin/message/template/send?access_token='.$access_token;
	  $data = '{
			   "touser":"'.$openid.'",
			   "template_id":"oTfgr_XBJacHAKdFyC2Ybm4CzXsrT9SNJ2p7zD_jorU",
			   "url":"http://yooopay.com/index.php?m=User&a=index&isshop=1",
			   "data":{
				   "first": {
					   "value":"您新增一笔代理收入",
					   "color":"#000"
				   },
				   "keyword1":{
					   "value":"'.$data.'",
					   "color":"#000"
				   },
				   "keyword2": {
					   "value":"'.$price.'",
					   "color":"#000"
				   },
				   "keyword3": {
					   "value":"'.$do_time.'",
					   "color":"#000"
				   },
				   "remark":{
					   "value":"点击通知查看详情,如有疑问请联系客服！",
					   "color":"#000"
				   }
				}
       	}';
    $res = $this->https_post($url,$data);
    return json_decode($res);  
  }
  
  
   //发送手动添加提成模板信息
  public function SendUser($openid,$price,$do_time,$data="满足奖励条件"){
	  $access_token = $this->get_token();
    $url='https://api.weixin.qq.Com/cgi-bin/message/template/send?access_token='.$access_token;
	  $data = '{
			   "touser":"'.$openid.'",
			   "template_id":"oTfgr_XBJacHAKdFyC2Ybm4CzXsrT9SNJ2p7zD_jorU",
			   "url":"http://yooopay.com/index.php?m=User&a=index&isshop=1",
			   "data":{
				   "first": {
					   "value":"您新增一笔奖励提成 ^_^",
					   "color":"#000"
				   },
				   "keyword1":{
					   "value":"'.$data.'",
					   "color":"#000"
				   },
				   "keyword2": {
					   "value":"'.$price.'",
					   "color":"#000"
				   },
				   "keyword3": {
					   "value":"'.$do_time.'",
					   "color":"#000"
				   },
				   "remark":{
					   "value":"点击通知查看详情，如有疑问请联系客服！",
					   "color":"#000"
				   }
				}
       	}';
    $res = $this->https_post($url,$data);
    return json_decode($res);  
  }
  
  public function no_pay($openid,$time,$orderid,$username){
	  $access_token = $this->get_token();
    $url='https://api.weixin.qq.Com/cgi-bin/message/template/send?access_token='.$access_token;
	  $data = '{
			   "touser":"'.$openid.'",
			   "template_id":"Ql7xleS5I9CUschaJPr1cwjYDzAjc6naEQ9eX_QUEaE",
			   "url":"http://yooopay.com/index.php?m=User&a=order_list",
			   "data":{
				   "first": {
					   "value":"亲爱的饭团【'.$username.'】您好，您有订单未支付！",
					   "color":"#000"
				   },
				   "ordertape":{
					   "value":"'.$time.'",
					   "color":"#000"
				   },
				   "ordeID": {
					   "value":"'.$orderid.'",
					   "color":"#000"
				   },
				   
				   "remark":{
					   "value":"点击详情可查看订单进行支付，如有疑问请联系客服！",
					   "color":"#000"
				   }
				}
       	}';
    $res = $this->https_post($url,$data);
    return json_decode($res);  
  }

  public function KF_M($openid,$data){
    $access_token = $this->get_token();
    $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;  
	$data='{
			  "touser": "'.$openid.'", 
			  "msgtype": "text", 
			  "text": {
				"content": "'.$data.'"
			  }
		   }';
    $res = $this->https_post($url,$data);
    return json_decode($res);  
  }
		
	

	public function get_token(){
    
		//获取appid
		$setting = D('setting');
		$appid = $setting->where(array('name'=>'appid'))->find();
		//获取appsecret
		$appsecret = $setting->where(array('name'=>'appsecret'))->find();
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid['data']."&secret=".$appsecret['data'];
		//获取access_tooken
		$data = json_decode($this->get_http($url),true);
		$access_token = $data['access_token'];
		S('access_token1',$access_token,7200);
		return $access_token;
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

	public function https_post($url,$data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);   
		curl_close($curl);
		return $result; 
	} 
}
?>