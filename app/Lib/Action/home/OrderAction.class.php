<?php
include_once("class.apiconfig.php");
class OrderAction extends frontendAction {

	public function payconfig(){
		$wx['appid'] = ApiConfig::APPID;
		$wx['mch_id'] = ApiConfig::MCHID;
		$wx['notify_url'] = ApiConfig::NOTIFY_URL;//通知地址
		$wx['key'] = ApiConfig::KEY;
		$wx['trade_type'] = ApiConfig::TRADE_TYPE;
		return $wx;
	}
	
	
	//统一支付
	public function dopay($orderId,$total_fee){
		$out_trade_no = $orderId;
		$body = $orderId;
		$wx = $this->payconfig();
		$wechatAppPay = new wechatAppPay($wx['appid'],$wx['mch_id'],$wx['notify_url'],$wx['key']);
		$params['body'] = $body;                               //商品描述
		$params['out_trade_no'] = $out_trade_no;               //自定义的订单号
		$params['total_fee'] = $total_fee*100;                 //订单金额 只能为整数 单位为分
		$params['trade_type'] = $wx['trade_type'];             //交易类型 JSAPI | NATIVE | APP | WAP 
		$result = $wechatAppPay->unifiedOrder( $params );
		//print_r($result);
	    //result中就是返回的各种信息信息，成功的情况下也包含很重要的prepay_id
		//创建APP端预支付参数
		$data = @$wechatAppPay->getAppPayParams( $result['prepay_id'] );
		// 根据上行取得的支付参数请求支付即可
		echo json_encode($data);
	}
	
	
	//结算界面-获取用户所有收货地址
	public function all_adddress(){
		//获取全部的收货地址
		$addrList = M('user_address')->where(array('uid'=>$this->visitor->info['id']))->order("is_default desc")->field('id,consignee,is_default,sheng,shi,qu,address,mobile')->select();
		if(count($addrList)>0){
			$jsonArr['addrs'] = $addrList;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	//结算界面-用户某一地址信息的详细内容
	public function addr_info(){
		$id = $this->_get('addrId','intval');
		$addr = M('user_address')->where(array('id'=>$id))->field('id,consignee,address,mobile,is_default,sheng,shi,qu')->find();
		if($addr){
			$jsonArr['addr'] = $addr;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	//结算界面-默认显示地址
	public function def_addr(){
		//获取全部的收货地址
		$addr = M('user_address')->where(array('uid'=>$this->visitor->info['id']))->order("is_default desc")->field('id,consignee,is_default,address,mobile')->find();
		if($addr){
			$jsonArr['addr'] = $addr;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//结算界面-删除地址
	public function del_addr(){
		$id = $this->_get('addrId','intval');
		if(M('user_address')->where('id='.$id)->delete()){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;	
		}
		echo json_encode($jsonArr);
	}
	
	//结算界面-保存地址修改
	public function edit_addr(){
		if($_REQUEST['fg']=='Android'){
			$id = $_REQUEST['id'];
			$data['consignee'] = $_REQUEST['consignee'];
			$data['address'] = $_REQUEST['address'];
			$data['mobile'] = $_REQUEST['mobile'];
			$data['sheng'] = $_REQUEST['sheng'];
			$data['shi'] = $_REQUEST['shi'];
			$data['qu'] = $_REQUEST['qu'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			$id = $params['addrId'];
			$data['consignee'] = $params['consignee'];
			$data['address'] = $params['address'];
			$data['mobile'] = $params['mobile'];
			$data['sheng'] = $params['sheng'];
			$data['shi'] = $params['shi'];
			$data['qu'] = $params['qu'];
		}
		M('user_address')->where(array('id'=>$id))->save($data);
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	
	//结算界面-所有身份证信息
	public function IDcards(){
		$cards = M('idcard')->where('uid = '.$this->visitor->info['id'])->field('Id,c_id,c_name')->select();
		if(count($cards)>0){
			$jsonArr['cards'] = $cards;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//结算界面-用户选择的购物车记录
	public function itemtobuy(){
		if($_GET['mainIds']!=''){
			$mainIds = $_GET['mainIds'];
			$idArr =  explode(',',$mainIds);
		}else if($_GET['goodId']!=''&&$_GET['quantity']!=''&&$_GET['shopid']!=''){
		
			$goodId = $this->_get('goodId', 'intval');//商品ID
			$size = $_GET['size'];//规格
			$quantity = $this->_get('quantity', 'intval');//购买数量
			$data['num']=$quantity;
			$data['shopid']= $this->_get('shopid');//店铺ID
			$data['id']= $goodId;
			$item=M('item')->field('goods_stock,cost,title,itemtype,baseid,img,yhprice,size,price')->where('id='.$goodId)->find();
			if($item){
				if($item['size']&&empty($size)){
					$jsonArr=array('status'=>0,'msg'=>'请选择规格');
					echo json_encode($jsonArr);
					exit;
				}else if(!empty($size)){
					
					$prices=explode('|',$item['yhprice']);
					$goods_stocks=explode('|',$item['goods_stock']);
					$sizes=explode('|',$item['size']);
					$costs=explode('|',$item['cost']);
				
					foreach($sizes as $key=>$val){
						if($val == $size){
							$cost = $costs[$key];//相应规格的成本价
							$price= $prices[$key];//商品价格
							$stock= $goods_stocks[$key];//商品价格
							break;
						}
					}
					$data['size']=$size;
				}else{
					$cost = $item['cost'];//相应规格的成本价
					$price= $item['price'];//商品价格
					$stock= $item['goods_stock'];//商品价格
				}
				$data['cost'] = $cost;//相应规格的成本价
				$data['price']= $price;//商品价格
				$data['name']= $item['title'];//商品标题
				$data['img']= $item['img'];//商品图片
				$data['itemtype']= $item['itemtype'];//商品类型
				$data['baseid']= $item['baseid'];//免税仓id
				$data['uid']= $this->visitor->info['id'];//用户ID
				$data['show']= 1;
				if($stock<$quantity){
					$jsonArr['status']=0;
					$jsonArr['msg'] = '没有足够的库存';
					echo json_encode($jsonArr);
					exit;
				}else{
					$cartId = M('cart')->add($data);
					$idArr =  explode(',',$cartId);
				}
			}else{
				$jsonArr['status']=0;
				$jsonArr['msg'] = '该商品不存在';
				echo json_encode($jsonArr);
				exit;
			}
		}else{
			$jsonArr['status']=0;
			echo json_encode($jsonArr);
			exit;
		}
		if(count($idArr)>0){
			$where['mainid'] = array('in',$idArr);
			$cartItem = M('cart')->where($where)->field('mainid,id,shopid,name,price,itemtype,size,img,num')->select();
			if(count($cartItem)>0){
				$totalPrice = 0;
				foreach($cartItem as $key=>$val){
					$cartItem[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
					$totalPrice += $val['price']*$val['num'];
				}
				$jsonArr['buyitems'] = $cartItem;	
				if($totalPrice<99){
					$jsonArr['yunfei'] = M('setting')->where("name='site_yunfei'")->getField('data');
				}else{
					$jsonArr['yunfei'] = '0';
				}
				$jsonArr['status'] = 1;
			}else{
				$jsonArr['status'] = 0;
			}
		}
		
		echo json_encode($jsonArr);
	}
	
	

	//直接支付（支付状态已确认）
	public function pay_order(){
		//订单页面进入
		$oId = $this->_get('orderid','trim');
		if($oId!=''){
			$dingdanhao = substr($oId,0,18);
			
			$userId=$this->visitor->info['id'];
			$orderInfo=M('item_order')->where(array('orderId'=>array('like',$oId.'%')))->field('shopid,order_sumPrice,goods_sumPrice,address_name,supportmetho,mobile,address')->find();
			if($orderInfo['supportmetho']==3){//微信支付
				$this->dopay($dingdanhao,$orderInfo['order_sumPrice']);
				exit;
			}else{
				$jsonArr['status'] = 0;
			}
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	//确认支付
	public function do_order(){
		$user_address=M('user_address');
		$item_order=M('item_order');
		$order_detail=M('order_detail');
		$item_goods=M('item');
		$cart=M('cart');
		$idcard=M('idcard');
		$user=M('user');
		
		//结算页面进入
		$cardid = $this->_get('cardid','intval');
		$addrid = $this->_get('addrid','intval');
		$shopid = $this->_get('shopid','intval');
		$mainIds = $this->_get('mainIds','trim');
		$idArr =  explode(',',$mainIds);
		
		//订单页面进入
		$oId = $this->_get('orderid','trim');
		if($oId!=''){
			$dingdanhao = substr($oId,0,18);
			
			$userId=$this->visitor->info['id'];
			$orderInfo=$item_order->where(array('orderId'=>array('like',$oId.'%')))->field('shopid,order_sumPrice,goods_sumPrice,address_name,supportmetho,mobile,address')->find();
			if($orderInfo['supportmetho']==3){
				$jsonArr['status'] = 0;
				echo json_encode($jsonArr);
				exit;
			}else{
				$sumPrice = $orderInfo['goods_sumPrice'];
				
				$a_ddr['name']=$orderInfo['address_name'];
				$a_ddr['mobile']=$orderInfo['mobile'];
				$a_ddr['address']=$orderInfo['address'];
				
				$cItems = $order_detail->where(array('orderId'=>array('like',$dingdanhao.'%')))
										   ->field('img,title,itemId,price,quantity as num,size,itemtype')
										   ->select();
				foreach($cItems as $k=>&$e){
					$cItems[$k]['img'] = ApiConfig::ITEM_IMG_PATH.$e['img'];
					$arr[] = $e['itemId'];
					if(is_null($e['size'])){
						$cItems[$k]['size'] = '';
					}
				}
			}
			$shopid = $orderInfo['shopid'];
		}else{
			if(count($idArr)>0){
				$where['mainid'] = array('in',$idArr);
				$cartItem = $cart->where($where)->field('mainId,id,uid,num,price,size,baseid,name,img,itemtype,cost,shopid')->select();
			}
			if(count($cartItem)>0){
				//完税、保税商品的分离组合
				$u1=array();
				$sumPrice = 0;
				foreach($cartItem as $k=>&$e){
					$cItems[$k]['img'] = ApiConfig::ITEM_IMG_PATH.$e['img'];
					$cItems[$k]['title'] = $e['name'];
					$cItems[$k]['price'] = $e['price'];
					$cItems[$k]['num'] = $e['num'];
					$cItems[$k]['itemtype'] = $e['itemtype'];
					$cItems[$k]['size'] = $e['size'];
					$cItems[$k]['itemId'] = $e['id'];
					$name=&$e['itemtype'];
					if(!isset($u1[$name])){
						$u1[$name]=$e;
						unset($u1[$name]['id'],$u1[$name]['name'],$u1[$name]['price'],$u1[$name]['num'],$u1[$name]['img'],$u1[$name]['size'],$u1[$name]['cost'],$u1[$name]['baseid']);
					}
					$u1[$name]['goods'][]=array('id'=>$e['id'],'name'=>$e['name'],'price'=>$e['price'],'num'=>$e['num'],'img'=>$e['img'],'size'=>$e['size'],'cost'=>$e['cost'],'baseid'=>$e['baseid']);
					$sumPrice += round($e['price']*$e['num'],2);
					$arr[] = $e['id'];
				}
				$itemlist = array_values($u1);
				unset($u1);
			  
				foreach($itemlist AS $key=>$val){
					foreach($val['goods'] AS $key1=>$val1){
						if($val['itemtype']==1){
							$sum+=$val1['price']*$val1['num'];
						}
						if($val['itemtype']==0){
							$sum1+=$val1['price']*$val1['num'];
						}
					}
					//统计不同分单的类型的价格
					if($val['itemtype']==1){
						array_push($itemlist[$key],$sum);
					}
					if($val['itemtype']==0){
						array_push($itemlist[$key],$sum1);
					}
				}
			  
				//生成订单号
				$dingdanhao = date("Y-m-dH-i-s");
				$dingdanhao = str_replace("-","",$dingdanhao);
				$dingdanhao .= rand(1000,2000);
			   
				$time=time();//订单添加时间
				
				
				
				$data['freetype']=0;
				$data['order_sumPrice']=$sumPrice;
				$data['add_time']=$time;//添加时间
				$data['goods_sumPrice']=$sumPrice;//商品总额
				$data['userId']=$this->visitor->info['id'];//用户ID
				$data['shopid']=$shopid;//店铺ID
				
				$uInfo = $user->where(array('id'=>$this->visitor->info['id']))->field('username,wechatid')->find();
				if($uInfo['username']){
					$data['userName']=$uInfo['username'];//用户名
				}else{
					$data['userName']=$uInfo['wechatid'];//用微信id做用户名
				}
				
				
				$address= $user_address->where(array('id'=>$addrid))->field('consignee,mobile,sheng,shi,qu,address')->find();//取到地址
				$data['address_name']=$address['consignee'];//收货人姓名
				$data['mobile']=$address['mobile'];//电话号码
				$addr = $address['sheng'].$address['shi'].$address['qu'].$address['address'];//地址
				$data['address']=$addr;//地址
				
				$a_ddr['name']=$address['consignee'];
				$a_ddr['mobile']=$address['mobile'];
				$a_ddr['address']=$addr;
				
				//入库判断是否订单金额小于99元
				if($sumPrice<99){
					//加运费
					$yunfei = M('setting')->where("name='site_yunfei'")->find();
					$data['order_sumPrice'] = $data['order_sumPrice']+$yunfei['data'];	//应付款的
					$data['yunfei'] = $yunfei['data'];
				}
				$data['app'] = '1';
				//分单入库
				foreach($itemlist AS $key=>$val){
					//判断属于那种商品 0:保税 1：完税
					if($val['itemtype']==0){
						$data['orderId']=$dingdanhao.'-01'; //保税
						$orderid = $item_order->data($data)->add();
					}else{
						$data['orderId']=$dingdanhao.'-02'; //完税
						$orderid = $item_order->data($data)->add();
					}
				}
					
				if($orderid){//添加订单成功
					$cds = $idcard->where(array('Id'=>$cardid))->field('c_id,c_name')->find();
					foreach ($itemlist as $key=>$item){
						$orders['sigsumprice'] = "";
						foreach($item['goods'] AS $key3=>$item3){
							$orders['sigsumprice']+=$item3['price'];
						}
						//判断属于那种商品 0:保税 1：完税
						if($item['itemtype']==0){
							$orders['orderId']=$dingdanhao.'-01';	//订单号
							foreach($item['goods'] AS $key1=>$item1){
								//得到分成
								$orders['fencheng'] = M('item')->where(array('id'=>$item1['id']))->getField('fencheng');
								//得到利润
								$orders['profit'] = round(($item1['price']-$item1['cost'])*$item1['num'],2);
								//减掉对应商品的库存数量
								$goods_stock = $item_goods->where(array('id'=>$item1['id']))->field('size,goods_stock,stock,is_stockjointly')->find();
								//获取该商品的库存、规格
								$stock=explode('|',$goods_stock['goods_stock']);
								$stk=explode('|',$goods_stock['stock']);
								$size=explode('|',$goods_stock['size']);
							
								foreach($size as $key2=>$item2){
									if($item2 == $item1['size']){
										$this_stock = $stock[$key2]-$item1['num']*$stk[$key2];
										if($goods_stock['is_stockjointly'] == 1){//共库存商品
											foreach($stock as $ky=>$vl){
												$stock[$ky] = $this_stock;
											}
										}else{
											$stock[$key2] = $this_stock;
										}
									}
									$data5['goods_stock']=implode('|',$stock);
									$item_goods->where(array('id'=>$item1['id']))->save($data5);
									$orders['itemId']=$item1['id'];//商品ID
									$orders['title']=$item1['name'];//商品名称
									$orders['img']=$item1['img'];//商品图片
									$orders['price']=$item1['price'];//商品价格
									$orders['quantity']=$item1['num'];//购买数量
									$orders['size']=$item1['size'];//购买尺寸
									$orders['itemtype']=$item['itemtype'];//商品类型
									$orders['shopid'] = $shopid; //分享过来的商家 ID
									$orders['baseid'] = $item1['baseid'];//免税仓id
									$orders['idcname'] = $cds['c_name'];//身份证姓名
									$orders['idcnum'] = $cds['c_id'];//身份证号码
									$datas1['baseid']=$item1['baseid'];
									M('item_order')->where(array('orderId'=>$dingdanhao.'-01'))->save($datas1);
								}
								$orders['sigsumprice'] = $item[0]; //分单商品总价格
								//分单入库
								$orders['add_time'] = time();
								$order_detail->data($orders)->add();
							}
						}else{
							$orders['orderId']=$dingdanhao.'-02';	//订单号
							foreach($item['goods'] AS $key1=>$item1){
								//得到分成
								$orders['fencheng'] = M('item')->where(array('id'=>$item1['id']))->getField('fencheng');
								//得到利润
								$orders['profit'] = round(($item1['price']-$item1['cost'])*$item1['num'],2);
								
								//减掉对应商品的库存数量
								$goods_stock = $item_goods->where(array('id'=>$item1['id']))->field('size,goods_stock')->find();
								//获取该商品的库存、规格
								$stock=explode('|',$goods_stock['goods_stock']);
								$size=explode('|',$goods_stock['size']);
								foreach($size as $key2=>$item2){
									if($item2 == $item1['size']){
										$i=$key2;
										$stock[$i]=$stock[$i]-$item1['num'];
									}
									$data5['goods_stock']=implode('|',$stock);
									$item_goods->where(array('id'=>$item1['id']))->save($data5);
									$orders['itemId']=$item1['id'];//商品ID
									$orders['title']=$item1['name'];//商品名称
									$orders['img']=$item1['img'];//商品图片
									$orders['price']=$item1['price'];//商品价格
									$orders['quantity']=$item1['num'];//购买数量
									$orders['size']=$item1['size'];//购买尺寸
									$orders['itemtype']=$item['itemtype'];//商品类型
									$orders['shopid'] = $shopid; //分享过来的商家 ID
									$orders['baseid'] = $item1['baseid'];//免税仓id
									$orders['idcname'] = $cds['c_name'];//身份证姓名
									$orders['idcnum'] = $cds['c_id'];//身份证号码
									$datas2['baseid']=$item1['baseid'];
									M('item_order')->where(array('orderId'=>$dingdanhao.'-02'))->save($datas2);
								}
								$orders['sigsumprice'] = $item[0]; //分单商品总价格
								//分单入库
								$orders['add_time'] = time();
								$order_detail->data($orders)->add(); 
							}
						}
					} 
				}else{
					$jsonArr['status'] = 0;//添加订单失败
					echo json_encode($jsonArr);
					exit;
					
				}
			}	
		}

		//用户可用优惠券,未使用且满足使用条件
		//判断订单中是否有活动产品
		$flag = true;
		$cates = '';
		if(count($arr)>0){
			$items = $item_goods->where(array('id'=>array('in',$arr)))->field('cate_id,activity,is_combo')->select();
		}
		foreach($items as $key => $val){
			if($val['activity']==1||$val['is_combo']==1){
				$flag = false;
			}
			$pid = M('item_cate')->where(array('id'=>$val['cate_id']))->getField('pid');
			$cates .= "and b.cate_ids like '%".$pid."%' ";  
		}
		
		
		if($flag){//订单中包含特价产品或活动产品，则只能使用现金券
			//品类券
			$coupon1 = M('user_coupon')->join('a left join weixin_coupon_templet b on a.couponId=b.id')
								  ->where('a.status=0 and b.condition <= '.$sumPrice.' and b.kind =2 and userId='.$this->visitor->info['id'].' and a.end_time >= '.time().' '.$cates)
								  ->field('b.exchangeCond,b.desc,b.kind,b.title,b.condition,b.disPrice,a.id as ucId,a.end_time as etime')
								  ->select();
			//其他券					  
			$coupon2 = M('user_coupon')->join('a left join weixin_coupon_templet b on a.couponId=b.id')
								  ->where('a.status=0 and b.condition <= '.$sumPrice.' and (b.kind >2 or (b.is_recom =1 and b.kind =1)) and userId='.$this->visitor->info['id'].' and a.end_time >= '.time())
								  ->field('b.exchangeCond,b.desc,b.kind,b.title,b.condition,b.disPrice,a.id as ucId,a.end_time as etime')
								  ->select();
			if(count($coupon2)>0&&count($coupon1)>0){
				$coupon=array_merge($coupon1,$coupon2);
			}else if(count($coupon2)>0){
				$coupon=$coupon2;
			}else if(count($coupon1)>0){
				$coupon=$coupon1;
			}
		}
							  
		//现金券
		$coupon3 = M('user_coupon')->join('a left join weixin_coupon_templet b on a.couponId=b.id')
							  ->where('a.status=0 and b.condition <= '.$sumPrice.'  and b.kind =1 and b.is_recom in (0,2,3) and userId='.$this->visitor->info['id'].' and a.end_time >= '.time())
							  ->field('b.exchangeCond,b.desc,b.kind,b.title,b.condition,b.disPrice,a.id as ucId,a.end_time as etime')
							  ->select();
		
		foreach($coupon3 as $k=>$v){
			switch ($v['kind'])
			{
				case 1:
					if($v['condition']==0){
						$coupon3[$k]['type'] = '现金券';
					}else{
						$coupon3[$k]['type'] = '通用券';
					}
					break;  
				case 2:
					$coupon3[$k]['type'] = '品类券';
					break;
				case 3:
					$coupon3[$k]['type'] = '兑换券';
					break;
				case 4:
					$coupon3[$k]['type'] = '新人券';
					break;
				default:
					$coupon3[$k]['type'] = '优惠券';
			}
		}
		foreach($coupon as $k=>$v){
			switch ($v['kind'])
			{
				case 1:
					if($v['condition']==0){
						$coupon[$k]['type'] = '现金券';
					}else{
						$coupon[$k]['type'] = '通用券';
					}
					break;  
				case 2:
					$coupon[$k]['type'] = '品类券';
					break;
				case 3:
					$coupon[$k]['type'] = '兑换券';
					break;
				case 4:
					$coupon[$k]['type'] = '新人券';
					break;
				default:
					$coupon[$k]['type'] = '优惠券';
			}
		}
	 	if(count($coupon3)<1){
			$coupon3 = array();
		}
		if(count($coupon)<1){
			$coupon = array();
		} 
		$jsonArr['couCash'] = $coupon3;
		$jsonArr['couOther'] = $coupon;
		
		$jsonArr['orderId'] = $dingdanhao;
		$jsonArr['sumPrice'] = $sumPrice;
		
		$jsonArr['addr'] = $a_ddr;
		$jsonArr['shopid'] = $shopid;
		$jsonArr['buyitems'] = $cItems;
		
		$jsonArr['merchant'] = M('user')->where(array('id'=>$shopid))->getField('merchant');
		$jsonArr['points'] = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('points');
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	
	//订单确认
	public function confirm(){
		if($_REQUEST['fg']=='Android'){
			$points = $_REQUEST['points'];
			$dingdanhao = $_REQUEST['orderId'];
			$freetype = $_REQUEST['freetype'];//顺丰
			$couId1 = $_REQUEST['couId1'];
			$couId2 = $_REQUEST['couId2'];
			$note = $_REQUEST['note'];
		}else{
			$urlParams = file_get_contents("php://input");
			$params = json_decode($urlParams,true);
			
			$points = $params['points'];
			$dingdanhao = $params['orderId'];
			$freetype = $params['freetype'];//顺丰
			$couId1 = $params['couId1'];
			$couId2 = $params['couId2'];
			$note = $params['note'];
		}
		
		$dingdanhao = substr($dingdanhao,0,18);
		if(!empty($note))//卖家留言
		{
			$data['note']=$note;
		}
	
		$data['supportmetho']=3;
		M('item_order')->where("orderId LIKE '$dingdanhao%'")->save($data);	
			
		if($freetype == 10){ //买家选择顺丰速递  不管商品金额为多少都需要支付运费10元
			$order = M('item_order')->where("orderId LIKE '$dingdanhao%'")->find();
			if($order['goods_sumPrice']<99){
				$order_data['yunfei'] = 20;
			}else{
				$order_data['yunfei'] = 10;
			}
			$order_data['order_sumPrice'] = $order['goods_sumPrice']+10;
			$order_data['freetype'] = $freetype;
			M('item_order')->where("orderId LIKE '$dingdanhao%'")->save($order_data);
		}
			
		//用户如果选择兑换积分
		$order = M('item_order')->where("orderId LIKE '$dingdanhao%'")->find();
		if($points >= 100){
			$order_data['order_sumPrice'] = $order['order_sumPrice']+$order['cash_price']/100-$points/100;
			$order_data['cash_price'] = $points;
			$point_save = M('item_order')->where("orderId LIKE '$dingdanhao%'")->save($order_data);
			if($point_save){
				$del_point = M('user')->where(array('id'=>$order['userId']))->setDec('points',$points);
				if($del_point){
					$message = D('messagelist');
					$message->user_id =$order['userId']; //用户id
					$message->recom = $order['userId']; //用户id
					$message->type = 7; //积分抵扣
					$message->order_id = $order['orderId']; //订单id
					$message->time = time();
					$message->status = 0; // 默认未读状态
					$message->points = $points;
					//截止最后一次兑换积分，账户中的范票余额
					$message->lastpoint = M('user')->where(array('id'=>$order['userId']))->getField('points');
					$message->add();
				}
			}
		}
			
		$disPrice = 0;
		//现金券以外的优惠券抵扣
		if($couId1>0){
			$order = M('item_order')->where('orderId = '.$dingdanhao)->find();
			$coupon = M('user_coupon')->join('a left join weixin_coupon_templet b on a.couponId=b.id')
									 ->where(array('a.id'=>$couId1))
									 ->field('disPrice')
									 ->find();
			$order_data['order_sumPrice'] = $order['order_sumPrice']+$order['coupon_price'] - $coupon['disPrice'];
			$order_data['coupon_price'] = $coupon['disPrice'];//将优惠券抵扣金额写入数据库
			
			
			
			$uc_data['orderId'] = $order['orderId'];
			$uc_data['status'] = 1;
			$uc_data['user_time'] = time();
			M('user_coupon')->where('id='.$couId1)->save($uc_data);
			
			$coupon_save = M('item_order')->where("orderId LIKE '$dingdanhao%'")->save($order_data);
			$disPrice = $coupon['disPrice'];
			
		}
		//叠加通用现金券抵扣
		
		if($couId2>0){
			$order = M('item_order')->where('orderId = '.$dingdanhao)->find();
			$coupon = M('user_coupon')->join('a left join weixin_coupon_templet b on a.couponId=b.id')
									 ->where(array('a.id'=>$couId2))
									 ->field('disPrice')
									 ->find();
			$order_data['order_sumPrice'] = $order['order_sumPrice']+$order['coupon_price']-$disPrice - $coupon['disPrice'];
			$order_data['coupon_price'] += $coupon['disPrice'];//将优惠券抵扣金额写入数据库
			
			$uc_data['orderId'] = $order['orderId'];
			$uc_data['status'] = 1;
			$uc_data['user_time'] = time();
			M('user_coupon')->where('id='.$couId2)->save($uc_data);
			$coupon_save = M('item_order')->where("orderId LIKE '$dingdanhao%'")->save($order_data);
		}
			
		$item_order = M('item_order')->where("orderId LIKE '$dingdanhao%'")->find();
			
		
		if($item_order['supportmetho']==3)
		{
			$this->dopay($dingdanhao,$item_order['order_sumPrice']);
			exit;
		}else{
			echo json_encode(array('status'=>0));
		}
	}
	
	
	
	//订单支付后的返回
	public function pay_info(){
		$orderId = $this->_get('orderId','trim');
		
		$order_info = M('item_order')->join('a left join weixin_user b on a.shopid=b.id')
									 ->where(array('orderId'=>array('like',$orderId.'%')))
									 ->field('orderId,order_sumPrice,merchant')
									 ->find();
		if($order_info){
			$jsonArr['order_info'] = $order_info;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	public function pay_recom(){
		$items = M('item')->where('status=1')->order('rand()')->limit(4)->field('img,title,id,price')->select();
		if(count($items)>0){
			foreach($items as $key=>$val){
				$items[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['items'] = $items;			
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
}