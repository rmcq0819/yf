<?php
include_once("class.apiconfig.php");
class IndexAction extends frontendAction {
	/*****首页广告***/
    public function ads(){
		$shopid = $_GET['shopid'];
		
				
		//获取该商家的信息
		if($shopid && $_SESSION['liulan'] != $this->visitor->info['id']){
			$ll = M('user')->where(array('id'=>$shopid))->field('liulan,ll_update')->find();
			if($ll['ll_update'] != strtotime(date('Y-m-d'))){
				$data['ll_update']	= strtotime(date('Y-m-d'));
				$data['liulan']		= 1;
			}else{
				$data['liulan']= $ll['liulan']+1;
			}
			M('user')->where(array('id'=>$shopid))->save($data);
			$_SESSION['liulan']	= $this->visitor->info['id'];
		}	

		//记录店铺访问历史，用于公众号访问店铺入口
		if($shopid){
			$sellerId = M('seller')->where(array('userid'=>$this->visitor->info['id'],'shopid'=>$shopid))->getField('id');
			if(!$sellerId){
				$sd['userid'] = $user_id;
				$sd['shopid'] = $shopid;
				$sd['addtime'] = time();
				M('seller')->add($sd);
			}else{
				//如用户访问过该店铺，则更新访问时间
				$sd['addtime'] = time();
				M('seller')->where(array('id'=>$sellerId))->save($sd);
			}
		}
    	$ad= M('ad');
    	$ads= $ad->field('content')->where('board_id=1 and status=1')->order('ordid asc')->select();
		
		
		if(count($ads)>0){
			foreach($ads as $key=>$val){
				if($val['content'] != "1612/15/5852609c05cea.png"){
					$content[] = ApiConfig::ADVERT_PATH.$val['content'];
				}
			}
			$jsonArr['ads'] = $content;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	 
	/*****首页优惠券***/
    public function coupons(){
/* 		$t = time();
		$start = mktime(10,0,0,date("m",$t),date("d",$t),date("Y",$t));
		$end = mktime(18,0,0,date("m",$t),date("d",$t),date("Y",$t));
		if(time()>$start&&time()<$end){
			$coupon = M('coupon_templet');
			if(date('w')==0||date('w')==6){	//周末券面金额
				$coupon_templet = $coupon->where(array('is_delete'=>0,'status'=>1,'is_recom'=>array('in','1,3'),'kind'=>1,'end_time'=>array('gt',time())))
										 ->limit('4')
										 ->order('is_recom asc')
										 ->field('id,disPrice,condition')
										 ->select();
			}else{
				$coupon_templet = $coupon->where(array('is_delete'=>0,'status'=>1,'is_recom'=>array('in','1,2'),'kind'=>1,'end_time'=>array('gt',time())))
										 ->limit('4')
										 ->order('is_recom asc')
										 ->field('id,disPrice,condition')
										 ->select();
			}
				
			$t = time();
			$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
			$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
			//判断用户当天是否已领券
			$couIds = M('user_coupon')->where('add_time between '.$start.' and '.$end.' and userId = '.$this->visitor->info['id'])->field('couponId')->select();
			
			foreach($couIds as $key=>$val){
				$arr[] = $val['couponId'];
			}
			$jsonArr['couIds'] = $arr;
			$jsonArr['couTemplets'] = $coupon_templet;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		} */
		$jsonArr['status'] = 0;
		echo json_encode($jsonArr);
	}
	
	/***** 商品列表 ****/
	public function items(){
		$item = M('item');
		
		$pageRows=10; //每页条数
    	import('Think.ORG.Page');
		$where['home'] = 1;
		$where['a.status'] = 1;
		
		$join = "a left join __FLAG__ as b on a.countryId = b.id";
		$count = $item->join($join)->where($where)->count();
    	$Page  = new Page($count,$pageRows);
    	$limit = $Page->firstRow.','.$Page->listRows;
		$jsonArr['nowpage'] = isset($_GET['p'])?$_GET['p']:1;
		$jsonArr['totalpage'] =  ceil($count/$pageRows);
		$home_item = $item->join($join)
						  ->where($where)
						  ->field("a.title,a.id,a.price,a.cover,a.img,b.name,b.flag,is_fictitious as virtual_good,a.yhprice,a.size,a.size_imgs,a.goods_stock")
						  ->order('a.ordid asc')
						  ->limit($limit)
						  ->select();
		if(count($home_item)>0){
			foreach($home_item as $key=>$val){
				$home_item[$key]['cover'] = ApiConfig::ITEM_IMG_PATH.$val['cover'];
				$home_item[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
				$home_item[$key]['flag'] = ApiConfig::FLAG_PATH.$val['flag'];
				
				if($val['size']){
					$price=explode('|',$val['yhprice']);
					$goods_stock=explode('|',$val['goods_stock']);
					$size=explode('|',$val['size']);
					$size_imgs = explode('|',$val['size_imgs']);
					foreach($size_imgs as $k=>$v){
						$size_imgs[$k] = ApiConfig::ITEM_SIZE_PATH.$v;
					}
					$home_item[$key]['size_imgs'] = $size_imgs;
					$home_item[$key]['size_price'] = $price;
					$home_item[$key]['goods_stock'] = $goods_stock;
					$home_item[$key]['size'] = $size;
					$home_item[$key]['stock'] = $goods_stock[0];
				}else{
					$home_item[$key]['size_imgs'] = array();
					$home_item[$key]['size_price'] = array();
					$home_item[$key]['goods_stock'] = array();
					$home_item[$key]['size'] = array();
					$home_item[$key]['stock'] = $val['goods_stock'];
				}
				unset($home_item[$key]['yhprice']);
				$item_like = M('item_like')->where(array('uid'=>$this->visitor->info['id'],'item_id'=>$val['id']))->find();
				if($item_like){
					$home_item[$key]['like'] = '1';
				}else{
					$home_item[$key]['like'] = '0';
				}
			}
			$jsonArr['items'] = $home_item;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		
		echo json_encode($jsonArr);
	}
	
	/***** 虚拟商品 ****/
	public function vitems(){
		$item = M('item');
		$home_vitem = $item->where('is_fictitious = 1 and status = 1')
						  ->field("id,cover")
						  ->order('ordid asc')
						  ->select();
		if(count($home_vitem)>0){
			foreach($home_vitem as $key=>$val){
				$home_vitem[$key]['cover'] = ApiConfig::ITEM_IMG_PATH.$val['cover'];
			}
			$jsonArr['vItems'] = $home_vitem;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}

	/***** 获取分享相关信息 ****/
	public function shareInfo(){
		$shopid = $_GET['shopid'];//分享者ID
		$user = M('user')->where(array('id'=>$shopid))->field('m_desc,merchant,cover,hyimg')->find();
	
		$url=ApiConfig::SERVER_PATH.'/index.php?m=Index&a=index&shares='.$shopid;    
		$jsonArr['url'] = $url;
		if($user['hyimg']==''){
			$jsonArr['imgUrl'] =  $user['cover'];
		}else{
			$jsonArr['imgUrl'] =  ApiConfig::SERVER_PATH.$user['hyimg'];
		}
		$jsonArr['title'] = $user['merchant']."- 团洋范";
		$jsonArr['desc'] = $user['m_desc']."- 团洋范";
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
		
	}
	/***** 热搜关键词 ****/
 	public function hot_keywords(){
		$keywords = M('search')->where(array('status'=>1))->order('num desc')->field('keyword')->select();
		if(count($keywords)>0){
			foreach($keywords as $val){
				$arr[] = $val['keyword'];
			}
			$jsonArr['keywords'] = $arr;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	} 
	/***** 历史搜索 ****/
	public function search_history(){
        $search_historys = array();
		print_r(cookie('search_history'));
        foreach (cookie('search_history') as $key => $value) {
            $search_historys[$key] = urldecode($value);
        }
		if(count($search_historys)>0){
			$jsonArr['historys'] = $search_historys;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['historys'] = $search_historys;
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
    }
	/***** 清空搜索历史 ****/
    public function clear_history(){
        cookie('search_history', NULL);
        setcookie ("content_keyword", "", time()-3600);
        $jsonArr['status'] = 1;
		echo json_encode($jsonArr);
    }
}