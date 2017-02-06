<?php
class frontendAction extends Action {

    protected $visitor = null;
    
    public function _initialize() {
		
		//消除所有的magic_quotes_gpc转义
        Input::noGPC();
        //初始化网站配置
        if (false === $setting = F('setting')) {
            $setting = D('setting')->setting_cache();
        }
        C($setting);
        //初始化访问者
		$user_id = cookie($user_id);
		$this->visitor = new user_visitor($user_id);
		if(is_null($this->visitor->info['id'])){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '初始化用户信息失败';
			echo json_encode($jsonArr);
			exit;
		}
    } 
	
	/**
     * 上传文件默认规则定义
     */
    protected function _upload_init($upload) {
        $allow_max = C('pin_attr_allow_size'); //读取配置
        $allow_exts = explode(',', C('pin_attr_allow_exts')); //读取配置
        $allow_max && $upload->maxSize = $allow_max * 1024;   //文件大小限制
        $allow_exts && $upload->allowExts = $allow_exts;  //文件类型限制
        $upload->saveRule = 'uniqid';
        return $upload;
    }

    /**
     * 上传文件
     */
    protected function _upload($file, $dir = '', $thumb = array(), $save_rule='uniqid') {
        $upload = new UploadFile();
        if ($dir) {
            $upload_path = 'E:/xyy/'.C('pin_attach_path') . $dir ;
            $upload->savePath = $upload_path;
        }
        if ($thumb) {
            $upload->thumb = true;
            $upload->thumbMaxWidth = $thumb['width'];
            $upload->thumbMaxHeight = $thumb['height'];
            $upload->thumbPrefix = '';
            $upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';
            $upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';
            $upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;
        }
        //自定义上传规则
        $upload = $this->_upload_init($upload);
        if( $save_rule!='uniqid' ){
            $upload->saveRule = $save_rule;
        }

        if ($result = $upload->uploadOne($file)) {
            return array('error'=>0, 'info'=>$result);
        } else {
            return array('error'=>1, 'info'=>$upload->getErrorMsg());
        }
    }
	/* public function get_http($url){
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
	
	//获取access_token（手动）
    public function get_access_token()
    {
		//获取appid
		$setting = M('setting');
		$appid = $setting->where(array('name'=>'appid'))->find();
		//获取appsecret
		$appsecret = $setting->where(array('name'=>'appsecret'))->find();
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid['data']."&secret=".$appsecret['data'];
		$access_token_Arr = json_decode($this->get_http($url),true);
		dump($access_token_Arr['access_token']);
		exit;          
    }
	
	
	public function get_unionid($openid){
		$access_token = 'W5ga3-P9DNyfZAASwE3U2JLzv7K2fR94c5p05lixDGTbrLQd9R1P3Vr8LQJh9wQjKUVqg15hMejUygQbHArqvJlCzJ-LyjH0mbYFNCpWT8MEfqVm7QKn9UsgHELk4INLEWGlCHAMEO';
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$data = json_decode($this->get_http($url),true);
		if($data['unionid']!=null){
			M('myuser')->where(array('wechatid'=>$openid,'unionid'=>''))->save(array('unionid'=>$data['unionid']));
		}
	}
	
	//由openid获取unionid
	public function get_unionids(){
		$users = M('myuser')->field('wechatid')->where("unionid ='' OR unionid IS NULL")->select();
		
		foreach($users as $key=>$val){
			$this->get_unionid($val['wechatid']);
		}
		echo json_encode(array('status'=>1,'msg'=>'SUCCESS'));
	} */
}



