<?php
class payendAction extends Action {
		
	public function getShareTree($shopid){
		$user_mod = M('user');
		//售出商品的店铺
		//$shopid = $this->_get('shopid','trim');
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
	
	
	/***** 支付成功 *****/
	public function pay_end(){
		$orderId = $this->_get('orderId','trim');
		
		//获取订单和店铺代理信息
		$order = M('item_order')->join("a left join weixin_user as b on a.shopid = b.id")
								->where(array('a.orderId'=>array('like',$orderId.'%'),'a.status'=>2))
								->field("a.userId,a.shopid,a.orderId,b.wechatid,b.username")
								->find();

		//获取订单信息和商品信息
		$order_detail = M('order_detail')->where(array('orderId'=>array('like',$orderId.'%')))->field('itemId,profit,fencheng,size,shopid')->select();
		
		if(!$order){
			$jsonArr['status'] = 0;
			echo json_encode($jsonArr);
			exit;
		}
		//购物之后给商家和消费者+10积分
		$points = 10;
		if($order['userId'] == $order['shopid']){
			$message = D('messagelist');
			$message->user_id = $order['userId'];      //用户ID
			$message->recom = $order['shopid'];       //商家ID
			$message->type = 5;           //购物积分
			$message->order_id = $order['orderId'];   //订单ID
			$message->time = time();
			$message->status = 0;         // 默认未读状态
			$message->points = $points;
			if($message->add()){
				M('user')->where(array('id'=>$order['shopid']))->setInc('points',$points);
			}
		}else{
			//商家积分
			$message = D('messagelist');
			$message->user_id = $order['userId'];      //用户ID
			$message->recom = $order['shopid'];       //商家ID
			$message->type = 5;           //购物积分
			$message->order_id = $order['orderId'];   //订单ID
			$message->time = time();
			$message->status = 0;         // 默认未读状态
			$message->points = $points;
			if($message->add()){
				M('user')->where(array('id'=>$order['shopid']))->setInc('points',$points);
			}
			//消费者积分
			$item_msg = D('messagelist');
			$item_msg->user_id = $order['userId'];     //用户ID
			$item_msg->recom = $order['userId'];      //用户ID
			$item_msg->type = 5;          //购物积分
			$item_msg->order_id = $order['orderId'];  //订单ID
			$item_msg->time = time();
			$item_msg->status = 0;        // 默认未读状态
			$item_msg->points = $points;
			if($item_msg->add()){
				M('user')->where(array('id'=>$order['userId']))->setInc('points',$points);
			}
		}
		
		
		$where_c['uid'] = $order['userId'];        //会员ID
		foreach($order_detail as $key=>$val){
			//订单支付后删除购物车相应商品
			$where_c['id'] = $val['itemId'];     //商品ID
			if(strlen($val['size'])>0){
				$where_c['size'] = $val['size'];    
			}
			$where_c['shopid'] = $val['shopid']; //店铺ID
			M('cart')->where($where_c)->delete();
			
			//记录实际售出数量
			M('item')->where(array('id'=>$val['itemId']))->setInc('paynum',1);
			
			//获取订单所有商品利润返利总和
			$profit += $val['profit'];
		}
		
		
		$shop_shares = $this->getShareTree($order['shopid']);//获取获得提成的用户ID
		foreach($shop_shares['uid'] as $sk=>$sv){
			$lvId .=$sv;
		}
		$roy = M('user_fengchenglv')->where(array('id'=>$lvId))->field('royalty')->find();//获取各级别的分成率
		$royArr = explode('|',$roy['royalty']);
		
		
		$time = date("Y-m-d H:i:s");
		$message = M('messagelist');
		$wxsend = new Wxsend();
		foreach($royArr as $rk=>$rv){
			$message->user_id =$order['userId'];
			$message->recom = $shop_shares['shopId'][$rk];
			$message->type = 3;
			$message->order_id = $order['orderId'];
			$message->time = time();
			$message->status = 0;
			$message->content = "
				尊敬的{$shop_shares['username'][$rk]}您好！您有一笔新的收入提醒：<br/>
				收入类型：店铺零售奖金<br/>
				收入金额：".round($profit*$rv,2)."(待结算)<br/>
				到账时间：{$time}<br/>
				如有疑问请在公众号内咨询在线客服！
			";
			$message->add();
			$wxsend->SR($shop_shares['wechatid'][$rk],round($profit*$rv,2)."(待结算)",$time);//提示代理商将获得返利
			//$wxsend->SR('oOejpwiK88gEkMvMHJUxe5JhN6lE',round($profit*$rv,2)."(待结算)".' '.$shop_shares['wechatid'][$rk],$time); //测试样例
		}
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	/***** 实体店支付成功 *****/
	public function payend(){
		$orderId = $this->_get('orderId','trim');
		$shopId = $this->_get('shopId','trim');
		$profit = $this->_get('profit','trim');
		$payTime = $this->_get('payTime','trim');
		$fcprofit = round($profit*0.4,2);
		$user = M('user')->where(array('id'=>$shopId))->field("id,wechatid,username")->find();
		
		//购物之后给商家+10积分
		$points = 10;
		
		//商家积分
		$message = D('messagelist');
		$message->user_id = '';      //用户ID
		$message->recom = $shopId;       //商家ID
		$message->type = 10;           //购物积分
		$message->order_id = $orderId;   //订单ID
		$message->time = $payTime;
		$message->status = 0;         // 默认未读状态
		$message->points = $points;
		if($message->add()){
			M('user')->where(array('id'=>$shopId))->setInc('points',$points);
		}
	
		$time = date("Y-m-d H:i:s");
		$message->user_id ='';
		$message->recom = $shopId;
		$message->type = 3;
		$message->order_id = $orderId;
		$message->time = $payTime;
		$message->status = 0;
		$message->content = "
			尊敬的{$user['username']}您好！您有一笔新的收入提醒：<br/>
			收入类型：店铺零售奖金<br/>
			收入金额：".$fcprofit."(实体店)<br/>
			到账时间：{$payTime}<br/>
			如有疑问请在公众号内咨询在线客服！
		";
		
		//实例化模版信息类		
		$wxsend = new Wxsend();
		if($message->add()){
			//$wxsend->SR($user['wechatid'],$fcprofit,$time);//提示代理商将获得返利
			$wxsend->SR('oOejpwiK88gEkMvMHJUxe5JhN6lE',$fcprofit."(实体店)".' '.$user['wechatid'],$time); //测试样例
		}
	 
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
}