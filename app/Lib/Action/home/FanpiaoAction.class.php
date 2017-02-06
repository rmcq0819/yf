<?php
include_once("class.apiconfig.php");
class FanpiaoAction extends frontendAction {
	/***** 商品列表 ****/
	public function items(){
		$itemMd = M('item');
		$where['is_fping'] = 1;
		$where['status'] = 1;
		$list = $itemMd->where($where)->order('ordid asc')->field('id,img,title,price,fping_price,fping_num,goods_stock')->select();
		if(count($list)>0){
			foreach($list as $key=>$val){
				$list[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['items'] = $list;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		
		echo json_encode($jsonArr);
	}
	
	/***** 虚拟商品--范票 ****/
	public function vitems(){
		$item = M('item');
		$vitem = $item->where('is_fictitious = 1 and status = 1')
						  ->field("id,img,price,buy_num,title")
						  ->order('ordid asc')
						  ->select();
		if(count($vitem)>0){
			foreach($vitem as $key=>$val){
				$vitem[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['vItems'] = $vitem;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}

	/***** 历史兑换记录 ****/
	public function fanpiao_history(){
       $uid = $this->visitor->info['id'];
		$fanpiao_record = M('fpingshop')
					  ->join("a left join weixin_item as b on a.item_id = b.id")
					  ->where(array('a.user_id'=>$uid))
					  ->field('a.fping_num,a.fping_price,a.add_time,b.img,b.title')
					  ->order('a.id desc')
					  ->select();
		if(count($fanpiao_record)>0){
			foreach($fanpiao_record as $key=>$val){
				$fanpiao_record[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['record'] = $fanpiao_record;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	
	public function counts(){
		$point_yuer = $this->visitor->info['points']; 
		$fanpiao_count = M('fpingshop')->where(array('user_id'=>$this->visitor->info['id']))->count();
		$jsonArr['points'] = $point_yuer;
		$jsonArr['counts'] = $fanpiao_count;
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	public function fping_pay(){
		$shopid = $this->_get('shopid','trim');
		$shop = M('user')->where(array('id'=>$shopid))->field('uid,login_days,v1')->find();
		if($shop['uid']==4||$shop['v1']!=1||$shop['login_days']<7){
			echo json_encode(array('status'=>0,'msg'=>'您访问的店铺未达到等级，不能兑换！'));
			exit;
		}
		$id = $this->_get('id','trim');
		$fping_num = $this->_get('fping_num','trim');
		$fping_price = $this->_get('fping_price','trim');
		$item_order=M('item_order');
		$order_detail=M('order_detail');
		$items = M('item');
		$uid = $this->visitor->info['id'];
		$point_yuer = $this->visitor->info['points'];
		$uname = $this->visitor->info['username'];
		//获取商品信息
		$item_detail = $items->where(array('id'=>$id))->find();
		
		if($point_yuer < $fping_num){
			echo json_encode(array('status'=>0,'msg'=>'您的范票余额不足，去购买范票再来兑换吧！'));
			exit;
		}else{
			$del_point =  M('user')->where(array('id'=>$uid))->setDec('points',$fping_num);
			if($del_point){
				$data['user_id'] = $uid;
				$data['item_id'] = $id;
				$data['fping_num'] = $fping_num;
				$data['fping_price'] = $fping_price;
				$data['add_time'] = time();
				$save = M('fpingshop')->add($data);
				if($save){
					$yunfei = 10; // 运费
					$dingdanhao = date("Y-m-dH-i-s");
					$dingdanhao = str_replace("-","",$dingdanhao);
					$dingdanhao .= rand(1000,2000);
					$times = time();//订单添加时间
					//获取地址信息
					$address_info = M('user_address')->where(array('uid'=>$uid))->find();
					if($item_detail['price'] < 99){
					   $order_data['yunfei'] = $yunfei;
					   $order_data['order_sumPrice'] = $fping_price+$yunfei; //订单总额+运费
					}else{
					   $order_data['order_sumPrice'] = $fping_price; // 订单总额
					}
					$order_data['goods_sumPrice'] = $fping_price; //商品总额
					$order_data['freetype']= 0; 
					$order_data['freeprice']= 0;
					$order_data['userId'] = $uid; //用户ID
					$order_data['userName'] = $uname; //用户名
					$order_data['shopid'] = $shopid; //店铺ID
					$order_data['orderId']= $dingdanhao.'-04'; //兑换商品订单号
					$order_data['add_time']= $times; //订单时间
					$order_data['status'] = 1; //订单状态
					$order_data['baseid'] = $item_detail['baseid']; //免税仓ID
					$order_data['is_fping'] = 1; //是否为兑换商品
					$order_data['address_name'] = $address_info['consignee']; //收货人姓名
					$order_data['mobile'] = $address_info['mobile']; //收货人手机号码
					$order_data['address'] = $address_info['sheng'].$address_info['shi'].$address_info['qu'].$address_info['address']; //收货地址
					$order_data['address_id'] = $address_info['id'];
					$order_save = $item_order->data($order_data)->add();
				    if($order_save){
						$detail_data['orderId'] = $dingdanhao.'-04'; //兑换商品订单号
						$detail_data['itemId'] = $item_detail['id']; //商品id
						$detail_data['title'] = $item_detail['title']; //商品名称
						$detail_data['img'] = $item_detail['img']; //商品图片
						$detail_data['price'] = $fping_price; //商品价格
						$detail_data['sigsumprice'] = $fping_price; //商品同类型价格
						$detail_data['quantity'] = 1; //商品兑换数量
						$detail_data['itemtype'] = $item_detail['itemtype']; //商品类型，完税/保税
						$detail_data['profit'] = $fping_price; //商品利润 => 范票商城以购买价格作为商品利润
						$detail_data['shopid'] = $shopid; //店铺ID
						$detail_data['status'] = 0; //订单状态
						$detail_data['baseid'] = $item_detail['baseid']; //免税仓ID
						$detail_data['add_time'] = $times; //订单添加时间
						$detail_data['fencheng'] = 0.40; //分成率
						$detail_save = $order_detail->data($detail_data)->add();
						if($detail_save){
							$orderId = $order_detail->where(array('id'=>$order_detail->getLastInsID()))->getField('orderId');
							$this->msg_add($uid,$uid,26,0,time(),0,$fping_num);
							echo json_encode(array('status'=>1,'orderId'=>$orderId));
							exit;
						}
				    }else{
					   echo json_encode(array('status'=>0,'msg'=>'兑换失败！'));
					   exit;
				    }
				}else{
					echo json_encode(array('status'=>0,'msg'=>'兑换失败！'));
					exit;
				}
			}else{
				echo json_encode(array('status'=>0,'msg'=>'兑换失败！'));
				exit;
			}
		}
	}
	
	
	public function msg_add($user_id,$recom,$type,$order_id,$time,$status,$points){
		$data['user_id'] = $user_id; 
		$data['recom'] = $recom; 
		$data['type'] = $type; 
		$data['order_id'] = $order_id;
		$data['time'] = $time;
		$data['status'] = $status;
		$data['points'] = $points;
		M('messagelist')->add($data);
	}
}