<?php
class ZhuangpanAction extends frontendAction
{
	public function index()
	{
		$t = time();
		$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
		$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t)); 
		$list = M('user_lottery')->join('a left join weixin_user b on a.userId=b.id left join weixin_zhuanpan_list c on a.prizeId=c.id')
								 ->order('a.Id asc')
								 ->field('b.username,c.prize')
								 ->limit('6')
								 ->where('a.status = 0 and a.draw_time between '.$start.' and '.$end)
								 ->select();
		$this->assign('list',$list);
		
		$where['userId'] = $this->visitor->info['id'];
		if(M('lottery_address')->where($where)->find()){
			$this->assign('addrInfo','true');
		}
		$this->assign('chance',M(user)->where(array('id'=>$this->visitor->info['id']))->getField('chance'));
		$this->display();
	} 

    public function getResult()
	{
		$m = M('zhuanpan_list');
    	$prizeArr = $m->select();//获取奖项设置，需与前台对应
		
		foreach ($prizeArr as $key=>$val)
		{
			$prize_arr[$val['id']-1] = $val;
		}		
		foreach ($prize_arr as $key => $val) 
		{   
			$arr[$val['id']] = $val['chance'];   
		}   
		$rid = $this->get_rand($arr); //根据概率获取奖项id
		$res= $prize_arr[$rid-1]; //中奖项  
		foreach($prize_arr as $key=>$val) //获取前端奖项位置
		{	
			if($val['id'] == $rid){
				$prize_site = $key;
				break;
			}
		}

		$data['prize_name'] = $res['prize'];
		$data['prize_kind'] = $res['kind'];
		$data['prize_pic'] = $res['pic'];
		$data['prize_points'] = $res['points'];
		$data['prize_couponId'] = $res['couponId'];
		$data['prize_site'] = $prize_site;//前端奖项从-1开始
		$data['prize_id'] = $rid;
		$data['res'] = $res;
		
		//每抽走一个实物奖品，将相应奖品的抽中概率减1
		if($res['id']!=5){
			$m->where(array('id'=>$res['id']))->setDec('chance');
		}
		return $data;   
    }
    
    public function get_rand($proArr) 
	{ 
		$result = '';    
		//概率数组的总概率精度   
		$proSum = array_sum($proArr);
		
		//概率数组循环   
		foreach ($proArr as $key =>$proCur) { 
			$randNum = mt_rand(1, $proSum);   
			if ($randNum <= $proCur) {   
				$result = $key;   
				break;   
			} else {   
				$proSum -= $proCur;   
			}         
		}   
		unset ($proArr);    
		return $result;   
	} 
  
	public function record()
	{
		$stime = isset($_GET['stime'])?strtotime(trim($_GET['stime'])):strtotime('2016-9-20');
		$etime = isset($_GET['etime'])?strtotime(trim($_GET['etime'])):strtotime('2017-9-20');
		//全部
		$list = M('user_lottery')->join('a left join weixin_zhuanpan_list b on a.prizeId=b.id')
								 ->order('draw_time desc')
								 ->field('b.*,a.draw_time,a.id')
								 ->where('draw_time between '.$stime.' and '.$etime.' and status=0 and userId='.$this->visitor->info['id'])
								 ->select();
	
		//范票
		$list1 = M('user_lottery')->join('a left join weixin_zhuanpan_list b on a.prizeId=b.id')
								 ->order('draw_time desc')
								 ->field('b.*,a.draw_time,a.id')
								 ->where('draw_time between '.$stime.' and '.$etime.' and kind=1 and status=0 and userId='.$this->visitor->info['id'])
								 ->select();
		//优惠券
		$list2 = M('user_lottery')->join('a left join weixin_zhuanpan_list b on a.prizeId=b.id')
								 ->order('draw_time desc')
								 ->field('b.*,a.draw_time,a.id')
								 ->where('draw_time between '.$stime.' and '.$etime.' and kind=2 and status=0 and userId='.$this->visitor->info['id'])
								 ->select();
		//实物
		$list3 = M('user_lottery')->join('a left join weixin_zhuanpan_list b on a.prizeId=b.id')
								 ->order('draw_time desc')
								 ->field('b.*,a.draw_time,a.id')
								 ->where('draw_time between '.$stime.' and '.$etime.' and kind=3 and status=0 and userId='.$this->visitor->info['id'])
								 ->select();
								 
		$this->assign('list',$list);
		$this->assign('list1',$list1);
		$this->assign('list2',$list2);
		$this->assign('list3',$list3);
		$this->assign('stime',$stime);
		$this->assign('etime',$etime);
		$this->display();
	}
	public function draw()
	{	
		
		$where['userId'] = $this->visitor->info['id'];
		$cost = $_GET['cost'];
  		$count = 1;    //先假设用户已经用过免费的抽奖机会
		$chance = M('user')->where(array('id'=>$this->visitor->info['id']))->getField('chance');
		if($chance){
			$count = 0;
		}else{
			echo json_encode('您的抽奖机会已经用完了,下单满99可以还可以获得机会哦！');
			exit;
		}
		if($count == 0){
			$ret = $this->getResult();
			M('user')->where(array('id'=>$this->visitor->info['id']))->setDec('chance');
			$flag = true;
			if($ret['prize_kind']==1)//范票
			{
				$message = M('messagelist');
				$message->user_id = $this->visitor->info['id'];
				$message->recom = $this->visitor->info['id'];
				$message->type = 20; //大转盘抽奖
				$message->time = time();
				$message->status = 0; // 默认未读状态
				$message->points = $ret['prize_points'];
				$message->add();
				if(!M('user')->where(array('id'=>$this->visitor->info['id']))->setInc('points',$ret['prize_points']))
				{
					$flag = false;
				}
			}
			if($ret['prize_kind']==2)//优惠券
			{
				$coupon['status'] = 0;
				$coupon['userId'] = $this->visitor->info['id'];
				$coupon['couponId'] = $ret['prize_couponId'];
				$coupon['add_time'] = time();
				$days = M('coupon_templet')->where('id = '.$ret['prize_couponId'])->getField('days');
				$coupon['end_time'] = time()+$days*24*3600;
				if(!M('user_coupon')->add($coupon))
				{
					$flag = false;
				}
			}
			
			$data['userId'] = $this->visitor->info['id'];
			$data['prizeId'] = $ret['prize_id'];
			$data['draw_time'] = time();
			
			if(M('user_lottery')->add($data)&&$flag){//写入抽奖记录
				$ret['status'] = 'true';
			}else{
				$ret['status'] = 'false';
			}
			echo json_encode($ret);
			exit;
		}
	}
	
	
	public function delRecord(){
		$id = $_GET['id'];
		$data['status'] = 1;
		if(M('user_lottery')->where('id='.$id)->save($data)){
			echo 'true';
		}else{
			echo 'false';
		}	
	}
	public function addr(){
		$where['userId'] = $this->visitor->info['id'];
		if(M('lottery_address')->where($where)->find()){
			$this->error('您已经填写过地址信息，无需重复填写！');
		}
		if($_POST['name']==''){
			$this->error('请填写姓名！');
		}
		if($_POST['phone']==''){
			$this->error('请填联系电话！');
		}
		if($_POST['address']==''){
			$this->error('请填写详细地址！');
		}
		$data['name'] = $_POST['name'];
		$data['phone'] = $_POST['phone'];
		$data['province'] = $_POST['province'];
		$data['city'] = $_POST['city'];
		$data['district'] = $_POST['district'];
		$data['address'] = $_POST['address'];
		$data['userId'] = $this->visitor->info['id'];
		M('lottery_address')->add($data);
		$this->success('收货地址添加成功！');
	}
	
	public function show(){
		echo json_encode(array('status'=>1));
	}
}
?>