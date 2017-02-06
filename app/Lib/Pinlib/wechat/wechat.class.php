<?php
/**
 * 微信
 */
class WeTOAuthV2 {
    public $appid = '';
    public $appkey = '';
    public $scope = 'snsapi_userinfo';
    private $_authorize_url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
	private $_access_token_url='https://api.weixin.qq.com/sns/oauth2/access_token';
    function __construct($appid, $appkey) {
        $this->appid = $appid;
        $this->appkey = $appkey;
    }
    function getAuthorizeURL($callback) {
        //$state = md5(uniqid(rand(), TRUE));
        $url = $this->_authorize_url . "?appid=" 
		. $this->appid . "&redirect_uri=" . urlencode($callback) 
		. "&response_type=code&scope=" . $this->scope . "&state=" . $state . "#wechat_redirect";
       // cookie('wechat_state', $state);
      	return $url;
    }
    function getAccessToken($keys) {
      //  $wechat_state = cookie('wechat_state');
       // if ($keys['state'] == $wechat_state) {
            $token_url = $this->_access_token_url . "?appid="
              . $this->appid . "&secret=" . $this->appkey
              . "&code=" . $keys["code"]."&grant_type=authorization_code";

            $response = $this->get_url_contents($token_url);
            if (!$response) {
                exit('system error');
            }
			$params = json_decode($response,true);
            return $params;
        //} else {
          //  echo("The state does not match. You may be a victim of CSRF.");
        //}
    }
	/*
    function getOpenid($access_token) {
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $access_token;
        $str  = file_get_contents($graph_url);
        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
        }
        $user = json_decode($str);
        if (isset($user->error)) {
            echo "<h3>error:</h3>" . $user->error;
            echo "<h3>msg  :</h3>" . $user->error_description;
            exit;
        }
        return $user->openid;
    }*/
    function getUserInfo($access_token, $openid) {
	
        $get_user_info = "https://api.weixin.qq.com/sns/userinfo?"
            . "access_token=".$access_token
            . "&openid=".$openid
            . "&lang=zh_CN";
        $info = $this->get_url_contents($get_user_info);
        $arr = json_decode($info, true);
        return $arr;
    }

    /**
     * 推送消息
     */
    function add_topic($access_token, $openid, $topic) {
        $url  = "https://graph.qq.com/shuoshuo/add_topic";
        $data = "access_token=".$access_token
            ."&oauth_consumer_key=".$this->appid
            ."&openid=".$openid
            ."&format=".$topic["format"]
            ."&richtype=".$topic["richtype"]
            ."&richval=".urlencode($topic["richval"])
            ."&con=".urlencode($topic["con"])
            ."&lbs_nm=".$topic["lbs_nm"]
            ."&lbs_x=".$topic["lbs_x"]
            ."&lbs_y=".$topic["lbs_y"]
            ."&third_source=".$topic["third_source"];
        $ret = $this->do_post($url, $data);
        return $ret;
    }

    function do_post($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    function get_url_contents($url) {
//        if (ini_get("allow_url_fopen") == "1")
//            return file_get_contents($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $result =  curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}