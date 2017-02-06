<?php
class SubinfoModel extends Model
{
   // 定义自动验证
    protected $_validate    =   array(
        array('username','require','姓名必须填写 ！'),
        array('phone_mob','require','手机号码必须填写 ！'),
		array('phone_mob','11','手机号码长度没有满足要求 ！',3,'length'),
		array('phone_mob','/^1[3|4|5|8][0-9]\d{4,8}$/','手机号码格式错误 ！','0','regex',1),
        array('wxname','require','微信号必须填写 ！'),
        array('address','require','联系地址必须填写 ！'),
		
     );
   
}