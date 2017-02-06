<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">

	<head>
		<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<title><?php echo ($info["merchant"]); ?></title>
<link href="../Style/css/amazeui.css" rel="stylesheet" type="text/css" />
<link href="../Style/css/css.css" rel="stylesheet" type="text/css" />
<link href="../Style/css/jquery.spinner.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../Style/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../Style/js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="../Style/js/amazeui.min.js"></script>
<script type="text/javascript" src="../Style/js/jquery.spinner.js"></script>
<script type="text/javascript" src="../Style/js/scrollTop.js"></script>
<script type="text/javascript" src="../Style/js/indexshop.js"></script>
<script type="text/javascript" src="../Style/js/jquery.fly.min.js"></script>

<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?5672db5ca194fe9a2ade4373b03c25f5";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
 
<style type="text/css">
#rtt {width:30px; height:30px; background:url(../Style/images/rrt.png); background-size:cover; position:fixed; right:8px; bottom:70px; border-radius: 5px; z-index:999; display:none;}
.proneirong img{width:100%;}
.overlay{
	background:transparent url(../Style/images/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:9999;
	display:none;
}
.overlay .box{position:fixed;z-index:9999;color:#333;width:320px;height:100px;text-align:center;overflow:hidden;top:0;right:0;bottom:0;left:0;margin:auto;border-radius:5px;background:#FFF;display:block;z-index:9999;}
.overlay .box_title{font-size:14px;font-family:微软雅黑;margin-top:22px;padding:5px;border-bottom:1px solid #ccc;}
.overlay .box_btn{margin-top:15px;}
.overlay .box_btn a{margin:0px 40px;color:#0E90D2;font-size:14px;}
.jj a{border:1px solid #ccc; width:25px; height:25px; padding:2px;}

.noshop{width:100%;margin-top:40%; text-align:center;}
.noshop a{padding:5px 35px; background:#C54056;color:#fff;}

.sreach{position:fixed; left:0; top:0;width:100%;height:100%;z-index:9999;background:#DDD;display:none;}
.am-icon-chevron-left{color:#fff;font-size:16px;}
.am-icon-search{color:#fff;font-size:20px;margin-left:8px;}
#itemlist{float:left;width:99%;height:100%; padding:8px;}
#itemlist li{ padding:5px; background:#FFF;margin-bottom:8px;}
.prorenqi a.onselect{color:#FF3300;}
.load{position:fixed;z-index:999;color:#fff;width:320px;height:150px;text-align:center;overflow:hidden;top:0;right:0;bottom:0;left:0;margin:auto;display:none;}
.load img{border-radius:32px;}
.load span{color:#999999;display:block;}
.nolist{width:90%;height:50px;margin:30px auto;font-size:14px;border:1px solid #ddd; text-align:center;line-height:50px;}
.top-title { background:#C54056;height:49px;line-height:49px;color:#FFF;font-size:14px;text-align:center;position: fixed;left:0;top:0;width:100%;transition: top .5s;z-index:9999;}
.top-title2 { background:#C54055;color:#FFF;line-height:32px;padding:5px;text-align:center;position: fixed;left:0;top:0;width:100%;transition: top .5s;z-index:9999;display:none;}
.hiddened{top: -90px;}
.showed{top:0;z-index: 9999;}
.onetop,.twotop,.threetop{width:30px;height:30px;display:block;border-radius:30px;padding:5px;line-height:18px;border:1px solid #333;}
.onetop img,.twotop img,.threetop img{width:20px;heihgt:20px;}
.twotop,.threetop{float:right;}
.onetop1,.twotop1,.threetop1{width:30px;height:30px;display:block;border-radius:30px;padding:5px;line-height:18px;border:1px solid #FFF;}
.onetop1 img,.twotop1 img,.threetop1 img{width:20px;heihgt:20px;}
.twotop1,.threetop1{float:right;}
.cartmsg{width:100%;height:auto; padding:10px 7px;;background:#000;opacity:0.7;-moz-opacity:0.7;filter:alpha(opacity=70);display:none;margin-top:4px;color:#FFFFFF; position:fixed;z-index:9999;font-size:16px;}
#mcover {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    z-index: 20000;
}
#mcover img {
    position: fixed;
    right: 18px;
    top: 5px;
    width: 260px!important;
    height: 180px!important;
    z-index: 20001;
}

.overlay2{
	background:transparent url(../Style/images/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:9999;
	display:none;
}
.overlay3{
	background:transparent url(../Style/images/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:9999;
	display:none;
}
.overlay4{
	background:transparent url(../Style/images/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:9999;
	display:none;
}

.overlay5{
	background:transparent url(../Style/images/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:9999;
	display:none;
}

.addr_index{background:#FFF;width:100%;height:auto;padding-bottom:12px;}
.addr_indexti{ border-bottom:1px solid #DCDCDC; font-size:14px; text-align:center; line-height:32px;}
#addr_index_close{ float:right; margin-right:8px;}
.addr_index ul li{ padding:5px; border-bottom:1px solid #ccc;}
.showinfo{position:fixed; left:35%;bottom:180px;z-index:99999;border-radius:5px;background:#000;opacity:0.9;-moz-opacity:0.9;filter:alpha(opacity=90); padding:0 5px;width:auto;height:38px;box-shadow:0px 0px 10px #000;margin:-60px auto;color:#FFFFFF; text-align:center; line-height:38px;font-size:14px;display:none;}
.login{ color:#009900; text-align:center; display:none;}
.login2{ color:#009900; text-align:center; display:none;}
</style>




		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script language="JavaScript">
		wx.config({
			debug: false,
			appId: 'wxc3f8ad3fc6c24903',
			timestamp: "<?php echo ($jsapi['timestamp']); ?>",
			nonceStr: "<?php echo ($jsapi['timestamp']); ?>",
			signature: "<?php echo ($jsapi['signature']); ?>",
			jsApiList: [
			  'onMenuShareTimeline',
			  'onMenuShareAppMessage',
			]
		  });
		  
		  wx.ready(function () {
			 wx.onMenuShareTimeline({
				title: "幸运大抽奖 - 团洋范", // 分享标题
				link: "http://yooopay.com/index.php?m=Zhuangpan&a=lottery&userId=<?php echo ($info['id']); ?>", // 分享链接
				imgUrl: "http://yooopay.com/Tpl/home/default/Style/index-images/activity/chou-header.jpg", // 分享图标
			});
			wx.onMenuShareAppMessage({
				title: "幸运大抽奖", // 分享标题
				desc: "抽奖大放送", // 分享描述
				link: "http://yooopay.com/index.php?m=Zhuangpan&a=lottery&userId=<?php echo ($info['id']); ?>", // 分享链接
				imgUrl: "http://yooopay.com/Tpl/home/default/Style/index-images/activity/chou-header.jpg", // 分享图标
				type: 'link', 
				dataUrl: '',
			});
		  });
		</script>
		<style type="text/css">
			*{
				font-family: "微软雅黑";
			}
			.topnav {
				z-index: 999;position: fixed;width: 100%;height: 49px;background: rgb(240,93,0);text-align: center;color: #fff;font-size: 18px;line-height: 49px;top: 0;
			}
			.topnav .back{
				position: absolute;left: 8px;top: 6px;margin-top: -8px;
			}
			.topnav img{
				position: absolute;top: 14px;left: 5px;
			}
			/*温馨提示*/
			#chou-tips .t-title{
				font-size: 15px; color: rgb(240,93,0); font-weight: bold; margin-top: 4px;
			}
			#chou-tips .t-content{
				color: rgb(240,93,0); font-size: 13px; margin-top: 4px;
			}
			
			/*转盘*/
			#lottery{
				width:100%;height:310px; margin: 0 auto; padding: 5px;margin-top: 0px; border-top: solid 2px rgb(240,93,0);border-bottom: solid 2px rgb(240,93,0); background-color: rgb(253,205,132);
			}
			#lottery table td{
				height:96px;text-align:center;vertical-align:middle;color:#333; border: solid 2px white; background-color: rgb(250,221,160);
			}
			#lottery table td img{
				width: 100%; height: 75px; 
			}
			#lottery table td a{
				width:100%;height:100px;display:block;text-decoration:none;
			}
			#lottery table td.active{
				/*background-color:#ea0000;*/
				background-color: rgb(197,175,126);
			}
			
			/*次数*/
			#cishu{
				float: right; margin-right: 10px; margin-top: 5px; font-size: 13px; color: #555;
			}
			/*按钮*/
			#btn{
				margin-top: 50px; text-align: center; padding-bottom: 20px;
			}
			#btn button{
				width: 110px;height: 29px;font-size: 13px;background-color: rgb(240,93,0);border: none; outline: none;color: rgb(253,219,140); border: solid 2px rgb(255,216,100); border-radius: 8px;box-shadow: 0px 1px 4px rgb(251,212,92);border-bottom: solid 4px rgb(251,212,92);
			}
			#btn button:first-child{
				margin-right: 20px;
			}
			/*获奖名单*/
			.Top_Record{
				background-color: white; margin: 7px;
			}
			.Top_Record .title{
				width: 65%; height: 40px;font-size: 17px; color: rgb(240,93,0); border-bottom: solid 1px rgb(240,93,0); margin: 0 auto; text-align: center; font-weight: bold; padding-top: 10px;
			}
			.topRec_List dl{ 
				width:90%; overflow:hidden; margin:0 auto; color:#7C7C7C;margin-top: 1px;
			}
			.topRec_List ul{ 
				width:100%;
			}
			.topRec_List li{ 
				width:100%; height:30px; line-height:30px; text-align:center; font-size:12px; 
			}
			.topRec_List li div{ 
				float:left;
			}
			.topRec_List li div:nth-child(1){ 
				width:40%;height:25px;
			}
			.topRec_List li div:nth-child(2){ 
				width:60%;height:25px;
			}
			.topRec_List .user-n,.topRec_List .user-c{
				width:100%;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
			}
			
			/*抽奖说明*/
			#guess-like {
            	height: 50px;line-height: 50px;font-size: 16px;text-align: center;position: relative;color: rgb(240,93,0); margin-top: 15px;
        	}
	        #guess-like .span-1,#guess-like .span-2 {
	            border-top: 1px solid rgb(240,93,0);width: 35%;position: absolute;top: 25px;
	        }
	        #guess-like .span-1{
	            left: 0px;
	        }
	        #guess-like .span-2{
	            right: 0px;
	        }
			#shuoming{
				padding: 0px 10px 10px 10px;
			}
			#shuoming .title{
				color: rgb(240,93,0); font-size: 13px;
			}
			#shuoming .content{
				color: rgb(85,85,85); font-size: 13px;
			}
			
			.am-modal-dialog{
				background: none; position: relative;left: 0; margin-top: -90px;
			}
			.am-modal-dialog .btn-close{
				width: 28px; height: 28px; position: absolute; top: 0; right: 10px;
			}
			.am-modal-dialog .zhong-pro{
				width: 105px;height: 79px;position: absolute; top: 182px; left: 84px;
			}
			.am-modal-dialog .congra{
				color: rgb(255,241,217); margin-top: -56px; font-size: 13px;
			}
			.am-modal-dialog .tips{
				color: rgb(255,241,217);font-size: 13px;
			}
			
			/*填写收货地址*/
			.am-modal-hd{
				background-color: rgb(240,93,0);color: white;padding:10px 10px 11px 10px;
			}
			.am-modal-bd .user-name,.am-modal-bd .user-phone{
				text-align: left; margin-left: 55px; margin-top: 8px;
			}
			.am-modal-bd .user-name input,.am-modal-bd .user-phone input{
				border-bottom: solid 1px rgb(172,172,172); outline: none;
			}
			.am-modal-bd .form-group{
				padding: 0px; margin: 0px; text-align: left;margin-left: 55px; margin-top: 8px;
			}
			.am-modal-bd .form-group label{
				font-size: 14px; margin-left: 9px; text-align: center; padding: 0px; margin: 0px;
			}
			.am-modal-bd .form-group select{
				width: 131px;-webkit-appearance:none;appearance:none;border:none; border-bottom: solid 1px rgb(172,172,172); background-color: white;
			}
			.am-modal-bd .form-group i{
				color: rgb(172,172,172);
			}
			.txtarea{
				text-align: left; margin-top: 8px; margin-left: 31px;
			}	
			.txtarea span{
				font-size: 14px;
			}
			#btn-save{
				width: 100%;position: fixed; bottom: 0; left: 0; 
			}	
			#btn-save button{
				width: 100%;height: 40px;border: none; background-color: rgb(240,93,0); color: white; font-size: 15px;
			}
			
			/*验证消息弹出框*/
			.showinfo {
	        	position: fixed;left: 50%;bottom: 180px;z-index: 99999;border-radius: 5px;background: #000;opacity: 0.9;filter: alpha(opacity=90);
	        	padding: 0 5px;height: 38px;box-shadow: 0px 0px 10px #000;color: #FFFFFF;text-align: center;line-height: 38px;font-size: 14px;display: none;
	        }
			
		
		</style>
		<link rel="stylesheet" href="../Style/css/animate.min.css" />
		<link rel="stylesheet" href="../Style/css/liMarquee.css">
	</head>

	<body style="background-color: rgb(252,233,201);">
		<!-- <div class="topnav">
			<a href="javascript:;" onClick="window.history.back(-1);" class="back">
				<img src="../Style/images/fanhui1.png" width="25" />
			</a>
			转盘大抽奖
		</div> -->
		<!-- <marquee id="marque" style="height: 20px;position: fixed; top: 49px;line-height: 20px; color:black">
			<p>您还有3次抽奖机会</p>
		</marquee> -->
		<div style="margin-top: 49px;">
			<img src="../Style/index-images/activity/chou-header.jpg" alt="头部" class="am-img-responsive" />
		</div>
		
		<!--温馨提示和抽奖说明-->
		<div id="chou-tips">
			<div style="padding: 0px 10px 7px 10px;">
				<p class="t-title">温馨提示：</p>
				<p class="t-content">新用户关注公众号可免费抽奖一次，购买商品还可获得抽奖机会哦&nbsp;<a href="#guess-like" style="color: rgb(240,93,0);">（点击这里可查看底部抽奖说明>>）</a></p>
			</div>
			<div style="clear: both;"></div>
		</div>
		<!--页面锚点平滑滚动的效果-->
		<script type="text/JavaScript">
			$(document).ready(function() {
			    $('a[href*=#]').click(function() {
			        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			            var $target = $(this.hash);
			            $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']'); 
			            if ($target.length) {
			                var targetOffset = $target.offset().top;
			                $('html,body').animate({
			                    scrollTop: targetOffset
			                },
			                800);
			                return false;    
			            }
			        }
			    });
			});
		</script>
		
		<!--转盘-->
		<div id="lottery">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="lottery-unit lottery-unit-0"><img src="../Style/index-images/activity/1_1.png"></td>
					<td class="lottery-unit lottery-unit-1"><img src="../Style/index-images/activity/2_2.png"></td>
					<td class="lottery-unit lottery-unit-2"><img src="../Style/index-images/activity/3_3.png"></td>
				</tr>
				<tr>
					<td class="lottery-unit lottery-unit-7"><img src="../Style/index-images/activity/6_6.png"></td>
					<td><img src="../Style/index-images/activity/choujiang2.png" alt="立即抽奖" style="height: 100px; margin-top: 0px;" onclick="draw('free');"/></td>
					<td class="lottery-unit lottery-unit-3"><img src="../Style/index-images/activity/7_7.png"></td>
				</tr>
				<tr>
					<td class="lottery-unit lottery-unit-6"><img src="../Style/index-images/activity/4_4.png"></td>
					<td class="lottery-unit lottery-unit-5"><img src="../Style/index-images/activity/8_8.png"></td>
					<td class="lottery-unit lottery-unit-4"><img src="../Style/index-images/activity/5_5.png"></td>
				</tr>
			</table>
		</div>
		<p id="cishu">剩余抽奖次数：<span style="color: rgb(240,93,0); font-size: 15px;"><?php echo ($chance); ?></span>&nbsp;次</p>
		<!--按钮-->
		<div id="btn">
			<button onclick="location.href='<?php echo U('Zhuangpan/record');?>'">我的中奖纪录</button>
			<button onclick="share();">分享到朋友圈</button>
		</div>
		
		<!--获奖名单-->
		<div class="Top_Record">
			<p class="title"><i class="am-icon-trophy"></i>&nbsp;&nbsp;获奖名单</p>
		    <div class="topRec_List">
				<!--<marquee id="maquee" direction="up" scrollamount="4" style="max-height: 195px;min-height: 80px;">
	    			<ul>
	    				<?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($k % 2 );++$k;?><li>
							<div><p style="width:100%;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo ($vol["username"]); ?></p></div>
							<div><p style="width:100%;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">获得<?php echo ($vol["prize"]); ?></p></div>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
	    			</ul>
	    		</marquee>-->
	    		<div id="maquee" style="width: 100%; height: 200px;background-color: white; color: #555;">
					<ul>
						<?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($k % 2 );++$k;?><li>
							<div><p class="user-n"><?php echo ($vol["username"]); ?></p></div>
							<div><p class="user-c">获得<?php echo ($vol["prize"]); ?></p></div>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<!--无缝滚动-->
		<script src="../Style/js/jquery.liMarquee.js"></script>
		<script>
			$(function(){
				$('#maquee').liMarquee({  
					direction: 'up',
					scrollamount:'42'
				});
			});
		</script>

		<!--抽奖说明-->
		<p id="guess-like">
        	<span class="span-1"></span>
        		抽奖说明&nbsp;<i class="am-icon-book"></i>
        	<span class="span-2"></span>
    	</p>
		<div id="shuoming">
			<p class="title">一、活动时间</p>
			<p class="content">2016年11月-28日 - 2016年12月-12日</p>
			<p class="title" style="margin-top: 9px;">二、活动规则</p>
			<p class="content">1、手指点击中间的的抽奖按钮，转盘开始转动，转动一定时间后自动停止，暗格停留所在的奖品即为中奖奖品。</p>
			<p class="content">2、新用户免费一次（进入商城界面后，点击上方的关注按钮、点击关注我们公众号后就可以获得1次抽奖机会）下单满99元也还可以进行一次抽奖，满298元获得2次，满500元可以获得5次</p>
			<p class="title" style="margin-top: 9px;">三、活动奖品发放</p>
			<p class="content">1、活动抽到范票，系统将自动为您存放到"个人中心-范票"。</p>
			<p class="content">2、实物奖品在中奖5个工作日后寄往中奖者所填写的收货地址处。</p>
		</div>
		
		<!--弹出框-->
		<button id="btn2" type="button" class="am-btn am-btn-primary" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0, width: 280}" style="display:none;">
  			你中奖了
		</button>

		<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
			<div class="am-modal-dialog" style="position:relative; left:0px;">
				<img class="btn-close" data-am-modal-close src="../Style/index-images/activity/close.png" onclick="clk();" alt="关闭"/>
			    <img src="../Style/index-images/activity/zhongjiang.png" alt="中奖了" style="width: 260px;" />
			    <img class="zhong-pro animated rollIn" src="" id="prizeImg" alt="中奖商品"/>
			    <p class="congra animated bounceInDown" id="prizeContent"></p>
			    <p class="tips animated  bounceInDown">（奖品可在“我的中奖纪录”中查看）</p>
				<button onclick="share();" style="width:120px; height:23px; border:solid 2px rgb(255,216,100);border-bottom:solid 3px rgb(255,216,100); background-color:rgb(240,93,0); color:rgb(255,216,100); border-radius:6px; margin-top:20px; position:absolute; left:50%; top:327px; margin-left:-60px;">向小伙伴得瑟一下</button>
			</div>
		</div>
		<!--收货地址S-->
		<button id="btn-addr" type="button" class="am-btn am-btn-primary" data-am-modal="{target: '#doc-modal-2', closeViaDimmer: 0, width: 300, height: 330}" style="display: none;">
  			Modal
		</button>

		<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-2" style="padding-top: 60px;">
		  	<div class="am-modal-dialog" style="background-color: white;">
			    <div class="am-modal-hd">
			    	填写获奖品收货地址
			      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close style="color: white;opacity: 1;">&times;</a>
			    </div>
			    <div class="am-modal-bd">
					<form class="form-inline" method="post" action="<?php echo U('Zhuangpan/addr');?>" id="from" name="form">
			      	<div class="container">
	
					
							<p class="user-name"><span style="font-size: 14px;">姓&nbsp;名：</span><input type="text" name="name" placeholder="姓名" style="width: 142px;"/></p>
							<p class="user-phone"><span style="font-size: 14px;">电&nbsp;话：</span><input type="text" name="phone" placeholder="电话" style="width: 142px;" /></p>
						  	<div data-toggle="distpicker" style="padding: 0px; margin: 0px;">
								<div class="form-group">
								  	<label class="sr-only" for="province2" >省&nbsp;份：</label>
								  	<select class="form-control" id="province2" name="province" data-province="---- 选择省 ----" ></select><i class="am-icon-chevron-down"></i>
								</div>
								<div class="form-group">
								  	<label class="sr-only" for="city2">城&nbsp;市：</label>
								  	<select class="form-control" id="city2" name="city" data-city="---- 选择市 ----" ></select><i class="am-icon-chevron-down"></i>
								</div>
								<div class="form-group">
								  	<label class="sr-only" for="district2">地&nbsp;区：</label>
								  	<select class="form-control" id="district2" name="district" data-district="---- 选择区 ----" ></select><i class="am-icon-chevron-down"></i>
								</div>
						  	</div>
						  	<div class="txtarea" style="">
						  		<span>详细地址：</span>
						  		<textarea name="address" id="address" rows="" cols="" placeholder="详细地址" style="width: 140px; border: solid 1px rgb(172,172,172);"></textarea>
						  	</div>
						  	<p style="margin-top: 5px; color: #555;">该地址为奖品邮寄地址，请确认无误后再保存！</p>
						

					</div>
					<div id="btn-save">
						<button id="check">保&nbsp;&nbsp;存</button>
					</div>
					</form>
			    </div>
			    
		  	</div>
		</div>
		<div class="showinfo animated shake"></div>
		<input type="hidden" id="addrInfo" value="<?php echo ($addrInfo); ?>"/>
		<input type="hidden" id="prizeId" value=""/>
		<!--收货地址E-->
		
		<!--分享S-->
		<div id="mcover" onclick="document.getElementById('mcover').style.display='';" style="display:none;">
			<img src="../Style/images/guide.png" />
		</div>
		<!--分享E-->
		<!-- <link rel="stylesheet" type="text/css" href="../Style/css/animations.css"/>
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar" id="" style="background-color:white; box-shadow:20px 20px 40px black;">
<ul class="am-navbar-nav am-cf am-avg-sm-5">
	  <li >
		<a href="<?php echo U('Index/index',array('shares'=>$info['id']));?>" class="">
			<img src="../Style/index-images/home.gif">
			<span class="am-navbar-label" style="color:rgb(137,137,137);">首页</span>
		</a>
	  </li>
	  <li>
		<a href="<?php echo U('Item/itemcate',array('shares'=>$info['id']));?>" class="">
			<img src="../Style/index-images/sort.gif" > 
			<span class="am-navbar-label" style="color:rgb(137,137,137);">分类</span>
		</a>
	  </li>
	  <li class="bottomhome">
		<a href='https://static.meiqia.com/dist/standalone.html?eid=23554&clientid=<?php echo ($server_u["id"]); ?>&metadata={"name":"<?php echo $server_u['username']; ?>","tel":"<?php echo $server_u['phone_mob']; ?>","email":"<?php echo $server_u['email']; ?>"}'>
			<img src="../Style/index-images/erweima.png">
			<span class="am-navbar-label" style="color:rgb(137,137,137);">客服</span>
		</a>
	  </li>
	  <li>
		<a href="<?php echo U('Shopcart/index',array('shares'=>$info['id']));?>">
		 <img src="../Style/index-images/shop-car.gif" > 
			<span class="am-navbar-label" style="color:rgb(137,137,137);">购物车</span>
		</a>
	  </li>
	  <li >
		<a href="<?php echo U('User/index',array('shares'=>$info['id']));?>" class="">
		 <img src="../Style/index-images/mine.gif" > 
			<span class="am-navbar-label" style="color:rgb(137,137,137);">我的</span>
		</a>
	  </li>
  </ul>
  <img id="tixing" src="../Style/index-images/activity/99tixing.png" alt="满99" style="width:110px; position:fixed; right:18%; bottom:49px; z-index:800; display: none;"/>
</div>
<script type="text/javascript">
	$(function(){
		$("#tixing").show();
		$("#tixing").addClass("slideExpandUp");
		$("#tixing").delay(4500).fadeOut();
	})
</script>
 -->
		<script src="../Style/js/distpicker.data.js" type="text/javascript" charset="utf-8"></script>
		<script src="../Style/js/distpicker.js" type="text/javascript" charset="utf-8"></script>
 		<script type="text/javascript">
			function clk(){
				var addrInfo = $("#addrInfo").val();
				var prizeId = $("#prizeId").val();
				
				if(addrInfo!=='true'&&prizeId!=4){
					$("#btn-addr").click();
					if($("doc-modal-2").css("display")!="none")     //禁止页面滚动
					{
						window.ontouchmove=function(e){
						e.preventDefault && e.preventDefault();
						e.returnValue=false;
						e.stopPropagation && e.stopPropagation();
						return false;
						}
					}
				}
			}
			$(function(){
				$(".am-close").on("click",function(){        //当关闭弹出框页面的时候   页面可以滚动
					window.ontouchmove=null; 
				});
			});
			$(function(){
				$(".btn-close").on("click",function(){        
					
				});
			});
		</script>

		<script type="text/javascript" src="../Style/layer/layer.js" charset="utf-8"></script>
		<script type="text/javascript">
			var lottery={
				index:-1,	//当前转动到哪个位置，起点位置
				count:0,	//总共有多少个位置
				timer:0,	//setTimeout的ID，用clearTimeout清除
				speed:20,	//初始转动速度
				times:0,	//转动次数
				cycle:50,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
				prize:-1,	//中奖位置
				pic:"",
				content:"",
				prize_site:"-1",
				init:function(id){
					if ($("#"+id).find(".lottery-unit").length>0) {
						$lottery = $("#"+id);	
						$units = $lottery.find(".lottery-unit");
						this.obj = $lottery;
						this.count = $units.length;
						$lottery.find(".lottery-unit-"+this.index).addClass("active");
					};
				},
				roll:function(){
					var index = this.index;
					var count = this.count;
					var lottery = this.obj;
					$(lottery).find(".lottery-unit-"+index).removeClass("active");
					index += 1;
					if (index>count-1) {
						index = 0;
					};
					$(lottery).find(".lottery-unit-"+index).addClass("active");
					this.index=index;
					return false;
				},
				stop:function(index){
					this.prize=index;
					return false;
				}
			};
			
			function roll(){
				lottery.times += 1;
				lottery.roll();
				if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
					clearTimeout(lottery.timer);
					lottery.prize=-1;
					lottery.times=0;
					click=false;
					$("#prizeImg").attr("src","../Style/index-images/activity/"+lottery.pic);
					$("#prizeContent").text("恭喜您获得"+lottery.content);
					setTimeout(function(){
						$("#btn2").click();
					},1500);
				}else{
					if (lottery.times<lottery.cycle) {
						lottery.speed -= 10;
					}else if(lottery.times==lottery.cycle) {
						lottery.prize = lottery.prize_site;		
					}else{
						if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
							lottery.speed += 110;
						}else{
							lottery.speed += 20;
						}
					}
					if (lottery.speed<40) {
						lottery.speed=40;
					};
					//console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize+'^^^^^^^'+lottery.index);
					lottery.timer = setTimeout(roll,lottery.speed);
					
				}
				
				return false;
			}
			
			var click=false;
			
			window.onload=function(){
				lottery.init('lottery');
			};
			function draw(type){
				var url="<?php echo U('Zhuangpan/draw');?>";
				$.getJSON(url,{cost:type},function(data){
					//alert(data.prize_pic+"-"+data.prize_name+"-"+data.prize_site);
					if(data.status=='true'){
						if (click) {
							return false;
						}else{
							lottery.speed=100;
							lottery.prize_site=data.prize_site;
							$("#prizeId").val(data.prize_site);
							lottery.content=data.prize_name;
							lottery.pic=data.prize_pic;
							roll();
							click=true;
							return false;
						}
					}else if(data.status=='false'){
						alert('非常抱歉出错了...');
					}else{
						if(data=='今天的免费抽奖次数已用完啦(>﹏<。)～用10张范票再抽一次？！'){
							layer.open({
								title: '提示',
								content: data,
								btn: ['确认', '取消'],
								yes: function(index){
									layer.close(index);
									draw('pay');
								}
							});
						}else{
							layer.open({content: data, time: 2,});
							//location.reload();
						}
					}
				});
			}
			function share() {
				$("#mcover").show();
			}
//			验证收货地址
			$(function() {
				$("#check").click(function(){
					var name = $("input[name='name']").val();
					var phone = $("input[name='phone']").val();
					var address = $("#address").val() ;
					
					if(name==''){
						var content = $('.showinfo').html('亲，请填写收货人姓名');
						var w = content.width()/2;
						$(".showinfo").css("margin-left",-w);
						$(".showinfo").show().delay(3000).fadeOut();
						return false;  
					}
					if(phone==''){
						var content = $('.showinfo').html('亲，请填写手机号码');
						var w = content.width()/2;
						$(".showinfo").css("margin-left",-w);
						$(".showinfo").show().delay(3000).fadeOut();
						return false;  
					}
					var phoneReg = /^1([38]\d|4[57]|5[0-35-9]|7[06-8]|8[89])\d{8}$/;
					if(!phoneReg.test(phone)){
						var content = $('.showinfo').html('亲，手机号码无效');
						var w = content.width()/2;
						$(".showinfo").css("margin-left",-w);
						$(".showinfo").show().delay(3000).fadeOut();
						return false;  
					}
					if(address==''){
						var content = $('.showinfo').html('亲，请填写收货详细地址');
						var w = content.width()/2;
						$(".showinfo").css("margin-left",-w);
						$(".showinfo").show().delay(3000).fadeOut();
						return false;  
					}
					$("#form").submit(); 
				 });
			});
		</script>
		<!--获奖js-->
		<script type="text/javascript"> 
			function autoScroll(obj){  
				$(obj).find("ul").animate({  
					marginTop : "-37px"  
				},500,function(){  
					$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
				})  
			}  
			$(function(){  
				setInterval('autoScroll(".maquee")',800);
			}) 
		</script>
	
	</body>

</html>