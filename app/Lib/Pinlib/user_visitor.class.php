<?php
class user_visitor {
    public $info = null;

    public function __construct($user_id) {
		if (session('user_info')) {
            $this->info = session('user_info');
        }else{
			$user= M('user')->field('id,uid,username,wechatid')->where(array('id'=>$user_id))->find();
			if($user){
				M('user')->where(array('id'=>$user['id']))->save(array('last_time' => time(), 'last_ip' => get_client_ip()));
				$this->info = $user;
				$_SESSION['user_info']=$user;
			}
		}
    }
}
?>