<?php
include_once("class.apiconfig.php");
class ShopcartAction extends frontendAction {
	
	//购物车"猜你喜欢"商品 
	public function likeitems(){
		$itemsbuy = M('posslike')->join("a left join weixin_item as b on a.itemid = b.id")
								 ->field("b.id,b.img,b.title,b.price")
								 ->where(array('a.userid'=>$this->visitor->info['id'],'b.status'=>1))
								 ->order('rand()')
								 ->limit(30)
								 ->select();
		if(count($itemsbuy) < 30){
			$itemsbuy = M('item')->where('status=1')->order('paynum desc')->limit(8)->field('img,title,price,id')->select();
		}
		
		
		
		if(count($itemsbuy)>0){
			foreach($itemsbuy as $key=>$val){
				$itemsbuy[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['items'] = $itemsbuy;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	//删除购物车所有商品
    public function remove_cart_items(){
    	
		//从数据库购物车表中删除该用户所有商品内容
		$shopid = $this->_get('shopid','intval');
		if(M('cart')->where(array('uid'=>$this->visitor->info['id'],'shopid'=>$shopid))->delete()){
			$jsonArr=array('status'=>1,'msg'=>'购物车已清空');
		}else{
			$jsonArr=array('status'=>0,'msg'=>'购物车清空失败');
		}
		echo json_encode($jsonArr);
    }	 

	//添加进购物车
    public function addcart(){
    
    	//获取数据
		$goodId = $this->_get('goodId', 'intval');//商品ID
		$size=$this->_get('size', 'trim');//规格
		$quantity = $this->_get('quantity', 'intval');//购买数量
		$data['num']=$quantity;
		$shopid = $this->_get('shopid');//店铺ID
		$data['shopid']= $shopid;//店铺ID
		$data['id']= $goodId;
		$item=M('item')->field('goods_stock,cost,title,itemtype,baseid,img,yhprice,size,price')->where('id='.$goodId)->find();
		
		
		if($item){
			if($item['size']&&empty($size)){
				$jsonArr=array('status'=>0,'msg'=>'请选择规格');
			}else{
				if(!empty($size)){
				
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
					$where['size']=$size;
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
				
				if($stock<$quantity){
					$jsonArr=array('status'=>0,'msg'=>'库存不足');
				}else{
					
					$where['id'] = $goodId;
					$where['shopid'] = $shopid;
					$where['uid']= $this->visitor->info['id'];
					$cart = M('cart')->where($where)->field('mainid,num')->find();
					if($cart){
						M('cart')->where(array('mainid'=>$cart['mainid']))->setInc('num',$quantity);
						$cartId = $cart['mainid'];
					}else{
						$cartId = M('cart')->add($data);
					}
					
					
					$count=M('cart')->where(array('uid' => $this->visitor->info['id'],'shopid' => $shopid,'is_show' => 0))->count();
					$carts=M('cart')->where(array('uid' => $this->visitor->info['id'],'shopid' => $shopid,'is_show' => 0))->select();
					$cart_price = 0;
					foreach($carts as $key=>$val){
						$cart_price += $val['price']*$val['num'];
					}
					if($cartId){
						$jsonArr=array('status'=>1,'cartId'=>$cartId.'','cart_count'=>$count,'cart_price'=>$cart_price,'msg'=>'商品已成功添加到购物车');
					}else{
						$jsonArr=array('status'=>0,'msg'=>'添加购物车失败');
					}
				}
			}
    	}else{
    		$jsonArr=array('status'=>0,'msg'=>'不存在该商品');
		} 
		
    	echo json_encode($jsonArr);
    }
    //删除购物车商品
    public function remove_cart_item(){
    
    	$mainId=$_GET['id'];//商品ID
		//从数据库购物车表中删除该商品内容
		if(M('cart')->where(array('mainid'=>$mainId))->delete()){
			$jsonArr=array('status'=>1,'msg'=>'商品已成功被删除');
		}else{
			$jsonArr=array('status'=>0,'msg'=>'商品删除失败');
		}
    	echo json_encode($jsonArr);
    }
    //修改数量
    public function change_quantity(){
    
    	$mainid= $this->_get('itemId','intval');
		$id=M('cart')->where(array('mainid'=>$mainid))->getField('id');//商品ID
		
    	$quantity = $this->_get('quantity', 'intval');//购买数量
		$data['num']=$quantity;
		
		$size=$this->_get('size','trim');//规格
    	$item=M('item')->field('goods_stock,size')->where(array('id'=>$id))->find();
	
		if($item['size']&&!empty($size)){
			$goods_stocks=explode('|',$item['goods_stock']);
			$sizes=explode('|',$item['size']);
		
			foreach($sizes as $key=>$val){
				if($val == $size){
					$stock= $goods_stocks[$key];//商品价格
					break;
				}
			}
		}else{
			$stock= $item['goods_stock'];
		}
    	
		
    	if($stock<$quantity){
			$jsonArr=array('status'=>0,'msg'=>'库存不足');
    	}else{
			if(M('cart')->where(array('mainid'=>$mainid))->save($data)){
				$cartItem = M('cart')->where(array('mainid'=>$mainid))->field('num,price')->find();
				$jsonArr=array('status'=>1,'item'=>$cartItem,'msg'=>'修改数量成功');
			}else{
				$jsonArr=array('status'=>0,'msg'=>'修改数量失败');
			}  
    	}
    	echo json_encode($jsonArr);
    }
	//获取库存量
	public function get_stock(){
    
    	$mainid= $this->_get('mainId','intval');
		$cart=M('cart')->where(array('mainid'=>$mainid))->field('id,size')->find();
		if($cart){
			$item=M('item')->field('goods_stock,size')->where(array('id'=>$cart['id']))->find();
			if($item['size']&&!empty($cart['size'])){
				$goods_stocks=explode('|',$item['goods_stock']);
				$sizes=explode('|',$item['size']);
			
				foreach($sizes as $key=>$val){
					if($val == $cart['size']){
						$stock= $goods_stocks[$key];//商品价格
						break;
					}
				}
			}else{
				$stock= $item['goods_stock'];
			}
			$jsonArr=array('status'=>1,'stock'=>$stock);
		}else{
			$jsonArr=array('status'=>0);
		}
    	echo json_encode($jsonArr);
    }
	//购物车内容
	public function cart_items(){
		$shopid = $this->_get('shopid','intval');
		//判断商品是否已经下架，如果是则从购物车中删除。
		$icart = M('cart')->join("a left join weixin_item as b on a.id = b.id")
						  ->where(array('a.uid'=>$this->visitor->info['id']))
						  ->select();
		
		foreach($icart as $vol){
			if($vol['status'] == 0){
				$where['id'] = array('in',$vol['id']); //商品id
				$where['uid'] = $this->visitor->info['id']; //会员id
				M('cart')->where($where)->delete();
			}
		}
		
		//将id相同的产品进行累加(num)   
		//购物车中所有没有商品规格的记录  需进行合并,根据uid进行
		$ret1 = M('cart')->where("shopid = ".$shopid." and uid = ".$this->visitor->info['id']." and size = '' and is_show=0")->field('id')->select();
		$arr1 = array();
		foreach($ret1 as $key => $val){     //剔除uid重复项
			$arr1[$val['id']] = $val['id'];
		} 
		
		//进行合并
		$item = array();   
		foreach($arr1 as $key => $val){
			$result = M('cart')->where(array('id' => $val,'shopid' => $shopid,'uid' => $this->visitor->info['id'],'is_show'=>0))
							   ->field('num,img,name,size,itemtype,id,price,mainid')
							   ->select();
			$cart_count = 0;
			foreach($result as $k => $v){
				$cart_count += $v['num'];
			}
			$result[0]['num'] = $cart_count;
			$item[] = $result[0];
		}
		
		//购物车中所有有商品规格的记录  根据规格再进行合并,根据uid和size
		$ret2 = M('cart')->where("shopid = ".$shopid." and uid = ".$this->visitor->info['id']." and size != '' and is_show = 0")
						 ->field('id')
						 ->select();
		$arr2 = array();
		foreach($ret2 as $key => $val){  //剔除uid重复项
			$arr2[$val['id']] = $val['id'];
		} 
		
		foreach($arr2 as $key => $val){
			$result = M('cart')->where(array('id' => $val,'shopid' => $shopid,'uid' => $this->visitor->info['id'],'is_show'=>0))
							   ->select();//不同规格的同一产品
			
			$arr3 = array();
			foreach($result as $k => $v){  //购物车中已添加的规格,剔除重复项
				$arr3[$v['size']] = $v['size'];
			}
			
			foreach($arr3 as $k => $v){
				$ret = M('cart')->where(array('id' => $val,'shopid' => $shopid,'uid' => $this->visitor->info['id'],'size' => $v,'is_show'=>0))
								->field('num,img,name,size,itemtype,id,price,mainid')
								->select();//不同规格的同一产品
				$cart_count = 0;
				foreach($ret as $kk => $vv){  //合并不同规格的同一产品
					$cart_count += $vv['num'];
				}
				$ret[0]['num'] = $cart_count;
				$item[] = $ret[0];
			}	
		}
		
		$arr4 = array();   			//改写合并后购物车记录中的num字段
		foreach($item as $key => $val){
			$arr4[] = $val['mainid'];
			$data['num'] = $val['num'];
			M('cart')->where('mainid ='.$val['mainid'])->save($data);
		}
	
		if(count($arr4)>0){
			$wh['mainid'] = array('not in',$arr4);  //合并购物车记录后删除多余记录
			$wh['uid'] = $this->visitor->info['id'];
			$wh['shopid'] = $shopid;
			M('cart')->where($wh)->delete();
		}
		
		
		if(count($item)>0){
			foreach($item as $key=>$val){
				$item[$key]['img'] =  ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['item'] = $item;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
    	echo json_encode($jsonArr);
	}
	
	
	//购物车记录条数
	public  function cart_count(){
		$shopid = $this->_get('shopid','intval');
		$count=M('cart')->where(array('uid' => $this->visitor->info['id'],'shopid' => $shopid,'is_show' => 0))->count();
		$jsonArr['status'] = 1;
		$jsonArr['cart_count'] = $count;
		echo json_encode($jsonArr);
		
	}
}