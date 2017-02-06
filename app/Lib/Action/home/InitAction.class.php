<?php
class InitAction extends Action {
	
	//获取用户信息
	public function getInfo(){
		$unionid = $_GET['unionid'];
		if(!$unionid){
			$jsonArr['status'] = 0;
    	}else{
			$user_info = M('user')->where(array('unionid'=>$unionid))->find();
			if($user_info){
				cookie('user_id',$user_info['id'],3600*7);
				$_SESSION['user_info'] = $user_info;
				if($user_info['hyimg']==''){
					$jsonArr['cover'] = $user_info['cover'];
				}else{
					$jsonArr['cover'] = "http://yooopay.com".$user_info['hyimg'];
					//$jsonArr['cover'] = "http://api.yooopay.com/yf".$user_info['hyimg'];
				}
				$jsonArr['id'] = $user_info['id'];	
				$jsonArr['status'] = 1;
			}else{
				$jsonArr['status'] = 0;
			}
		}
		echo json_encode($jsonArr);
	}
	
	//添加用户
	public function add_user(){
		$default_data['pwd'] = md5('123456');
		$default_data['hyimg'] = '/data/upload/avatar/000/00/00/default_200.jpg';
		$userId = M('user')->add($default_data);
		if($userId){
			$data['username'] = '小范'.$userId;
			$data['nickname'] = '小范'.$userId;
			$data['reg_ip'] = get_client_ip();
			$data['reg_time'] = time();
			$data['last_time'] = time();
			if(M('user')->where(array('id'=>$userId))->save($data)){
				$user_info = M('user')->where(array('id'=>$userId))->find();
				cookie('user_id',$user_info['id'],3600*7);
				$_SESSION['user_info'] = $user_info;
				if($user_info['hyimg']==''){
					$jsonArr['cover'] = $user_info['cover'];
				}else{
					$jsonArr['cover'] = "http://yooopay.com".$user_info['hyimg'];
					//$jsonArr['cover'] = "http://api.yooopay.com/yf".$user_info['hyimg'];
				}
				$jsonArr['id'] = $user_info['id'];	
				$jsonArr['username'] = $user_info['username'];	
				$jsonArr['pwd'] = '123456';	
				$jsonArr['status'] = 1;
			}else{
				$jsonArr['status'] = 0;
			}
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//用户已临时身份登录
	public function login(){
		if($_REQUEST['fg']=='Android'){
			$params['username'] = $_REQUEST['username'];
			$params['pwd'] = $_REQUEST['pwd'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
		}
		$username = $params['username'];
		$pwd = $params['pwd'];
		
		if($username==''||$pwd==''){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '用户名或密码为空';
    	}else{
			$user_info = M('user')->where(array('username'=>$username,'pwd'=>md5($pwd)))->find();
		
			if($user_info){
				cookie('user_id',$user_info['id'],3600*7);
				$_SESSION['user_info'] = $user_info;
				if($user_info['hyimg']==''){
					$jsonArr['cover'] = $user_info['cover'];
				}else{
					$jsonArr['cover'] = "http://yooopay.com".$user_info['hyimg'];
					//$jsonArr['cover'] = "http://api.yooopay.com/yf".$user_info['hyimg'];
				}
				$jsonArr['id'] = $user_info['id'];	
				$jsonArr['status'] = 1;
			}else{
				$jsonArr['status'] = 0;
			}
		}
		echo json_encode($jsonArr);
	}
}