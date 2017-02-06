<?php
include_once("class.apiconfig.php");
class NewAction extends frontendAction {
	
	//新品推荐
	public function new_items(){
		$nwh['zhuangui'] = 1;
		$nwh['status'] = 1;
		$nitems = M('item')->where($nwh)->field('id,pic,price,title')->select();
		if(count($nitems)>0){
			foreach($nitems as $key=>$val){
				$nitems[$key]['pic'] = ApiConfig::ITEM_IMG_PATH.$val['pic'];
			}
			$jsonArr['status'] = 1;
			$jsonArr['items'] = $nitems;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
	//专题推荐
	public function special_items(){
		$swh['zhuanti'] = 1;
		$swh['status'] = 1;
		$sitems = M('item')->where($swh)->field('id,pic,price,title,reason,buy_num')->select();
		if(count($sitems)>0){
			foreach($sitems as $key=>$val){
				$sitems[$key]['pic'] = ApiConfig::ITEM_IMG_PATH.$val['pic'];
			}
			$jsonArr['status'] = 1;
			$jsonArr['items'] = $sitems;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	//推荐banner图
	public function ads(){
		$ad= M('ad');
    	$ads= $ad->where('board_id=9 and status=1')->order('ordid asc')->field('content')->select();//推荐banner
		if(count($ads)>0){
			foreach($ads as $key=>$val){
				$content[]= ApiConfig::ADVERT_PATH.$val['content'];
			}
			$jsonArr['status'] = 1;
			$jsonArr['ads'] = $content;
		}else{
			$jsonArr['status'] = 0;
		}
		echo json_encode($jsonArr);
	}
	
}