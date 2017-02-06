<?php
class couponAction extends frontendAction {
	
	public function get_coupon(){
		$id = $this->_get('id','intval');
  		$t = time();
		$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
		$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
		 
		 $ret = M('user_coupon')->where('add_time between '.$start.' and '.$end.' and userId = '.$this->visitor->info['id'].' and couponId = '.$id)->find();
		if($ret){
			$jsonArr['msg'] = '今天已经领取过啦！';
			$jsonArr['status'] = 0;
		}else{  
		
			$data['status'] = 0;
			$data['userId'] = $this->visitor->info['id'];
			$data['couponId'] = $id; 
		 	$data['add_time'] = time();
			$days = M('coupon_templet')->where('id = '.$id)->getField('days');
			$data['end_time'] = time()+$days*24*3600; 
			
			if(M('user_coupon')->add($data)){
				$jsonArr['msg'] = '领取成功！';
				$jsonArr['status'] = 1;
			 }else{
				$jsonArr['msg'] = '领取失败！';
				$jsonArr['status'] = 0;
			}  
			M('coupon_templet')->where(array('id'=>$id))->setInc('count',1);
		}   
		echo json_encode($jsonArr);
	}
	
}