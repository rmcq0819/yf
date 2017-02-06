<?php
include_once("class.apiconfig.php");
class ItemAction extends frontendAction {

	/***** 商品详情页商品相册 ****/
	public function imgs(){
		$id = $this->_get('id', 'intval');
        $img_list = M('item_img')->field('url')->where(array('item_id' => $id))->limit(0,5)->order('ordid')->select();
		if(count($img_list)>0){
			foreach($img_list as $key=>$val){
				$arr[] =  ApiConfig::ITEM_IMG_PATH.$val['url'];
			}
			$jsonArr['imgs'] = $arr;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//是否收藏
	public function is_shoucang(){
		$id = $this->_get('id','intval');
		$item_like = M('item_like')->where(array('uid'=>$this->visitor->info['id'],'item_id'=>$id))->find();
		if($item_like){
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 商品详情页推荐商品（类别销量）*****/
	public function recitems(){
		$id = $this->_get('id', 'intval');
		$item=M('item');
		$cate_id = $item->where(array('id'=>$id))->getField('cate_id');
        $itemsbuy = $item->where('cate_id='.$cate_id.' AND status=1 AND id!='.$id)->order('buy_num desc')
							->limit(8)
							->field('id,title,img,price,itemtype')
							->select();
		if(count($itemsbuy)>0){
			foreach($itemsbuy as $key=>$val){
				$itemsbuy[$key]['img'] =  ApiConfig::ITEM_IMG_PATH.$val['img'];
			}
			$jsonArr['items'] = $itemsbuy;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
		
	/***** 商品详情页商品参数*****/
	public function itemparams(){
		$id = $this->_get('id', 'intval');
		$item_mod=M('item');
		$item = $item_mod->field('id,title,adress,itemtype,yhprice,price,goods_stock,buy_num,size,size_imgs,countryId,is_fictitious')
						 ->where(array('id' => $id, 'status' => 1))
						 ->find();
						 
		if($item){
			$arr['id'] = $item['id'];
			$arr['title'] = $item['title'];
			$arr['buy_num'] = $item['buy_num'];
			$arr['itemtype'] = $item['itemtype'];
			$arr['price'] = $item['price'];
			$arr['virtual_good'] = $item['is_fictitious'];
			if($item['countryId']>0){
				$arr['address'] = M('flag')->where(array('Id'=>$item['countryId']))->getField('name');
			}
			$jsonArr['status'] = 1;
			if($item['size']){
				$price=explode('|',$item['yhprice']);
				$goods_stock=explode('|',$item['goods_stock']);
				$size=explode('|',$item['size']);
				$size_imgs = explode('|',$item['size_imgs']);
			
				foreach($size_imgs as $key=>$val){
					$size_imgs[$key] = ApiConfig::ITEM_SIZE_PATH.$val;
				}
				$arr['stock'] = $goods_stock[0];
				$arr['size_imgs'] = $size_imgs;
				$arr['size_price'] = $price;
				$arr['goods_stock'] = $goods_stock;
				$arr['size'] = $size;
				
				$jsonArr['sz'] = 1;
			}else{
				$arr['stock'] = $item['goods_stock'];
				$arr['size_imgs'] = array();
				$arr['size_price'] = array();
				$arr['goods_stock'] = array();
				$arr['size'] = array();
				$jsonArr['sz'] = 0;
			}
			$jsonArr['item'] = $arr;
			
			//记录用户访问商品记录，用于商品推荐及浏览记录
			$posslikeId = M('posslike')->where(array('itemid'=>$id,'userid'=>$this->visitor->info['id']))->getField('id');
			if($posslikIde){
				$data['addtime'] = time();
				M('posslike')->where(array('id'=>$posslikeId))->save($data);
			}else{
				$data['userid'] = $this->visitor->info['id'];
				$data['itemid'] = $id;
				$data['addtime'] = time();
				M('posslike')->add($data);
			}
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}  
	/** 
     * 获取远程图片的宽高和体积大小 
     * 
     * @param string $url 远程图片的链接 
     * @param string $type 获取远程图片资源的方式, 默认为 curl 可选 fread 
     * @param boolean $isGetFilesize 是否获取远程图片的体积大小, 默认false不获取, 设置为 true 时 $type 将强制为 fread  
     * @return false|array 
     */  
    function myGetImageSize($url, $type = 'curl', $isGetFilesize = true){   
      
        // 若需要获取图片体积大小则默认使用 fread 方式  
        $type = $isGetFilesize ? 'fread' : $type;  
       
         if ($type == 'fread') {  
            // 或者使用 socket 二进制方式读取, 需要获取图片体积大小最好使用此方法  
            $handle = fopen($url, 'rb');  
       
            if (! $handle) return false;  
       
            // 只取头部固定长度168字节数据  
            $dataBlock = fread($handle, 2000);  
        }  
        else {  
		
            // 据说 CURL 能缓存DNS 效率比 socket 高  
            $ch = curl_init($url);  
            // 超时设置  
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
            // 取前面 168 个字符 通过四张测试图读取宽高结果都没有问题,若获取不到数据可适当加大数值  
            curl_setopt($ch, CURLOPT_RANGE, '0-1999');  
            // 跟踪301跳转  
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
            // 返回结果  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
       
            $dataBlock = curl_exec($ch);  
            curl_close($ch);  
       
            if (! $dataBlock) return false;  
        }  
       
        // 将读取的图片信息转化为图片路径并获取图片信息,经测试,这里的转化设置 jpeg 对获取png,gif的信息没有影响,无须分别设置  
        // 有些图片虽然可以在浏览器查看但实际已被损坏可能无法解析信息   
		
        $size = getimagesize('data://image/jpg;base64,'. base64_encode($dataBlock));  
        if (empty($size)) {  
            return false;  
        }  
       
        $result['width'] = $size[0];  
        $result['height'] = $size[1];  
       
        // 是否获取图片体积大小  
        if ($isGetFilesize) {  
            // 获取文件数据流信息  
            $meta = stream_get_meta_data($handle);  
            // nginx 的信息保存在 headers 里，apache 则直接在 wrapper_data   
            $dataInfo = isset($meta['wrapper_data']['headers']) ? $meta['wrapper_data']['headers'] : $meta['wrapper_data'];  
       
            foreach ($dataInfo as $va) {  
                if ( preg_match('/length/iU', $va)) {  
                    $ts = explode(':', $va);  
                    $result['size'] = trim(array_pop($ts));  
                    break;  
                }  
            }  
        }  
       
        if ($type == 'fread') fclose($handle);  
       
        return $result;  
    }  
	/***** 商品详情页商品详情图片*****/
	public function iteminfo(){
		$id = $this->_get('id', 'intval');
		$item_mod=M('item');
		$info = $item_mod->where(array('id' => $id, 'status' => 1))->field('info,infoscale')->find();
		if($info['info']){
			preg_match_all("|src=(.*) |U",$info['info'],$match);
			foreach($match[1] as $key=>$val){
				$tmp = trim($val,'"');
				
				if(strpos($tmp,"http")!==0){//未匹配出http
					$tmp = ApiConfig::SERVER_PATH.ltrim($tmp,'.'); 	
				}
				$arr[] = $tmp; 
				if($info['infoscale']==''||$info['infoscale']=='0'){
					$size = $this->myGetImageSize($tmp); 
					if($size){
						$sizearr[] = round($size['width']/$size['height'],1);
					}else{
						$size = getimagesize($tmp);
						$sizearr[] = round($size[0]/$size[1],1);
					}
				}
			}
			if($info['infoscale']!=''){
				$sizearr = explode('|',$info['infoscale']);
				foreach($sizearr as $key=>$val){
					if($val==0){
						unset($sizearr[$key]);
					}
				}
			}
			$jsonArr['status'] = 1;
			$jsonArr['info'] = $arr;
			$jsonArr['scale'] = $sizearr;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	} 
	
	/***** 商品详情页商品详情图片*****/
	public function itemcs(){
		$id = $this->_get('id', 'intval');
		$item_mod=M('item');
		$cs = $item_mod->where(array('id' => $id, 'status' => 1))->field('cs,csscale')->find();
		if($cs['cs']){
			preg_match_all("|src=(.*) |U",$cs['cs'],$match);
			foreach($match[1] as $key=>$val){
				$tmp = trim($val,'"');
				
				if(strpos($tmp,"http")!==0){
					$tmp = ApiConfig::SERVER_PATH.ltrim($tmp,'.'); 	
				}
				$arr[] = $tmp; 
				if($cs['csscale']==''||$cs['csscale']=='0'){
					$size = $this->myGetImageSize($tmp); 
					if($size){
						$sizearr[] = round($size['width']/$size['height'],1);
					}else{
						$size = getimagesize($tmp);
						$sizearr[] = round($size[0]/$size[1],1);
					}
				}
			}
			if($cs['csscale']!=''){
				$sizearr = explode('|',$cs['csscale']);
				foreach($sizearr as $key=>$val){
					if($val==0){
						unset($sizearr[$key]);
					}
				}
			}
			$jsonArr['status'] = 1;
			$jsonArr['cs'] = $arr;
			$jsonArr['scale'] = $sizearr;
		}else{
			$jsonArr['status'] = 0;
		}	
		echo json_encode($jsonArr);
	} 
	
	//getimagesize效率低
	/*function getimageinfo($img) { //$img为图象文件绝对路径 
		$img_info = getimagesize($img);    
		switch ($img_info[2]) { 
			case 1: 
			$imgtype = "gif"; 
			break; 
			case 2: 
			$imgtype = "jpg"; 
			break; 
			case 3: 
			$imgtype = "png"; 
			break; 
		} 
		$img_type = $imgtype."图像"; 
		$img_size = ceil(filesize($img)/1000)."k"; //获取文件大小 
		$new_img_info = array ( 
			"width"=>$img_info[0], 
			"height"=>$img_info[1], 
			"type"=>$img_type 
			"size"=>$img_size 
		} 
		return $new_img_info; 
	}*/
	
	
   
	
	/***** 商品详情页商品评论*****/
	public function itemcoments(){
		$id = $this->_get('id', 'intval');
		$item_comment_mod = M('item_comment');
		$cmt_list = $item_comment_mod->join('a left join weixin_user b ON a.uid=b.id')
														   ->where('a.item_id='.$id.' and a.status = 1')
														   ->order(array('a.add_time'=>'DESC'))
														   ->field('a.add_time,a.uname,a.info,a.picurl1,a.picurl2,a.picurl3,b.id,b.cover,b.hyimg')
														   ->select();
		
		if(count($cmt_list)>0){
			foreach($cmt_list as $key=>$val){
				$cmt_list[$key]['pic'] = '0';
				if($val['picurl1']!==''){
					$cmt_list[$key]['picurl1'] =  ApiConfig::SERVER_PATH.'/'.$val['picurl1'];
					$cmt_list[$key]['pic'] = '1';
				}
				if($val['picurl2']!==''){
					$cmt_list[$key]['picurl2'] =  ApiConfig::SERVER_PATH.'/'.$val['picurl2'];
					$cmt_list[$key]['pic'] = '1';
				}
				if($val['picurl3']!==''){
					$cmt_list[$key]['picurl3'] =  ApiConfig::SERVER_PATH.'/'.$val['picurl3'];
					$cmt_list[$key]['pic'] = '1';
				}
				if($val['hyimg']){
					$cmt_list[$key]['cover'] = ApiConfig::SERVER_PATH.'/'.$val['hyimg']; 
				}else{
					$cmt_list[$key]['cover'] = $val['cover'];
				}
				unset($cmt_list[$key]['hyimg']);
			}
			$jsonArr['status'] = 1;
			$jsonArr['coment'] = $cmt_list;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	/***** 商品详情收藏商品*****/
	public function shoucang() {
		$id = $this->_get('id', 'intval');
		//先检查这个id号对应的item是否存在
		$itemMd = M('item');
		$item = $itemMd->where(array('id' => $id))->find();
		if(!$item) {
			echo json_encode(array("status"=>0,"msg"=>"商品不存在"));
			exit;
		}
 		
		$uid = $this->visitor->info['id'];
		$item_likeMd = M('item_like');
		$is_liked = $item_likeMd->where(array('item_id' => $item['id'], 'uid' => $uid))->count();
		if($is_liked!= 0) {
			$item_likeMd->where(array('item_id' => $item['id'], 'uid' => $uid))->delete();
			echo json_encode(array("status"=>0,"msg"=>"取消收藏成功"));
			exit;
		}else{
			$item_likeMd->add(array('item_id'=>$item['id'], 'uid'=>$uid, 'add_time'=>time()));
			echo json_encode(array("status"=>1,"msg"=>"添加收藏成功"));
		}
	}
	
	public function countrys(){
		$fwhere['status'] = 1;
		$countrys = M('flag')->field('Id,bg_img,name')->where($fwhere)->order('ordid asc')->select();
		if(count($countrys)>0){
			foreach($countrys as $key=>$val){
				$countrys[$key]['bg_img'] = ApiConfig::BG_IMG_PATH.$val['bg_img'];
			}
		
			$jsonArr['status'] = 1;
			$jsonArr['countrys'] = $countrys;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//获取分享相关信息
	public function shareInfo(){
		$id = $_GET['id'];//需分享的商品ID
		$shopid = $_GET['shopid'];//分享者ID
		$item = M('item')->where(array('id'=>$id))->field('img,title')->find();

		$url=ApiConfig::SERVER_PATH.'/index.php?m=Item&a=index&id='.$id.'&shares='.$shopid;  
		$jsonArr['url'] = $url;
		$jsonArr['imgUrl'] =  ApiConfig::ITEM_IMG_PATH.$item['img'];
		$jsonArr['title'] = $item['title']."- 团洋范";
		$jsonArr['desc'] = $item['title']."- 团洋范";
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}
	
	//分类页面-子类信息
	public function cates(){
		$pid = $this->_get('pid','intval');
		$subcates = M('item_cate')->where(array('status' => 1,'pid' => $pid))->field('id,name,img')->order(array('ordid'=>'asc'))->select();
		if(count($subcates)>0){
			foreach($subcates as $key=>$val){
				$subcates[$key]['img'] = ApiConfig::ITEM_CATE_PATH.$val['img'];
			}
			$jsonArr['subcates'] = $subcates;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//分类页面-大类banner图片
	public function banner(){
		$pid = $this->_get('pid','intval');
		$banner = M('item_cate')->where(array('status' => 1,'id' =>$pid))->getField('img');
		if($banner){
			$jsonArr['banner'] = ApiConfig::ITEM_CATE_PATH.$banner;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	public function recoms(){
		$pid = $this->_get('pid','intval');
		$subcates = M('item_cate')->where(array('status' => 1,'pid' => $pid))->field('id')->order(array('ordid'=>'asc'))->select();
		foreach($subcates as $key=>$val){
			$arr[] = $val['id'];
		}
		
		$limit = '6';
		if(count($arr)>0){
			$where['cate_id'] = array('in',$arr);
			$where['status'] = 1;
			$recoms = M('item')->where($where)->field('id,img,price,buy_num,itemtype,size')->order('ordid asc')->limit($limit)->select();
			if(count($recoms)>0){
				foreach($recoms as $key=>$val){
					$recoms[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
				}
				$jsonArr['recoms'] = $recoms;
				$jsonArr['status'] = 1;
			}else{
				$jsonArr['status'] = 0;
			}
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	//分类子页面商品信息
	public function itemfcate(){
    	$cate_id = $this->_get('cate_id','intval');	     //分类ID
		$countryId = $this->_get('countryId','intval'); //国家ID
		$country = $this->_get('country','trim'); //其他国家
		$keyword = $this->_get('keyword','trim');     //关键词
		$condition = isset($_GET['condition'])?$this->_get('condition','trim'):'all_round';

		$where['status'] = 1;
		if($cate_id){
			$where['cate_id'] = $cate_id;
			$detail_title = M('item_cate')->where('id = '.$cate_id)->getField('name');  //获取分类名称
		}
		if($keyword){
			$where['title'] = array('like','%'.$keyword.'%');
			
			$content_keyword = array();                //1.创建一个数组
            $content_keyword[] = urlencode($keyword);  //2.对接受到的关键词插入到数组中去
            if(isset($_COOKIE['content_keyword'])){    //3.判定cookie是否存在,第一次不存在(如果存在的话)
                $now_content = str_replace("\\", "", $_COOKIE['content_keyword']);//4.可以查看下cookie,此时如果unserialize的话出问题的,我把里面的斜杠去掉了
                $now = unserialize($now_content);      //5.把cookie 中的serialize生成的字符串反实例化成数组
                foreach($now as $n=>$w) {              //6.里面很多元素,所以我要foreach 出值
                    if(!in_array($w,$content_keyword)){//7.判定这个值是否存在,如果存在的化我就不插入到数组里面去;
                        $content_keyword[] = $w;       //8.插入到数组
                    }
                }
                $content= serialize($content_keyword);                //9.把数组实例化成字符串
                setcookie("content_keyword",$content, time()+3600*7); //10.插入到cookie
            }else{
                $content= serialize($content_keyword);                //11.把数组实例化成字符串
                setcookie("content_keyword",$content, time()+3600*7); //12.生成cookie
            }
            $getcontent = unserialize(str_replace("\\", "", $_COOKIE['content_keyword']));
            if(count($getcontent)<7){
                cookie('search_history',$getcontent,3600*7);          //13.指定cookie保存时间
            }
			
			$detail_title = $keyword;
		}
		if($countryId){
			$where['countryId'] = $countryId;
			$detail_title = M('flag')->where('Id = '.$countryId)->getField('name');  //获取国家名称
		}
		if($country == 'more'){
			$flags = M('flag')->where('status = 1')->field('id')->select();
			$arr = array();
			foreach($flags as $key => $val){
				$arr[] = $val['id'];
			}
			$where['countryId'] = array('not in',$arr);
			$detail_title = '其他';
		}
		$order = 'ordid asc';
		if($condition == 'sales_desc'){
			$order = 'buy_num desc';
		}
		if($condition == 'sales_asc'){
			$order = 'buy_num asc';
		}
		if($condition == 'price_desc'){
			$order = 'price desc';
		}
		if($condition == 'price_asc'){
			$order = 'price asc';
		}
		if($condition == 'itemtype_1'){
			$where['itemtype'] = 0;
		}
		if($condition == 'itemtype_0'){
			$where['itemtype'] = 1;
		}
		if($condition == 'new'){
			$order = 'add_time desc';
		}
		
		
		$pageRows=12; //每页条数
    	import('Think.ORG.Page');
		$count = M('item')->where($where)->count();
		$Page  = new Page($count,$pageRows);
    	$limit = $Page->firstRow.','.$Page->listRows;
		$jsonArr['nowpage'] = isset($_GET['p'])?$_GET['p']:1;
		$jsonArr['totalpage'] =  ceil($count/$pageRows);
		
		$items = M('item')->where($where)->order($order)->field('id,img,title,price,buy_num,is_fictitious as virtual_good,goods_stock,size,size_imgs,yhprice')->limit($limit)->select();
		$item_likeMd = M('item_like');
        $item_likeArr = $item_likeMd->where(array('uid'=>$this->visitor->info['id']))->field('item_id')->select();
		foreach($item_likeArr as $key=>$val){
			$arr[] = $val['item_id'];
		}
		
		if(count($items)>0){
			foreach($items as $key=>$val){
				$items[$key]['img'] = ApiConfig::ITEM_IMG_PATH.$val['img'];
				
				if($val['size']){
					$price=explode('|',$val['yhprice']);
					$goods_stock=explode('|',$val['goods_stock']);
					$size=explode('|',$val['size']);
					$size_imgs = explode('|',$val['size_imgs']);
					foreach($size_imgs as $k=>$v){
						$size_imgs[$k] = ApiConfig::ITEM_SIZE_PATH.$v;
					}
					$items[$key]['size_imgs'] = $size_imgs;
					$items[$key]['size_price'] = $price;
					$items[$key]['stocks'] = $goods_stock;
					$items[$key]['sizes'] = $size;
					$items[$key]['goods_stock'] = $goods_stock[0];
				}else{
					$items[$key]['size_imgs'] = array();
					$items[$key]['size_price'] = array();
					$items[$key]['stocks'] = array();
					$items[$key]['sizes'] = array();
					$items[$key]['goods_stock'] = $val['goods_stock'];
				}
				unset($items[$key]['yhprice']);
				if($val['size']){
					$items[$key]['size'] = '1';
				}else{
					$items[$key]['size'] = '0';
				} 
				if(in_array($val['id'],$arr)){
					$items[$key]['like'] = '1';
				}else{
					$items[$key]['like'] = '0';
				}
			}
			$jsonArr['items'] = $items;
			$jsonArr['page_title'] = $detail_title;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	
	//国家分类 more图片
	public function othercty(){
		
		$jsonArr['bg_img'] = ApiConfig::MORE_FLAG_PATH;
		$jsonArr['name'] = "其他";
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}

 	//计算所有参数、详情图片宽高比
	public function do_scales(){
		
		$items = M('item')->where("csscale='' and infoscale=''")->field('id')->limit('50')->select();
		foreach($items as $key=>$val){
			$this->doscale($val['id']);
		}
		$jsonArr['status'] = 1;
		echo json_encode($jsonArr);
	}

	public function doscale($id){
		$items = M('item')->where(array('id'=>$id))->field('cs,info,id')->select();
		foreach($items as $key=>$val){
			preg_match_all("|src=(.*) |U",$val['cs'],$match1);
			preg_match_all("|src=(.*) |U",$val['info'],$match2);
			$sizearr1 = '';
		
			foreach($match1[1] as $key1=>$val1){
				$tmp1 = trim($val1,'"');
				
				if(strpos($tmp1,"http")!==0){
					$tmp1 = "http://yooopay.com".ltrim($tmp1,'.'); 	
				}
				
				$size1 = $this->myGetImageSize($tmp1); 
				
				if($size1){
					$sizearr1[] = round($size1['width']/$size1['height'],1);
				}else{
					$size1 = getimagesize($tmp1);
					
					$sizearr1[] = round($size1[0]/$size1[1],1);
				}
			
				$csscale = implode('|',$sizearr1);
			}
			$sizearr2 = '';
			foreach($match2[1] as $key=>$val2){
				$tmp2 = trim($val2,'"');
				
				if(strpos($tmp2,"http")!==0){
					$tmp2 = "http://yooopay.com".ltrim($tmp2,'.'); 	
				}
				 
				$size2 = $this->myGetImageSize($tmp2); 
				
				if($size2){
					$sizearr2[] = round($size2['width']/$size2['height'],1);
				}else{
					$size2 = getimagesize($tmp2);
					$sizearr2[] = round($size2[0]/$size2[1],1);
					
				}
				
				
				$infoscale = implode('|',$sizearr2);
				
			}
			
			$data['csscale'] = $csscale;
			$data['infoscale'] = $infoscale;
		
		dump($val['id']);
		dump($data);
		
			M('item')->where(array('id'=>$val['id']))->save($data);
		}
	} 
}