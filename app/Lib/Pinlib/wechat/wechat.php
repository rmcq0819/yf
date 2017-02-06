<?php
require_once dirname(__FILE__) . '/wechat.class.php'; //导入微信SDK
class wechat_oauth
{
    private $_need_request = array('code', 'state');

    public function __construct($setting) {
        //组装回调地址
        $this->redirect_uri = U('wxoauth/callback', array('mod'=>'wechat'), '', '', true);
        $this->setting = $setting;
    }
    /**
     * 获取授权地址
     */
    function getAuthorizeURL() {
        $oauth = new WeTOAuthV2($this->setting['appid'], $this->setting['appsecret']);
        return $oauth->getAuthorizeURL($this->redirect_uri);
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo($request_args) {
        $oauth = new WeTOAuthV2($this->setting['appid'], $this->setting['appsecret']);
        $keys = array('code'=>$request_args['code'], 'state'=>$request_args['state'], 'redirect_uri'=>$this->redirect_uri);
        $token = $oauth->getAccessToken($keys);
        $openid = $token["openid"];
        $user = $oauth->getUserInfo($token["access_token"], $openid);
        $result['keyid'] = $openid;
        $result['keyname'] = $user['nickname'];
        $data['province'] = $user['province'];
        $data['city'] = $user['city'];
        $data['gender'] = $user['sex'];
		$data['headerimg'] = $user['headimgurl'];
		$result['bind_info']=serialize($data);
        return $result;
    }

    /**
     * 推送信息
     */
    public function send($bind_user, $data) {
        $token = unserialize($bind_user['info']);
        $client = new WeTOAuthV2($this->setting['appid'], $this->setting['appsecret']);
        try {
            $return = $client->add_topic($token['access_token'], $bind_user['keyid'], array(
                'format' => '',
                'richtype' => '2',
                'richval' => $data['url'],
                'con' => $data['content'],
                'lbs_nm' => '',
                'lbs_x' => '',
                'lbs_y' => '',
                'third_source' => '',
            ));
        }catch(Exception $e){}
    }
  
    public function NeedRequest() {
        return $this->_need_request;
    }
}