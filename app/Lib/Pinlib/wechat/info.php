<?php

return array(
    'code'      => 'wechat',
    'name'      => '微信授权',
    'desc'      => '申请地址：http://mp.weixin.qq.com/',
    'author'    => '智风科技',
    'version'   => '1.0',
    'config'    => array(
        'app_key'   => array(
            'text'  => 'Appid',
            'desc'  => '申请：http://mp.weixin.qq.com',
            'type'  => 'text',
        ),
        'app_secret'=> array(
            'text'  => 'appsecret',
            'desc'  => '申请：http://mp.weixin.qq.com',
            'type'  => 'text',
        )
    )
);