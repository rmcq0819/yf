<?php

class userModel extends Model
{
    protected $_validate = array(
        array('username', 'require', '{%username_require}'), //不能为空
        array('repassword', 'password', '{%inconsistent_password}', 0, 'confirm'), //确认密码
        array('email', 'email', '{%email_error}'), //邮箱格式
        array('username', '1,20', '{%username_length_error}', 0, 'length', 1), //用户名长度
        array('password', '6,20', '{%password_length_error}', 0, 'length', 1), //密码长度
        array('username', '', '{%username_exists}', 0, 'unique', 1), //新增的时候检测重复
    );

    protected $_auto = array(
        array('password','md5',1,'function'), //密码加密
        array('reg_time','time',1,'function'), //注册时间
        array('reg_ip','get_client_ip',1,'function'), //注册IP
    );

    /**
     * 修改用户名
     */
    public function rename($map, $newname) {
        if ($this->where(array('username'=>$newname))->count('id')) {
            return false;
        }
        $this->where($map)->save(array('username'=>$newname));
        $uid = $this->where(array('username'=>$newname))->getField('id');
        //修改商品表中的用户名
        M('item')->where(array('uid'=>$uid))->save(array('uname'=>$newname));
        //修改专辑表中的用户名
        M('album')->where(array('uid'=>$uid))->save(array('uname'=>$newname));
        //评论和微薄暂时不修改。
        return true;
    }

    public function name_exists($name, $id = 0) {
        $where = "username='" . $name . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function email_exists($email, $id = 0) {
        $where = "email='" . $email . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * getDown(&$fenxiaos,$level,$userid,$shares);
     * getfenxiao($userid,$shares,$level=1);//获取所有下线
     * 2016-07-06  By Liuyumei
     */


    public function getDown(&$fenxiaos,$level,$userid,$shares,$reg_time){
        $where = array(
                'shares_tree' => array('like','%|'.$userid.'|-%'),
                'status' => 1,
				'uid'=>array('lt','4')
            );
        $users=$this->where($where)->select();
       
        foreach ($users as $key => $value) {
            $v['id']=$value['id'];
			$v['uid']=$value['uid'];
            $v['username']=$value['username'];
            $v['shares']=$shares;    
            $v['level']=$level;
			$v['reg_time']=$value['reg_time'];
            $fenxiaos[]=$v;
            $this->getDown($fenxiaos,$level+1,$value['id'],$value['username'],$value['reg_time']);
        }
    }


/*    public function getfenxiao_s($userid,$shares,$level=1){//测试用
        static $fenxiao_s = array();
        $this->getDown($fenxiao_s,$level,$userid,$shares);
        return $fenxiao_s;
    }*/

    public function getfenxiao($userid,$shares,$level=1){
       static $fenxiaos = array();
       $this->getDown($fenxiaos,$level,$userid,$shares);


    /*	$where = array('shares'=>$userid,'status'=>1);    //修改前代码     测试用
        
        //2016-04-19 by shuguang 添加的判断 start
        $uinfo = $this->where('id='.$userid)->find();//当前用户信息
        if($uinfo['uid']==3){//用户组 1=团长 2=掌柜 3=店长 4=消费者
            $where = array(
                'uid' => 3,
                'shares' => $uinfo['shares'],
                'shares_tree' => array('like','%|'.$userid.'|-%'),
                'status' => 1,
                'recom' => $userid
            );
        }
        if($uinfo['uid']==2){//用户组 1=团长 2=掌柜 3=店长 4=消费者
            $where = array(
                'uid' => 3,
               // 'shares' => $uinfo['shares'],
                'shares_tree' => array('like','%|'.$userid.'|%'),
                'status' => 1,
                //'recom' => $userid
            );
        }
        //2016-04-19 by shuguang 添加的判断 end

    	//获取当前userid下的所有分销商
        $shares=$this->field('username')->where(array('id'=>$userid))->find();
    	$res = $this->field('id,uid,username,reg_time,cover')->where($where)->select();

        //2016-04-20 by shuguang 添加 start
		
		if($uinfo['uid']==2){//用户组 1=团长 2=掌柜 3=店长 4=消费者
            $res = array();
            $datas['uid'] = 2;
            $datas['shares_tree'] = array("like",'%|'.$userid.'|%');
            $mysons = $this->where($datas)->field("id")->select();//掌柜的所有直推掌柜和育成掌柜的id
            
            foreach ($mysons as $key => $value) {
                $tiaojian['uid'] = 3;
                $tiaojian['shares'] = $value['id'];
                //echo $value['id'].'<br>';
                $results1 = $this->field('id,uid,username,reg_time,cover')->where($tiaojian)->select();
                if($results1){
                    foreach ($results1 as $ks => $vs) {
                        $res[] = $vs;
                    }
                }
            }
            $mywhere['uid'] = 3;
            $mywhere['shares'] = $userid;
            $results2 = $this->field('id,uid,username,reg_time,cover')->where($mywhere)->select();
            if($results2){
                foreach ($results2 as $ks => $vs) {
                    $res[] = $vs;
                }
            }
        }
		
            foreach ($res as $key => $value) { 
                if($value['uid']==4){//消费者不是下级店长 
                    unset($res[$key]);
                }
            }
        
        //2016-04-20 by shuguang 添加 end
        //print_r($res);
        //echo $this->getLastSql().'<br>';
    	foreach ($res as $k=>$v){
    		$v['level'] = $level;
            $v['shares']= $shares['username'];
    		$fenxiaos[] = $v;
    		$this->getfenxiao($v['id'],$level+1);
    	}*/
    	return $fenxiaos;
    }

     /**
     *  判断代理是否符合升级条件
     *  @author vany 
     *  date 2015-12-14
     */
	public function getDowns(&$fenxiaos,$userid){
		$user=M('user');
        $where = array(
                'shares_tree' => array('like','%|'.$userid.'|-%'),
                'status' => 1,
				'uid'=>array('lt','4')
            );
        $users=$user->where($where)->select();
       
        foreach ($users as $key => $value) {
            $fenxiaos[]=$value['id'];
            $this->getDowns($fenxiaos,$value['id']);
        }
    }// 2016-07-11 Modify by Liuyumei
    public function check_lv($id,$uid){ 
        //如果当前会员等级为boss
        if ($uid == 1||$uid == 2) {
            return false;
            exit;
        }
        if ($uid == 3) {
            static $fenxiaos = array();
            $this->getDowns($fenxiaos,$id);
			$res['num']=0;
			$res['zt']=0;
			foreach($fenxiaos as $key=>$value){
				$uinfo =M('user')->where('id='.$value)->find();//当前月新增用户信息
				//dump($uinfo);
				if($uinfo['uid']==3&&$uinfo['reg_time']>=mktime(24, 0, 0, date("m"), 0, date("Y"))){//新增总数
					$res['num']+=1;
				}
				$tree = explode('|',$uinfo['shares_tree']);//直接下级的shares_tree
				$nu=count($tree);
				if($uinfo['uid']==3&&$tree[$nu-2]==$id&&$uinfo['reg_time']>=mktime(24, 0, 0, date("m"), 0, date("Y"))){//直推新增数
					$res['zt']+=1;
				}	
			}
        }
		
		
        //获取用户升级条件
        $where['id'] = $uid-1;     //用户将升级到的等级
        $condition = M('user_category')->where($where)->find();   //相应等级应该具备的条件
        if ($res['zt'] >= $condition['upgrade1']) {  //升级条件满足 status=1
            $res['status'] = 1;   
			return $res;
        }else{
			$res['status'] = 0;
            return $res;
        }
    }

    /* *
     *  通过用户id获取卖咖总数
     *  @author vany 
     *  date 2015-12-11
     */
  /*  public function get_mk($user,$uid){// 307 3
        if ($uid == 2) {
            $data['shares'] = $user;
        }else{
            $data['recom']  = $user;
        }
        $data['uid'] = 3;
        $res = $this->where($data)->field('id')->select();
        $recom += count($res);
		
        if ($res) {
            foreach ($res as $key => $value) {
                $this->get_mk($value['id']);
            }
        }
        return $recom;
    } */
}