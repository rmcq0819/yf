<?php
include_once("class.apiconfig.php");
class UserinfoAction extends frontendAction {
	
	/***** 用户基本信息 *****/
	public function user_index(){
		$user = M('user')->where(array('id'=>$this->visitor->info['id']))->field('id,username,cover,reg_time,hyimg,uid,login_days,v1')->find();
		if($user['hyimg']!=''){
			$jsonArr['cover'] =  ApiConfig::SERVER_PATH.$user['hyimg'];
			//$jsonArr['cover'] = "http://api.yooopay.com/yf".$user['hyimg'];
		}else{
			$jsonArr['cover'] = $user['cover'];
		}
		$jsonArr['username'] = $user['username'];
		$uaddtime = time() - $user['reg_time'];
		$jsonArr['utime'] = ceil($uaddtime/3600/24).'';//开始创业的天数
		$jsonArr['regtime'] = $user['reg_time'];
		$jsonArr['number'] = ''.$user['id']*3;
		$jsonArr['uid'] = $user['uid'];
		if($user['uid']!=4&&$user['v1']==1&&$user['login_days']>=7){
			$jsonArr['hot'] = '1';
		}else{
			$jsonArr['hot'] = '0';
		}
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	/***** 不同条件订单数量 *****/
	public  function order_count(){
		$item_order = M('item_order');
		//我的待付款总数
		$wfpCount = $item_order->where('status=1 and is_delete=0 and userId='.$this->visitor->info['id'])->count();
		//我的待发货总数
		$tbsCount = $item_order->where('status=2 and is_delete=0 and userId='.$this->visitor->info['id'])->count();
		//我的待收货总数
		$rogCount = $item_order->where('status=3 and is_delete=0 and userId='.$this->visitor->info['id'])->count();
		//店铺全部订单总数
		$allCount = $item_order->where('is_delete=0 and userId='.$this->visitor->info['id'])->count();
		$jsonArr['wfpCount'] = $wfpCount;
		$jsonArr['tbsCount'] = $tbsCount;
		$jsonArr['rogCount'] = $rogCount;
		$jsonArr['allCount'] = $allCount;
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	
	/***** 未读信息条数 *****/
	public  function msg_count(){
		$msglist = M('messagelist');
		//未读信息条数
		$msgCount = $msglist->where('status=0 and user_id='.$this->visitor->info['id'])->count();
		
		$jsonArr['msgCount'] = $msgCount;
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	
	/***** 基本信息页面 *****/
	public function base_info(){
		$info = M('user')->where(array('id'=>$this->visitor->info['id']))->field('id,hyimg,cover,username,phone_mob,email,zhanghao,huming,kaihuhang')->find();
        $banks = M('bank')->order('id asc')->field('id,bank')->select();
		$jsonArr['id'] = $info['id'];
		$jsonArr['username'] = $info['username'];
		$jsonArr['phone_mob'] = $info['phone_mob'];
		$jsonArr['email'] = $info['email'];
		$jsonArr['zhanghao'] = $info['zhanghao'];
		$jsonArr['huming'] = $info['huming'];
		$jsonArr['kaihuhang'] = $info['kaihuhang'];
		if($info['hyimg']==''){
			$jsonArr['cover'] = $info['cover'];
		}else{
			$jsonArr['cover'] = ApiConfig::SERVER_PATH.$info['hyimg'];
			//$jsonArr['cover'] ="http://api.yooopay.com/yf".$info['hyimg'];
		}
		$jsonArr['banks'] = $banks;
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	/***** 修改基本信息 *****/
	public function edit_info(){
		if($_REQUEST['fg']=='Android'){
			if($_REQUEST['username']!=''){
				$data['nickname'] = $_REQUEST['username'];
				$data['username'] = $_REQUEST['username'];
			}
			if($_REQUEST['phone_mob']!=''){
				$data['phone_mob']   = $_REQUEST['phone_mob'];
			}
			if($_REQUEST['email']!=''){
				$data['email']   = $_REQUEST['email'];
			}
			if($_REQUEST['zhanghao']!=''){
				$data['zhanghao']   = $_REQUEST['zhanghao'];
			}
			if($_REQUEST['huming']!=''){
				$data['huming']   = $_REQUEST['huming'];
			}
			if($_REQUEST['kaihuhang']!=''){
				$data['kaihuhang']   = $_REQUEST['kaihuhang'];
			}
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			
			
			if($params['username']!=''){
				$data['nickname'] = $params['username'];
				$data['username'] = $params['username'];
			}
			if($params['phone_mob']!=''){
				$data['phone_mob']   = $params['phone_mob'];
			}
			if($params['email']!=''){
				$data['email']   = $params['email'];
			}
			if($params['zhanghao']!=''){
				$data['zhanghao']   = $params['zhanghao'];
			}
			if($params['huming']!=''){
				$data['huming']   = $params['huming'];
			}
			if($params['kaihuhang']!=''){
				$data['kaihuhang']   = $params['kaihuhang'];
			}
		}
		if(!empty($_FILES['avatar']['name'])){
			$data['hyimg'] = $this->uploadPic('avatar','avatar');//上传的头像所在的文件名称
		}
		$result = M('user')->where(array('id' =>$this->visitor->info['id']))->save($data);
		if($result){
			echo json_encode(array('status'=>1,'msg'=>'修改成功'));
		}else{
			echo json_encode(array('status'=>0,'msg'=>'您没有修改'));
		}
	}
	public function uploadPic($fname,$fdir){
		//省份证正面图片规格
		$avatar_size = explode(',', C('pin_avatar_size'));
		//省份证图片保存文件夹
		$uid = abs(intval($this->visitor->info['id']));
		$suid = sprintf("%09d", $uid);
		$dir1 = substr($suid, 0, 3);
		$dir2 = substr($suid, 3, 2);
		$dir3 = substr($suid, 5, 2);
		$avatar_dir = $dir1.'/'.$dir2.'/'.$dir3.'/';
		//图片
		$suffix = '';
		foreach ($avatar_size as $size) {
			$suffix .= '_'.$size.',';
		}
		$result = $this->_upload($_FILES[$fname], $fdir.'/'.$avatar_dir, array(
			'width'=>C('pin_avatar_size'),
			'height'=>C('pin_avatar_size'),
			'remove_origin'=>true,
			'suffix'=>trim($suffix, ','),
			'ext' => 'jpg',
		), md5($uid));
		if ($result['error']) {
			echo json_encode(array('status'=>0,'msg'=>'上传图片失败'));
			exit;
		}else {
			import('ORG.ThumbHandle');
		 /* 	$path = dirname(dirname(dirname(dirname(dirname(__FILE__))))); 
			$path = str_replace('\\','/',$path); */
			$path = "E:/xyy/";
			$imgparth = $path.'/data/upload/'.$fdir.'/'.$avatar_dir.md5($uid).'_'.$size.'.jpg';
			$imgparth_100 = $path.'/data/upload/'.$fdir.'/'.$avatar_dir.md5($uid).'_100'.'.jpg';

			$img = new ThumbHandle();

			$img->param($imgparth)->thumb($imgparth, 200,200,1);//将生成的图片强制居中截取成200*200的图片

			$img->param($imgparth)->thumb($imgparth_100, 100,100,1);//将原来生成的100高的图片，利用200的图片强制居中截取成100*100的图片
			$data = '/data/upload/'.$fdir.'/'.$avatar_dir.md5($uid).'_'.$size.'.jpg?'.time();
		}
        return $data;
    }
	
	/***** 浏览记录 *****/
	public function history(){
		$date = date('Y-m-d', strtotime('-7 days'));
		$t = strtotime($date);
        $posslike_mod = M('posslike');
		$pitemIds = $posslike_mod->where(array('userid'=>$this->visitor->info['id'],'addtime'=>array('gt',$t)))->order('id desc')->field('itemid')->select();
		
		foreach($pitemIds as $key=>$val){
			$getids[] = $val['itemid'];
		}

		if(count($getids)>0){
			$map['id'] = array('in',$getids);
			$itemArr = M('item')->where($map)->field('id,title,price,img')->select();
			if(count($itemArr)>0){
				foreach($itemArr as $key=>$val){
					$itemArr[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
				}
				$jsonArr['history'] = $itemArr;
				$jsonArr['status'] = 1;
			}else{
				$jsonArr['status'] = 0;
			}
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	/***** 删除浏览记录 *****/
	public function del_history(){
		$posslike_mod = M('posslike');
		$pitemIds = $posslike_mod->where(array('userid'=>$this->visitor->info['id']))->delete();
		if($pitemIds){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 收藏 *****/
	public function collections(){
		$item_likeMd = M('item_like');
        $uid = $this->visitor->info['id'];
        $where['uid'] = $uid;
        $item_likeArr = $item_likeMd->join('a left join weixin_item b on a.item_id= b.id')->where($where)->order('a.add_time DESC')->field('b.id,img,title,price')->select();
        if(count($item_likeArr)>0){
			foreach($item_likeArr as $key=>$val){
				$item_likeArr[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['kileItems'] = $item_likeArr;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	/***** 地址列表 *****/
	public function addr_list(){
		$user_address_mod = M('user_address');
        $uid = $this->visitor->info['id'];
        $where['uid'] = $uid;
        $addrArr = $user_address_mod->where($where)->order('is_default desc')->field('id,consignee,towns,address,mobile,is_default')->select();
        if(count($addrArr)>0){
			$jsonArr['addrList'] = $addrArr;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	/***** 设置默认地址 *****/
	public function edit_addr(){
    
		$user_address_mod = M('user_address');
		$id = $this->_get('id', 'intval');
		
		$data['is_default'] = 0;
		$user_address_mod->where(array('is_default' => 1))->save($data);//将已经默认的取消
		
		$data['is_default'] = 1;
		$result = $user_address_mod->where(array('id' =>$id))->save($data);
		if($result){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	/***** 删除地址 *****/
	public function del_addr(){
    
		$user_address_mod = M('user_address');
		$id = $this->_get('id', 'intval');
		
		$result = $user_address_mod->where(array('id' =>$id))->delete();
		if($result){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	/***** 添加地址 *****/
	public function add_addr(){
    
		$user_address_mod = M('user_address');
		$data['uid'] = $this->visitor->info['id'];
		if($_REQUEST['fg']=='Android'){
			$data['consignee'] = $_REQUEST['consignee'];
			$data['address'] = $_REQUEST['address'];
			$data['mobile'] = $_REQUEST['mobile'];
			$data['sheng'] = $_REQUEST['sheng'];
			$data['shi'] = $_REQUEST['shi'];
			$data['qu'] = $_REQUEST['qu'];
			$data['towns'] = $_REQUEST['towns'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			
			
			$data['consignee'] = $params['consignee'];
			$data['address'] = $params['address'];
			$data['mobile'] = $params['mobile'];
			$data['sheng'] = $params['sheng'];
			$data['shi'] = $params['shi'];
			$data['qu'] = $params['qu'];
			$data['towns'] = $params['towns'];
		}
	 	$result = $user_address_mod->add($data);
		if($result){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		} 
	 
		echo json_encode($jsonArr);
    }
	
	/***** 身份证信息列表 *****/
	public function card_list(){
		$cards = M('idcard')->where('uid = '.$this->visitor->info['id'])->field('id,c_id,c_name')->select();
		if(count($cards)>0){
			$jsonArr['status'] = 1;
			$jsonArr['cards'] = $cards;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	/***** 删除身份证信息 *****/
	public function del_card(){
		$idcard_mod = M('idcard');
		$id = $this->_get('id', 'intval');
		
		$result = $idcard_mod->where(array('Id'=>$id))->delete();
		if($result){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 添加身份证信息 *****/
	public function add_card() {	
		if($_REQUEST['fg']=='Android'){
			$name = $_REQUEST['name'];
			$cardId = $_REQUEST['cardId'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			$name = $params['name'];
			$cardId = $params['cardId'];
		}
        $card=M('idcard');
        $data['c_name'] = $name;
        $data['c_id'] = $cardId;
        $data['uid'] = $this->visitor->info['id'];
		$result = $card->add($data);
		if($result){
			$jsonArr['data'] = $data;
			$jsonArr['params'] = $params;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	

	
	/***** 范票-赚了多少 *****/
	public function point_gain(){
        $imsg = M('messagelist');
		$where['type'] = array('in','5,6,8,9,11,20');
		$where['recom'] = $this->visitor->info['id'];
        $point_list = $imsg->where($where)->order('id desc')->field('type,time,points')->select();
        if($point_list){
			foreach($point_list as $key=>$val){
				switch ($val['type'])
				{
					case 5:
						$point_list[$key]['desc'] = '购物奖励范票';
						break;  
					case 6:
						$point_list[$key]['desc'] = '评论奖励范票';
						break;
					case 8:
						$point_list[$key]['desc'] = '红包奖励范票';
						break;
					case 9:
						$point_list[$key]['desc'] = '系统奖励范票';
						break;
					case 11:
						$point_list[$key]['desc'] = '红包退还范票';
						break;
					case 20:
						$point_list[$key]['desc'] = '转盘抽奖范票';
						break;
					default:
						$point_list[$key]['desc'] = '赚的范票';
				}
				unset($point_list[$key]['type']);
			}
			$points = $imsg->where($where)->sum('points');
			$jsonArr['status'] = 1;
			$jsonArr['pointList'] = $point_list;
			$jsonArr['points'] = $points;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	
	/***** 范票-花了多少 *****/
	public function point_spend(){
        $imsg = M('messagelist');
       
		$where['type'] = array('in','7,10,21');
		$where['recom'] = $this->visitor->info['id'];
        $point_list = $imsg->where($where)->order('id desc')->field('type,time,points')->select();
		$points = $imsg->where($where)->sum('points');
        if($point_list){
			foreach($point_list as $key=>$val){
				switch ($val['type'])
				{
					case 7:
						$point_list[$key]['desc'] = '购物抵扣范票';
						break;  
					case 10:
						$point_list[$key]['desc'] = '范票红包分享';
						break;
					case 21:
						$point_list[$key]['desc'] = '范票抽奖机会';
						break;
					case 26:
						$point_list[$key]['desc'] = '范票商城兑换';
						break;
					default:
						$point_list[$key]['desc'] = '花的范票';
				}
				unset($point_list[$key]['type']);
			}
			
			$jsonArr['status'] = 1;
			$jsonArr['pointList'] = $point_list;
			$jsonArr['points'] = $points;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	/***** 范票总数 *****/
	public function point_yuer(){
		//用户的范票总额
		$point_yuer = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('points');
		$jsonArr['status'] = 1;
		$jsonArr['points'] = $point_yuer;
		echo json_encode($jsonArr);
	}
	
	/***** 分享范票 *****/
	/*  public function redbag_sharedo(){
		//用户的范票总额
		$point_yuer = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('points');
        $points = $this->_post('points','trim'); //范票数
        $message = $this->_post('message','trim'); //红包附言
		if($points > $point_yuer){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '您输入的范票数高于您的账户范票总额！';
			echo json_encode($jsonArr);
			exit;
		}
        //将用户输入的范票数写进我的红包
        $redPackets = M('red_packets');
        $data['userId'] = $this->visitor->info['id'];
        $data['content_cate'] = 0;
        $data['content'] = $points;
		$data['allpoint'] = $points;
		if($message == ''){
			 $data['message'] = '真诚奉献，分享快乐信念';
		}else{
			$data['message'] = $message;
		}
        $data['status'] = 0;
        $data['source'] = 2; //范票放进红包
        $data['start_time'] = time();
        $data['orderId'] = 0;
        if($redPackets->add($data)){
			//记录范票抵扣
            $message = M('messagelist');
            $message->user_id = $this->visitor->info['id']; //用户id
            $message->recom = $this->visitor->info['id']; //用户id
            $message->type = 10; //范票分享
            $message->order_id =0; //订单id
            $message->time = time();
            $message->status = 0; // 默认未读状态
            $message->points = $points;
            $message->add();
            $del_point =  M('user')->where(array('id'=>$this->visitor->info['id']))->setDec('points',$points);
            if($del_point){
                $jsonArr['status'] = 1;
            }else{
				$jsonArr['status'] = 0;
			}
        }else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    } */
	

	
	/***** 优惠券 *****/
	public function coupons(){
		$userid = $this->visitor->info['id'];
		$cou_type = $this->_get('cou_type','intval');
		
		if($cou_type == 1){
			//未使用
			$where = array('a.status'=>0,'userId'=>$userid,'a.end_time'=>array('gt',time()));
		}else if($cou_type == 2){
			//已过期
			$where = array('a.status'=>0,'userId'=>$userid,'a.end_time'=>array('lt',time()));
		}else{
			//已使用
			$where = array('a.status'=>1,'userId'=>$userid);
		}
		
		$coupon = M('user_coupon')->join('a left join weixin_coupon_templet b on a.couponId=b.id')
								  ->where($where)
								  ->field('b.kind,b.title,b.condition,b.disPrice,b.exchangeCond,b.desc,a.add_time as stime,a.end_time as etime')
								  ->order('add_time desc')
								  ->select();	
		if(count($coupon)>0){
			foreach($coupon as $key=>$val){
				switch ($val['kind'])
				{
					case 1:
						if($val['condition']==0){
							$coupon[$key]['type'] = '现金券';
						}else{
							$coupon[$key]['type'] = '通用券';
						}
						break;  
					case 2:
						$coupon[$key]['type'] = '品类券';
						break;
					case 3:
						$coupon[$key]['type'] = '兑换券';
						break;
					case 4:
						$coupon[$key]['type'] = '新人券';
						break;
					default:
						$coupon[$key]['type'] = '优惠券';
				}
			}
			$jsonArr['status'] = 1;
			$jsonArr['coupon'] = $coupon;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);						  
	}
	
	/***** 领券中心 *****/
	public function coupon_templet(){
	/* 	$coupon = M('coupon_templet');
		$user_coupon = M('user_coupon');
		
		$t = time();
		$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
		$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t)); 
		
		//判断用户当天是否已领券
		$couponIds = $user_coupon->where('add_time between '.$start.' and '.$end.' and userId = '.$this->visitor->info['id'])->field('couponId')->select();
		$arr = array();
		foreach($couponIds as $key=>$val){
			$arr[] = $val['couponId'];
		}
		
		
		$starttime = mktime(6,0,0,date("m",$t),date("d",$t),date("Y",$t));
		$endtime = mktime(24,0,0,date("m",$t),date("d",$t),date("Y",$t));
		if(time()>$starttime&&time()<$endtime){
			if(date('w')==0||date('w')==6){	//周末现金券面金额
				$coupon_templet1 = $coupon->where(array('is_delete'=>0,'status'=>1,'is_recom'=>array('in','1,3'),'kind'=>1,'end_time'=>array('gt',time())))
										  ->field('id,kind,title,disPrice,condition,desc,exchangeCond,num,count,end_time')
										  ->select();
			}else{
				$coupon_templet1 = $coupon->where(array('is_delete'=>0,'status'=>1,'is_recom'=>array('in','1,2'),'kind'=>1,'end_time'=>array('gt',time())))
										   ->field('id,kind,title,disPrice,condition,desc,exchangeCond,num,count,end_time')
										   ->select();
			}
		}
		$coupon_templet2 = $coupon->where(array('is_delete'=>0,'status'=>1,'kind'=>array('neq',1),'end_time'=>array('gt',time())))
								  ->field('id,kind,title,disPrice,condition,desc,exchangeCond,num,count,end_time')
								  ->select();//全部未过期其他类券
		
		if(count($coupon_templet2)>0&&count($coupon_templet1)>0){
			$coupon_templet=array_merge($coupon_templet1,$coupon_templet2);
		}else if(count($coupon_templet2)>0){
			$coupon_templet=$coupon_templet2;
		}else if(count($coupon_templet1)>0){
			$coupon_templet=$coupon_templet1;
		}	
		
		if(count($coupon_templet)>0){
			foreach($coupon_templet as $key=>$val){
				if(in_array($val['id'],$arr)){
					$coupon_templet[$key]['haved'] = 1;
				}else{
					$coupon_templet[$key]['haved'] = 0;
				}
				switch ($val['kind'])
				{
					case 1:
						if($val['condition']==0){
							$coupon_templet[$key]['type'] = '现金券';
						}else{
							$coupon_templet[$key]['type'] = '通用券';
						}
						break;  
					case 2:
						$coupon_templet[$key]['type'] = '品类券';
						break;
					case 3:
						$coupon_templet[$key]['type'] = '兑换券';
						break;
					case 4:
						$coupon_templet[$key]['type'] = '新人券';
						break;
					default:
						$coupon_templet[$key]['type'] = '优惠券';
				}
			}
			$jsonArr['status'] = 1;
			$jsonArr['coupon_tpl'] = $coupon_templet;
			$jsonArr['couIds'] = $arr;
		}else{
			$jsonArr['status'] = 0;
		} */
		$jsonArr['status'] = 0;
		echo json_encode($jsonArr);
	}

	
	/***** 消息中心-消息分类 *****/
	public function info_count(){
        $count1 = M('messagelist')->where(array('recom'=>$this->visitor->info['id'],'type'=>1))->count();
        $count2 = M('messagelist')->where(array('recom'=>$this->visitor->info['id'],'type'=>2))->count();
        $count3 = M('messagelist')->where(array('recom'=>$this->visitor->info['id'],'type'=>3))->count();
        $count4 = M('messagelist')->where(array('recom'=>$this->visitor->info['id'],'type'=>4))->count();
		
		$jsonArr['status'] = 1;
		$jsonArr['count1'] = $count1;
		$jsonArr['count2'] = $count2;
		$jsonArr['count3'] = $count3;
		$jsonArr['count4'] = $count4;
		echo json_encode($jsonArr);
    }
	
	/***** 消息中心-消息列表*****/
	public function info_list(){
        $where['recom'] = $this->visitor->info['id'];
        $where['type'] = $this->_get('type');
        $msglist = M('messagelist')->where($where)->order('id desc')->field('id,content,time')->select();
        if(count($msglist)>0){
			foreach ($msglist as $key=>$val){
				$msglist[$key]['content'] = mb_substr(str_replace(array("\t","\r","\n"),"",$val['content']),0,20,'utf-8');
			}
			$jsonArr['status'] = 1;
			$jsonArr['msgs'] = $msglist;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	
	/***** 消息中心-删除消息*****/
	public function del_info(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $ret = M('messagelist')->where($where)->delete();
        if($ret){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	
	/***** 消息中心-消息详情*****/
	public function info_content(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $status = M('messagelist')->where($where)->getField('status');
        if($status == 0){
            $data['status'] = 1;
            M('messagelist')->where($where)->save($data);
        }
        $msg = M('messagelist')->where('id='.$id)->field('id,content')->find();
        if($msg){
			$msg['content'] = str_replace(array("\n","\t","<br/>"),"",$msg['content']);
			$msg['content'] = str_replace(array("\r"),"\n",$msg['content']);
			$jsonArr['status'] = 1;
			$jsonArr['msg'] = $msg;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	/***** 待付款/待发货/待收货订单列表*****/
	public function orderlist(){
        //获取订单状态
        $od_type = $this->_get('od_type','intval');//待付款
		
        //当前用户的信息
        $userid = $this->visitor->info['id'];
        
        $item_order=M('item_order');
        $order_detail=M('order_detail');
		
	
		$where=array('status'=>$od_type,'userId'=>$userid,'is_delete'=>0);
        $item_orders= $item_order->where($where)->order('id desc')->field('orderId,supportmetho')->select();
		
       
		
		if(count($item_orders)>0){
			foreach ($item_orders as $key=>$v){
				if($v['supportmetho']==3){
					$item_orders[$key]['supportmethod'] = '1';
				}else{
					$item_orders[$key]['supportmethod'] = '0';
				}
				unset($item_orders[$key]['supportmetho']);
				$order_details= $order_detail->where(array('orderId'=>$v['orderId']))->field('itemId,title,img,price,quantity,size')->select();
				foreach ($order_details as $k=>$val){
					$items= array('title'=>$val['title'],'img'=>ApiConfig::ITEM_IMG_PATH.$val['img'],'price'=>$val['price'],'quantity'=>$val['quantity']);
					$item_orders[$key]['item'][]=$items;
					$item_orders[$key]['count'] += $val['quantity'];
					$item_orders[$key]['totalprice'] += round($val['quantity']*$val['price']);
				}
			}
			$jsonArr['status'] = 1;
			$jsonArr['orders'] = $item_orders;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	/***** 删除订单 *****/
	public function del_order(){
		$oId = $this->_get('orderId','trim');
		$id = substr($oId,0,18);
	
		$item_order=M('item_order');
		$data['is_delete'] = 1;
		if($item_order->where("orderId like '$id%'")->save($data)){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 催促发货 *****/
	public function do_urgent(){
		$where['orderId'] = $this->_get('orderId','trim');
		$od = M('item_order');
		
		$data['is_urgent'] = 1;
		$ret=$od->where($where)->save($data);
		if($ret){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}	
		echo json_encode($jsonArr);
	}
	
	/***** 店铺订单 *****/
	public function shop_order(){
        $data['a.userId'] = $this->visitor->info['id'];
        $data['a.is_delete'] = 0;
		if(isset($_GET['cond'])){
            $cond=$this->_get('cond','trim');
			if ($cond=='one') {   //最近一个月
				$data['a.add_time']=array('gt',strtotime(date('Y-m-01',time())));
			}else if($cond=='two') {    //最近三个月
				$data['a.add_time']=array('gt',strtotime(date('Y-m-01 h:i:s',strtotime('-3 month'))));
			}else if($cond=='three'){   //三个月之前
				$data['a.add_time']=array('lt',strtotime(date('Y-m-01 h:i:s',strtotime('-3 month'))));
			}else if ($cond=='itemtype1') {   //完税
				$data['a.orderId']=array('like','%-02%');
			}else if($cond=='itemtype2') {    //保税
				$data['a.orderId']=array('like','%-01%');
			}else if($cond=='status1'){   //待付款
				$data['a.status']=1;
			}else if($cond=='status2'){   //待收货
				$data['a.status']=3;
			}else if($cond=='status3'){   //待发货
				$data['a.status']=2;
			}else if($cond=='status4'){   //已完成
				$data['a.status']=4;
			}else if($cond=='status6'){   //退货中
				$data['a.status']=6;
			}else if($cond=='status7'){   //退货失败
				$data['a.status']=7;
			}else if($cond=='status8'){   //退货成功
				$data['a.status']=8;
			}else if(strlen($cond)>0){
				$data['a.orderId|b.title|b.price'] = array('like','%'.$cond.'%');
			}
		}
		$join = "a left join weixin_order_detail as b on a.orderId = b.orderId";
        $res=M('item_order')->join($join)->where($data)->order('a.add_time desc')->field('a.orderId,a.status as ostatus,a.c_status,a.supportmetho,a.add_time')->distinct('orderId')->select();
		
		if(count($res)>0){
			foreach ($res as  $key=>$v){
				if($this->visitor->info['uid']==4){
					$fclv = 0;
				}else{
					if($v['add_time']>1480298400){
						$fclv = M('user_fengchenglv')->where(array('id'=>$this->visitor->info['uid']))->getField('royalty');
					}else{
						$fclv = 0.4;
					}
				}
				if(time()<$v['add_time']+24*3600*30){
					$res[$key]['refund'] = '1';
				}else{
					$res[$key]['refund'] = '0';
				}
				if($v['supportmetho']==3){
					$res[$key]['supportmethod'] = '1';
				}else{
					$res[$key]['supportmethod'] = '0';
				}
				unset($res[$key]['supportmetho']);
				$order_details=M('order_detail')->where("orderId='".$v['orderId']."'")->select();
				foreach ($order_details as $k=>$val){
					$items= array('title'=>$val['title'],'img'=>ApiConfig::ITEM_IMG_PATH.$val['img'],'price'=>$val['price'],'quantity'=>$val['quantity']);
					$res[$key]['item'][]=$items;
					$res[$key]['profit'] += $val['profit']*$fclv;
					$res[$key]['count'] += $val['quantity'];
					$res[$key]['totalprice'] += $val['quantity']*$val['price'];
				}
				$res[$key]['profit'] = round($res[$key]['profit'],2);
				$res[$key]['totalprice'] = round($res[$key]['totalprice'],2);
			}
			$jsonArr['status'] = 1;
			$jsonArr['orders'] = $res;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr); 
    }
	/***** 查看物流 *****/
	public function logistics(){
		
		import("@.Public.Express"); 	//物流查询类 
		$express = new Express();
		$orderId =$this->_get('orderId','trim');
	   
		
	    $order_detail =M('order_detail');
	    $map['orderId'] = $orderId;
		
	    $kuaidi_info = $order_detail->where($map)->field('freecode')->find();
		$result  = $express -> getorder($kuaidi_info['freecode'],2);

		$time_arr = array();
		$content_arr = array();
		if(count($result)>0){
			foreach($result['data'] as $k=>$v){
				if($v['ftime']&&$v['context']){
					$time_arr[] = $v['ftime'];
					$content_arr[] = $v['context'];
				}
			}
			
			$exps['times'] = $time_arr;
			$exps['contents'] = $content_arr;
			$jsonArr['exp'] = $exps;
			
			$jsonArr['status'] = 1; 
		}else{
			$jsonArr['status'] = 0; 
		}
		echo json_encode($jsonArr);
	}
	
	
	/***** 快递详情 *****/
	public function express(){
		$orderId =$this->_get('orderId','trim');
		
		$item_order = M('item_order');
		
		$kuaidi_info = $item_order->where(array('orderId'=>$orderId))->field('mobile,address,address_name,yunfei,order_sumPrice')->find();
		
		if($kuaidi_info){
			if(is_null($kuaidi_info['yunfei'])){
				$kuaidi_info['yunfei'] = '0.00';
			}
			$jsonArr['express'] = $kuaidi_info; 
			$jsonArr['status'] = 1; 
		}else{
			$jsonArr['status'] = 0; 
		}
		echo json_encode($jsonArr);
	}
	
	
	
	/***** 快递商品 *****/
	public function exp_goods(){
		$orderId =$this->_get('orderId','trim');
		$ord_detail = M("order_detail")->where(array('orderId'=>$orderId))->field("itemId,img,title,price,quantity")->order("id asc")->select();
	
		if(count($ord_detail)>0){
			foreach($ord_detail as $key=>$val){
				$ord_detail[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['items'] = $ord_detail; 
			$jsonArr['status'] = 1; 
		}else{
			$jsonArr['status'] = 0; 
		}
		echo json_encode($jsonArr);
	}
	
	/***** 订单详情 *****/
	public function order_info(){
		$orderId =$this->_get('orderId','trim');
		$item_order=M('item_order');
		$order_detail=M('order_detail');
		
		$order=$item_order->where(array('orderId'=>array('like',$orderId.'%')))->field('orderId,mobile,address,address_name,status')->find();
		if($order){
			$order_details= $order_detail->where(array('orderId'=>array('like',$orderId.'%')))->select();
			foreach ($order_details as $key=>$val){	
				$items[$key]= array('itemId'=>$val['itemId'],'title'=>$val['title'],'img'=>ApiConfig::ITEM_IMG_PATH.$val['img'],'price'=>$val['price'],'quantity'=>$val['quantity']);
				$totalprice += $val['quantity']*$val['price'];
			}
			$order['items'] = $items;
			$order['totalprice'] = round($totalprice,2);
			$jsonArr['order'] = $order;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	/***** 晒单评价 *****/
	public function comment(){
		$orderId = $this->_get('orderId','trim');
		$order_detail=M('order_detail');
		
		$order_details= $order_detail->where(array('orderId'=>$orderId,'c_status'=>0))->field('itemId,img,title,price')->select();
		if(count($order_details)>0){
			foreach ($order_details as $key=>$val){	
				$order_details[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['items'] = $order_details;
			$jsonArr['orderId'] = $orderId;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 提交评价 *****/
	public function do_comment(){
		if($_REQUEST['fg']=='Android'){
			if(empty($_REQUEST['info'])){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '未填写评价内容';
				echo json_encode($jsonArr);
				exit;
			} 
			$info = $_REQUEST['info'];
			$orderId = $_REQUEST['orderId'];
		}else{
			/* $urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			
			
			if(empty($params['info'])){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '未填写评价内容';
				echo json_encode($jsonArr);
				exit;
			} 
			$info = $params['info'];
			$orderId = $params['orderId']; */
			$params['msg'] = $_POST['info'];
			$params['orderId'] = $_POST['orderId'];
			$params['picurl1'] = $_FILES["picurl1"];
			
			if(strlen($params['msg'])<1){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '未填写评价内容';
				echo json_encode($jsonArr);
				exit;
			} 
			$info = $params['msg'];
			$orderId = $params['orderId'];
		}
		$item_commentMd = M("item_comment");
		$item_orderMd = M("item_order");
		$uid = $this->visitor->info['id'];
		
		$img = "";
		$picFile = $_FILES["picurl1"];
		if($picFile["error"] === 4) {
			$picurl1 = "";
		}else {
			$img = "data/upload/bbs/".time().$uid."_img.jpg";
			$exist = file_exists($picurl1);
			if($exist) {
				unlink($img);
			}
			/* move_uploaded_file($picFile["tmp_name"],$img); */
			$path = "E:/xyy/";
			move_uploaded_file($picFile["tmp_name"],$path.$img);
		}
		
		$data['picurl1']=$img;
		$data["uid"] = $uid;
		$data["info"] = $info;
		$data["orderId"] = $orderId;
		$data["uname"] = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('username');
		$data["add_time"] = time();
		
		$itemIds = M('order_detail')->where(array('orderId'=>$orderId))->field('itemId')->select();
		foreach($itemIds as $key=>$val){
			$data['item_id'] = $val['itemId'];
			$item_commentMd->add($data);
		}
		
		//评论都是5积分
		$order_info = $item_orderMd->where(array('orderId'=>$orderId))->field('shopid,userId')->find();
		$points = 5;
		if($order_info['userId'] == $order_info['shopid']){
			$message = D('messagelist'); 
			$message->user_id =$uid; //用户id
			$message->recom = $uid; //用户id
			$message->type = 6; //评论积分
			$message->order_id = $orderId; //订单id
			$message->time = time(); 
			$message->status = 0; // 默认未读状态
			$message->points = $points;
			if($message->add()){
				M('user')->where(array('id'=>$uid))->setInc('points',$points);
			}
		}else{
			//消费者积分
			$message = D('messagelist'); 
			$message->user_id =$uid; //用户id
			$message->recom = $uid; //用户id
			$message->type = 6; //评论积分
			$message->order_id = $orderId; //订单id
			$message->time = time(); 
			$message->status = 0; // 默认未读状态
			$message->points = $points;
			if($message->add()){
				M('user')->where(array('id'=>$uid))->setInc('points',$points);
			}
			$comment_msg = D('messagelist'); 
			$comment_msg->user_id =$uid; //商家id
			$comment_msg->recom = $order_info['shopid']; //商家id
			$comment_msg->type = 6; //评论积分
			$comment_msg->order_id = $orderId; //订单id
			$comment_msg->time = time(); 
			$comment_msg->status = 0; // 默认未读状态
			$comment_msg->points = $points;
			if($comment_msg->add()){
				M('user')->where(array('id'=>$order_info['shopid']))->setInc('points',$points);
			}
		}
		
		//评论之后的状态
		if($item_orderMd->where(array("orderId"=>$orderId))->save(array('c_status'=>1))){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		
		echo json_encode($jsonArr);
	}
	/***** 提交评价 *****/
	public function do_comment_new(){
		if($_REQUEST['fg']=='Android'){
			if(empty($_REQUEST['info'])){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '未填写评价内容';
				echo json_encode($jsonArr);
				exit;
			} 
			$info = $_REQUEST['info'];
			$orderId = $_REQUEST['orderId'];
		}else{
			$info = $_POST['info'];
			$orderId = $_POST['orderId'];
			$itemId = $_POST['itemId'];
			if(strlen($info)<1){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '未填写评价内容';
				echo json_encode($jsonArr);
				exit;
			} 

		}
		$item_commentMd = M("item_comment");
		$item_orderMd = M("item_order");
		$order_detailMd = M("order_detail");
		$user_mod = M("user");
		$uid = $this->visitor->info['id'];
		
		$path = "E:/xyy/";
		$time = time();
		$img1 = "";
		$picFile1 = $_FILES["picurl1"];
		if($picFile1["error"] === 4) {
			$picurl1 = "";
		}else {
			$picurl1 = "data/upload/bbs/".$time.$uid."_picurl1.jpg";
			$exist = file_exists($picurl1);
			if($exist) {
				unlink($picurl1);
			}
			move_uploaded_file($picFile1["tmp_name"],$path.$picurl1);
		}
		$picurl2 = "";
		$picFile2 = $_FILES["picurl2"];
		if($picFile2["error"] === 4) {
			$picurl2 = "";
		}else {
			$picurl2 = "data/upload/bbs/".$time.$uid."_picurl2.jpg";
			$exist = file_exists($picurl2);
			if($exist) {
				unlink($picurl2);
			}
			move_uploaded_file($picFile2["tmp_name"],$path.$picurl2);
		}
		$picurl3 = "";
		$picFile3 = $_FILES["picurl3"];
		if($picFile3["error"] === 4) {
			$picurl3 = "";
		}else {
			$picurl3 = "data/upload/bbs/".$time.$uid."_picurl3.jpg";
			$exist = file_exists($picurl3);
			if($exist) {
				unlink($img3);
			}
			move_uploaded_file($picFile3["tmp_name"],$path.$picurl3);
		}
		
		
		$data['picurl1']=$picurl1;
		$data['picurl2']=$picurl2;
		$data['picurl3']=$picurl3;
		$data["uid"] = $uid;
		$data["info"] = $info;
		$data["orderId"] = $orderId;
		$data["uname"] = $user_mod->where(array('id'=>$this->visitor->info['id']))->getField('username');
		$data["add_time"] = $time;
		
		
		$data['item_id'] = $itemId;
		$item_commentMd->add($data);
		
		
		//评论都是5积分
		$order_info = $item_orderMd->where(array('orderId'=>$orderId))->field('shopid,userId')->find();
		$points = 5;
		if($order_info['userId'] == $order_info['shopid']){
			$message = D('messagelist'); 
			$message->user_id =$uid; //用户id
			$message->recom = $uid; //用户id
			$message->type = 6; //评论积分
			$message->order_id = $orderId; //订单id
			$message->time = time(); 
			$message->status = 0; // 默认未读状态
			$message->points = $points;
			if($message->add()){
				$user_mod->where(array('id'=>$uid))->setInc('points',$points);
			}
		}else{
			//消费者积分
			$message = D('messagelist'); 
			$message->user_id =$uid; //用户id
			$message->recom = $uid; //用户id
			$message->type = 6; //评论积分
			$message->order_id = $orderId; //订单id
			$message->time = time(); 
			$message->status = 0; // 默认未读状态
			$message->points = $points;
			if($message->add()){
				$user_mod->where(array('id'=>$uid))->setInc('points',$points);
			}
			$comment_msg = D('messagelist'); 
			$comment_msg->user_id =$uid; //商家id
			$comment_msg->recom = $order_info['shopid']; //商家id
			$comment_msg->type = 6; //评论积分
			$comment_msg->order_id = $orderId; //订单id
			$comment_msg->time = time(); 
			$comment_msg->status = 0; // 默认未读状态
			$comment_msg->points = $points;
			if($comment_msg->add()){
				$user_mod->where(array('id'=>$order_info['shopid']))->setInc('points',$points);
			}
		}
		
		//评论之后的状态
		if($order_detailMd->where(array("orderId"=>$orderId,'itemId'=>$itemId))->save(array('c_status'=>1))){
			if($order_detailMd->where(array("orderId"=>$orderId,"c_status"=>0))->count()==0){
				$item_orderMd->where(array("orderId"=>$orderId))->save(array('c_status'=>1));
			}
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}	
	/***** 确认收货 *****/
	public function confirm_order(){
	
		$orderId = $this->_get('orderId','trim');
	    $item_order = M('item_order');
	    $item = M('item');
		 
	    $item_orders = $item_order->field("a.*,b.id,b.uid,b.shares,b.recom,b.username,b.wechatid")
								  ->join('a left join __USER__ as b on a.shopid = b.id')
								  ->where("a.orderId = '".$orderId."' and a.userId = ".$this->visitor->info['id']." and a.status = 3")
								  ->find();
	    if(!$item_orders){
	     	$jsonArr['status'] = 0;
	     	$jsonArr['msg'] = '订单不存在';
			echo json_encode($jsonArr);
			exit;
	    }
	     $data['status']	= 4;//状态改为已完成
		 
	     $where['orderId']  = $orderId;
	     $where['status']	= 3;
	     // 实例化模版信息类            
         $wxsend   = new Wxsend();    
	     if($item_order->where($where)->save($data) !== false)
	     {	
	     	//获取订单信息和商品信息                
            $order_detail = M('order_detail')->where("orderId = '".$orderId."'")
                                             ->select();	
							 
	     	// 获取分成率				 
	     	$lv = M("user_fengchenglv")->where(array('id'=>3))->field('royalty,status')->find();	
			
			foreach ($order_detail as $k => $val) {
                $profit += $val['profit']; //获取订单利润
                $orderPrice += $val['price']; //获取订单金额
				// 减掉对应商品的库存数量
				$item->where('id='.$val['itemId'])->setInc('buy_num',$val['quantity']);
				$shares['id']= $item_orders['shares'];
            }
		
			
			$shop_shares = $this->getShareTree($item_orders['shopid']);//获取获得提成的用户ID
			foreach($shop_shares['uid'] as $sk=>$sv){
				$lvId .=$sv;
			}
			$roy = M('user_fengchenglv')->where(array('id'=>$lvId))->field('royalty')->find();//获取各级别的分成率
			$royArr = explode('|',$roy['royalty']);
			
			$time = date("Y-m-d H:i:s");
			$message = M('messagelist');
			$fclist = M("user_fengchengllist");
			foreach($royArr as $rk=>$rv){
				//店铺分成
				$fcdata['price']= round($orderPrice,2); //订单总金额
				$fcdata['user_id'] = $this->visitor->info['id'];
				$fcdata['add_time']= time();
				$fcdata['state'] 	 = 1;
				$fcdata['orderId'] = $item_orders['orderId'];
				
				$fcdata['royalty'] = $rv;
				$fcdata['fencheng']= round($profit*$rv,2);
				$fcdata['uid'] = $shop_shares['shareId'][$rk];
				$res = $fclist->add($fcdata);
				//将发送的消息写进后台
				$message->user_id =$order['userId'];
				$message->recom = $shop_shares['shopId'][$rk];
				$message->type = 3;
				$message->order_id = $order['orderId'];
				$message->time = time();
				$message->status = 0;
				$message->content = "
					尊敬的{$shop_shares['username'][$rk]}您好！您有一笔新的收入提醒：<br/>
					收入类型：店铺零售奖金<br/>
					收入金额：".round($profit*$rv,2)."<br/>
					到账时间：{$time}<br/>
					如有疑问请在公众号内咨询在线客服！
				";
				$message->add();
				$wxsend->SR($shop_shares['wechatid'][$rk],round($profit*$rv,2),$time);//提示代理商获得返利
				//$wxsend->SR('oOejpwiK88gEkMvMHJUxe5JhN6lE',round($profit*$rv,2).' '.$shop_shares['wechatid'][$rk],$time); //测试样例
			}
			$jsonArr['status'] = 1;
	    }else 
	    {
	     	$jsonArr['status'] = 0;
	    }
		echo json_encode($jsonArr);
	}
	public function getShareTree($shopid){
		//售出商品的店铺
		//$shopid = $this->_get('shopid','trim');
		$user_mod = M('user');
		$user =  $user_mod->where(array('id'=>$shopid))->field('uid,username,wechatid,shares_tree')->find();
		$uid = $user['uid'];
		
		$arr = array();
		$arr['shareId'][] = $shopid;
		$arr['uid'][] = $uid;
		$arr['username'][] = $user['username'];
		$arr['wechatid'][] = $user['wechatid'];
		//由低等级往上,找出5,3,2等级的店铺各一家
		while($uid>2){
			$sharesArr = explode('|',$user['shares_tree']);
			$num = count($sharesArr);
			if($num<3){
				return $arr;
				//echo json_encode($arr);exit;
			}
			$user = $user_mod->where(array('id'=>$sharesArr[$num-2]))->field('uid,username,wechatid,shares_tree')->find();
			while($user['uid']>=$uid){ //需要跳过相同等级的店铺
				$sharesArr = explode('|',$user['shares_tree']);
				$num = count($sharesArr);
				if($num<3){
					return $arr;
					//echo json_encode($arr);exit;
				}
				$user = $user_mod->where(array('id'=>$sharesArr[$num-2]))->field('uid,username,wechatid,shares_tree')->find();
			}
			$arr['shareId'][] = $sharesArr[$num-2];
			$arr['uid'][] = $user['uid'];
			$arr['username'][] = $user['username'];
			$arr['wechatid'][] = $user['wechatid'];
			$uid = $user['uid'];
		}
		//再找出两家等级为2的店铺
		for($i=0;$i<2;$i++){
			$sharesArr = explode('|',$user['shares_tree']);
			$num = count($sharesArr);
			if($num<3){
				return $arr;
				//echo json_encode($arr);exit;
			}
			$user =  $user_mod->where(array('id'=>$sharesArr[$num-2]))->field('uid,username,wechatid,shares_tree')->find();
			while($user['uid']!=2){  
				$sharesArr = explode('|',$user['shares_tree']);
				$num = count($sharesArr);
				if($num<3){
					return $arr;
					//echo json_encode($arr);exit;
				}
				$user = $user_mod->where(array('id'=>$sharesArr[$num-2]))->field('uid,username,wechatid,shares_tree')->find();
			}			
			$arr['shareId'][] = $sharesArr[$num-2];
			$arr['uid'][] = $user['uid'];
			$arr['username'][] = $user['username'];
			$arr['wechatid'][] = $user['wechatid'];
		}
		return $arr;
		//echo json_encode($arr);
	}	
	/***** 申请退款 *****/
	public function refund(){
	
		$orderId = $this->_get('orderId','trim');
		$res=M('item_order')->where(array('orderId'=>$orderId))->field('orderId,order_sumPrice')->find();
		if($res){
			$jsonArr['oderInfo'] = $res;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 提交申请 *****/
	public function do_refund(){
		if($_REQUEST['fg']=='Android'){
			$yuanyin = $_REQUEST['yuanyin'];
			if(empty($yuanyin)){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '退款原因还未填写';
				echo json_encode($jsonArr);
				exit;
			}
			$data['yuanyin'] = $yuanyin;
			$data['orderId'] = $_REQUEST['orderId'];
			$data['refund'] = $_REQUEST['refund'];
			$data['express'] = $_REQUEST['express'];
			$data['re_orderId'] = $_REQUEST['re_orderId'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			
			$yuanyin = $params['yuanyin'];
			if(empty($yuanyin)){
				$jsonArr['status'] = 0;
				$jsonArr['msg'] = '退款原因还未填写';
				echo json_encode($jsonArr);
				exit;
			}
			$data['yuanyin'] = $yuanyin;
			$data['orderId'] = $params['orderId'];
			$data['refund'] = $params['refund'];
			$data['express'] = $params['express'];
			$data['re_orderId'] = $params['re_orderId'];
		}
		$ret = M('item_ordertk')->where(array("orderId"=>$orderId))->count();
		if($ret>0){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '您已提交过申请';
			echo json_encode($jsonArr);
			exit;
		}
		
		$user = M('user')->where(array('id'=>$this->visitor->info['id']))->field('id,username,kaihuhang,huming,zhanghao')->find();
		$data['add_time'] = time();
		$data['uid'] = $user['id'];
		$data['uname'] = $user['username'];
		$data['kaihuhang'] = $user['kaihuhang'];
		$data['huming'] = $user['huming'];
		$data['zhanghao'] = $user['zhanghao'];
		if(M('item_ordertk')->add($data)){
			$item_order = M('item_order');
			$item_order->where(array("orderId"=>$orderId))->save(array("status"=>6));
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 用户信息 *****/
	public function user_info(){
		$user = M('user')->where(array('id'=>$this->visitor->info['id']))
		                 ->field('username,merchant,m_desc,phone_mob,wechatid,email,gender,province,city,cover,reg_time,last_time,points,hyimg')
						 ->find();
		if($user){
			if($user['hyimg']!=''){
				$user['cover'] = ApiConfig::SERVER_PATH.$user['hyimg'];
				//$user['cover'] = "http://api.yooopay.com/yf".$user['hyimg'];
			}
			unset($user['hyimg']);
			$jsonArr['status'] = 1;
			$jsonArr['user'] = $user;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 历史店铺 *****/
	public function historys_shops(){
        $List = M('user')->join("a left join weixin_seller as b on a.id = b.shopid")
							   ->field("a.id,a.merchant,a.m_desc,a.hyimg,a.cover")
							   ->where(array('b.userid'=>$this->visitor->info['id'],'a.id'=>array('neq',$this->visitor->info['id'])))
							   ->order('b.addtime desc')
							   ->limit('5')
							   ->select();
	    if(count($List)>0){
			$shopList = $List;
		}else{
			$shopList = M('user')->field("id,merchant,m_desc,hyimg,cover")->where(array('id'=>76))->select();
		}
		$uid = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('uid');
		if($uid<4){
			$shop = M('user')->field("id,merchant,m_desc,hyimg,cover")
				   ->where(array('id'=>$this->visitor->info['id']))
				   ->select();
			$shopList = array_merge($shop,$shopList);
		}					   
		if(count($shopList)>0){
			foreach($shopList as $key=>$val){
				if($val['hyimg']!=''){
					$shopList[$key]['cover'] = ApiConfig::SERVER_PATH.$val['hyimg'];
					//$user['cover'] = "http://api.yooopay.com/yf".$user['hyimg'];
				}
				unset($shopList[$key]['hyimg']);
			}
			$jsonArr['shops'] = $shopList;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	
	
	/***** 搜索店铺 *****/
	public function search_shop(){
		$keyword = $this->_get('keyword','trim');
		if(strlen($keyword)>0){
			$where['id|merchant|m_desc'] = array('like','%'.$keyword.'%');
		}else{
			$where['id'] = 76;
		}
		$where['uid'] = array('in','2,3');
		
       
		$shopList = M('user')->field("id,merchant,m_desc,hyimg,cover")->where($where)->select();
		
							   
		if(count($shopList)>0){
			foreach($shopList as $key=>$val){
				if($val['hyimg']!=''){
					$shopList[$key]['cover'] = ApiConfig::SERVER_PATH.$val['hyimg'];
					//$user['cover'] = "http://api.yooopay.com/ym".$user['hyimg'];
				}
				unset($shopList[$key]['hyimg']);
			}
			$jsonArr['shops'] = $shopList;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	/***** 删除店铺 *****/
    public function del_shop(){
        $shopid = $this->_get('shopid','intval');
        $where['shopid'] = $shopid;
        $where['userid'] = $this->visitor->info['id'];
        if(M('seller')->where($where)->delete()){
        	$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	//***** 清空消息记录 *****/
	public function clear_msg(){
		//4=确认收货 3=待结算 2=新进代理 1=发货通知
		$where['type'] = $this->_get('type','trim');
		$where['recom'] = $this->visitor->info['id'];
		if(M('messagelist')->where($where)->delete()){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//***** 临时用户修改登录名和密码 *****/
	public function edit_userinfo(){
		if($_REQUEST['fg']=='Android'){
			$params['username'] = $_REQUEST['username'];
			$params['pwd'] = $_REQUEST['pwd'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
		}
		$username = $params['username'];
		if(M('user')->where(array('username'=>$username))->find()){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '该昵称已存在';
			echo json_encode($jsonArr);
			exit;
		}
		if($username==''){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '昵称不能为空';
			echo json_encode($jsonArr);
			exit;
		}
		if($params['pwd']==''){
			$jsonArr['status'] = 0;
			$jsonArr['msg'] = '密码不能为空';
			echo json_encode($jsonArr);
			exit;
		}
		$data['username'] = $username;
		$data['nickname'] = $username;
		$data['pwd'] = md5($params['pwd']);
		$where['id'] = $this->visitor->info['id'];
		if(M('user')->where($where)->save($data)){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		} 
		echo json_encode($jsonArr);
	}
	
	//***** 临时用户判断 *****/
	public function user_kind(){
		$pwd = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('pwd');
		if(strlen($pwd)>0){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	//***** 临时用户数据迁移 *****/
	public function merge_data(){
		$username = $this->_get('username');
		$id = M('user')->where(array('username'=>$username))->getField('id');
		M('item_order')->where(array('userId'=>$id))->save(array('userId'=>$this->visitor->info['id']));
		M('item_ordertk')->where(array('uid'=>$id))->save(array('uid'=>$this->visitor->info['id']));
		M('cart')->where(array('uid'=>$id))->save(array('uid'=>$this->visitor->info['id']));
		M('idcard')->where(array('uid'=>$id))->save(array('uid'=>$this->visitor->info['id']));
		M('messagelist')->where(array('recom'=>$id))->save(array('recom'=>$this->visitor->info['id']));
		M('posslike')->where(array('userid'=>$id))->save(array('userid'=>$this->visitor->info['id']));
		M('user_address')->where(array('uid'=>$id))->save(array('uid'=>$this->visitor->info['id']));
		M('item_comment')->where(array('uid'=>$id))->save(array('uid'=>$this->visitor->info['id']));
		M('user_coupon')->where(array('userId'=>$id))->save(array('userId'=>$this->visitor->info['id']));
		M('item_like')->where(array('uid'=>$id))->save(array('uid'=>$this->visitor->info['id']));
		if(M('user')->delete($id)){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
}