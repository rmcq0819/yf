<?php
    class varAction extends Action {
        public function test(){
            //$data = '{sign=d1e8bd11b361afb739216ec725f5d0b2&timestamp=1480345940869&inv_bill_details=[{"inv_detail_uid":"8A624839E85332769F3B750EA60A015F","goods_name":"新商品11","sku_name":"","sku_code":" 新商品11","nums":2.0,"sum_sale":2.0,"sum_cost":5.3,"unit":"","isPresent":false},{"inv_detail_uid":"8A624839E85332769F3B750EA60A015F","goods_name":"新商品22","sku_name":"","sku_code":" 新商品22","nums":3.0,"sum_sale":2.0,"sum_cost":564.0,"unit":"","isPresent":false}]&app_key=27ec741f75a73244098308be2d052a5c&format=json&inv_bill={"inv_code":"XC","storage_code":"0023","custom_name":"aaa","customer_nick":"","shop_nick":"23629","shop_uid":"381ADDE392353F7685450602D9CB8DB8","bill_date":"2016-11-22 00:00:00","create_time":"2016-11-22 15:22:13","sum_cost":245,"sum_sale":1800.0,"delivery_fee":0.0,"modified_time":"2016-11-22 15:22:13","post_fee":0.0,"discount_fee":0.0,"paid_fee":2.0,"salercpt_uid":"1A1C4FB4D7F73DBAA3CDB821798D3D14"}}';
            //$arr = array('data'=>$data);
            //$url = "https://api.yooopay.com/index.php?m=var&a=receiveInventoryBill";
            $data = 'sign=F7F3C58CCB74A671FB92BFB30D65C77F&timestamp=1482123451028&inv_bill_details=[{"sku_no":" 新商品11","goods_name":"新商品11","sku_name":"","nums":1,"sum_sale":2.0,"unit":"","sum_cost":0.0}]&app_key=27ec741f75a73244098308be2d052a5c&format=json&inv_bill={"inv_bill_type":"XC","inv_no":"XC201611220002","custom_name":"aaa","customer_nick":"","bill_date":"2016-11-22 00:00:00","create_time":"2016-11-22 15:22:13","sum_sale":2.0,"post_fee":0.0,"paid_fee":2.0,"discount_fee":0.0,"tp_tid":"","shop_name":"线下店铺1","shop_nick":"线下店铺1","storage_code":"0023"}';
            $url =  "http://localhost/test/xin/index.php?m=var&a=receiveInventoryBill";
            $ch = curl_init();//初始化curl
            curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//$post为发送的数据
            $res = curl_exec($ch);//运行curl
            curl_close($ch);
            print_r($res);//输出结果
        }

        public function receiveInventoryBill(){
            //需要用到的key
            $this->_key ='27ec741f75a73244098308be2d052a5c';

            $data = $_POST;

            //参数合法性
            if(empty($data)){
                $jsonarr['response'] = 1;
                $jsonarr['success'] = false;
                $jsonarr['error_code'] = 403; //用户无权限，参数缺失
                $jsonarr['error_msg'] = '参数缺失';
                echo json_encode($jsonarr);
                exit();
            }

            if($data['app_key'] !== $this->_key){
                $jsonarr['response'] = 1;
                $jsonarr['success'] = false;
                $jsonarr['error_code'] = 401; //用户key错误
                $jsonarr['error_msg'] = 'App_Key错误';
                echo json_encode($jsonarr);
                exit();
            }

            /*if($sign != $t_sign){
                $jsonarr['response'] = 1;
                $jsonarr['error_code'] = 401; //用户sign错误
                $jsonarr['error_msg'] = 'sign参数错误';
                echo json_encode($jsonarr);
                exit();
            }*/

            $inventoryBill = (array)json_decode($data['inv_bill']);
            $details       = json_decode($data['inv_bill_details']);

            //走接口
            if($inventoryBill['inv_bill_type']=='XC'){ //销售出库
                $erp_order = $inventoryBill['inv_no']; //erp编号
                $shopid  = $inventoryBill['shop_nick'];//店铺id
                $user  = $inventoryBill['custom_name'];//购买人
                $price  = $inventoryBill['sum_sale'];//订单总金额

                foreach ($details as $key=>$val){
                    $val = (array)$val;
                    $cost += $val['sum_cost']; //总成本
                }

                $profit = $price-$cost;

                //生成订单号
                $dingdanhao = date("Y-m-dH-i-s");
                $dingdanhao = str_replace("-","",$dingdanhao);
                $dingdanhao .= rand(1000,2000);

                //先写入order表
                $order['orderId'] = $dingdanhao; //订单号
                $order['shopid'] = $shopid; //店铺id
                $order['add_time'] = time();//添加时间
                $order['order_sumPrice'] = $price;//订单总金额
                $order['username'] = $user;//购买人
                $order['status'] = 1;//状态
                $order['cost'] = $cost;//总成本
                $order['profit'] = $profit;//总利润
                $order['erp_orderid'] = $erp_order; //erp的订单号
                $reo = M('substance_order')->add($order);


                //计算利率分成等
                $shop_shares = $this->getShareTree($shopid);//获取获得提成的用户ID
                foreach($shop_shares['uid'] as $sk=>$sv){
                    $lvId .=$sv;
                }
                $roy = M('user_fengchenglv')->where(array('id'=>$lvId))->field('royalty')->find();//获取各级别的分成率
                $royArr = explode('|',$roy['royalty']);

                $fclist = M("substance_fenchenglist");

                $wxsend   = new Wxsend();
                foreach($royArr as $rk=>$rv){
                    //店铺分成
                    $fcdata['orderId'] = $dingdanhao;  //订单号
                    $fcdata['fencheng']= round($profit*$rv,2); //分成金额
                    $fcdata['price']= round($price,2); //订单总金额
                    $fcdata['user_name'] = $user;
                    $fcdata['add_time']= time();   //时间
                    $fcdata['state'] 	 = 1;
                    $fcdata['royalty'] = $rv;
                    $fcdata['profit'] = $profit;
                    $fcdata['uid'] = $shop_shares['shareId'][$rk];//获利人id
                    $res = $fclist->add($fcdata);
                    //$wxsend->SR($shop_shares['wechatid'][$rk],round($profitPrice*$rv,2),$time);//提示代理商获得返利
                    $wxsend->SR('oOejpwkHntWxdXEDlk41EZega2Fs',round($profit*$rv,2).' (实体店所营)',time()); //测试样例

                }

                //写入订单详情表
                $order_detail = M('substance_order_detail');
                foreach ($details as $key=>$val){
                    $val = (array)$val;
                    //写入order_detail表
                    $detail['orderId'] = $dingdanhao; //订单号
                    $detail['item_name'] = $val['goods_name']; //商品名称
                    $detail['img'] = $val['img']; //商品图片
                    $detail['price'] = $val['sum_sale']; //销售小计
                    $detail['quantity'] = $val['nums']; //商品数量
                    $detail['size'] = $val['sku_name']; //商品规格
                    $detail['shopid'] = $shopid; //店铺id
                    $detail['status'] = 1; //状态
                    $detail['add_time'] = time(); //添加时间
                    $detail['item_sku'] = $val['sku_no']; //万里牛sku码
                    $detail['cost'] = $val['sum_cost']; //成本
                    $red = $order_detail->add($detail);
                }

                if($res && $reo && $red){
                    $jsonarr['response'] = 0;
                    $jsonarr['success'] = true;
                    $jsonarr['error_code'] = 200; //完成
                    $jsonarr['error_msg'] = '数据接收完成';
                    echo json_encode($jsonarr);
                    exit();
                }else{
                    $jsonarr['response'] = 1;
                    $jsonarr['success'] = false;
                    $jsonarr['error_code'] = 501; //没有成功写入数据库
                    $jsonarr['error_msg'] = '数据写入失败';
                    echo json_encode($jsonarr);
                    exit();
                }

            }elseif ($inventoryBill['inv_bill_type']=='XT'){   //退货

                $erp_order = $inventoryBill['inv_no']; //erp编号
                $shopid  = $inventoryBill['shop_nick'];//店铺id
                $user  = $inventoryBill['custom_name'];//购买人
                $price  = $inventoryBill['sum_sale'];//订单总金额   退货总金额

                foreach ($details as $key=>$val){
                    $val = (array)$val;
                    $cost += $val['sum_cost']; //总成本
                }
                //利润
                $profit = $price - $cost;


                //写入return_order表   退货表
                $order['shopid'] = $shopid; //店铺id
                $order['add_time'] = time();//添加时间
                $order['order_sumPrice'] = $price;//退货订单总金额
                $order['username'] = $user;//购买人
                $order['cost'] = $cost;//退货总成本
                $order['profit'] = $profit;//退货总利润
                $order['erp_orderid'] = $erp_order; //erp的订单号
                $reo = M('substance_return_order')->add($order);

                //减去每一级获得的分利
                $shop_shares = $this->getShareTree($shopid);//获取获得提成的用户ID
                foreach($shop_shares['uid'] as $sk=>$sv){
                    $lvId .=$sv;
                }
                $roy = M('user_fengchenglv')->where(array('id'=>$lvId))->field('royalty')->find();//获取各级别的分成率
                $royArr = explode('|',$roy['royalty']);

                $fc = M("substance_fenchenglist");
                $wxsend   = new Wxsend();
                foreach($royArr as $rk=>$rv){
                    //店铺分成
                    $fencheng= round($profit*$rv,2); //分成金额
                    $id = $shop_shares['shareId'][$rk];
                    $time = time()- 7*24*3600 ;
                    $money = $fc->where(array('uid'=>$id,'fencheng'=>array('egt',$fencheng),'add_time'=>array('gt',$time)))->field('fencheng,profit,orderId')->find();

                    $mdata['fencheng'] = $money['fencheng']-$fencheng;
                    $mdata['profit'] = $money['profit']-$profit;

                    $rem = $fc->where(array('uid'=>$id,'fencheng'=>array('egt',$money['fencheng']),'add_time'=>array('gt',$time)))->limit(1)->save($mdata);

                    //$wxsend->SR($shop_shares['wechatid'][$rk],round($profitPrice*$rv,2),$time);//提示代理商获得返利
                    $wxsend->SR('oOejpwkHntWxdXEDlk41EZega2Fs',round($profit*$rv,2).'(您有一笔退款金额) ',time()); //测试样例
                }

                $sdata['cost'] = $cost;
                $sdata['profit'] = $mdata['profit'];
                $rse = M('substance_order')->where(array('orderId'=>$money['orderId']))->save($sdata);
                if($rem && $reo){
                    $jsonarr['response'] = 0;
                    $jsonarr['success'] = true;
                    $jsonarr['error_code'] = 200; //完成
                    $jsonarr['error_msg'] = '数据接收完成';
                    echo json_encode($jsonarr);
                    exit();
                }else{
                    $jsonarr['response'] = 1;
                    $jsonarr['success'] = false;
                    $jsonarr['error_code'] = 501; //没有成功写入数据库
                    $jsonarr['error_msg'] = '数据写入失败';
                    echo json_encode($jsonarr);
                    exit();
                }

            }else{
                $jsonarr['response'] = 1;
                $jsonarr['success'] = false;
                $jsonarr['error_code'] = 422;//数据没有写入完
                $jsonarr['error_msg'] = '数据写入未完成';
                echo json_encode($jsonarr);
                exit();
            }

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
    }