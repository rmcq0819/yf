<?php if (!defined('THINK_PATH')) exit();?><!doctype html><html class="no-js"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="description" content=""><meta name="keywords" content=""><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"><title><?php echo ($info["merchant"]); ?></title><link href="../Style/css/amazeui.css" rel="stylesheet" type="text/css" /><link href="../Style/css/css.css" rel="stylesheet" type="text/css" /><link href="../Style/css/jquery.spinner.css" rel="stylesheet" type="text/css" /><script type="text/javascript" src="../Style/js/jquery-1.7.1.min.js"></script><script type="text/javascript" src="../Style/js/TouchSlide.1.1.js"></script><script type="text/javascript" src="../Style/js/amazeui.min.js"></script><script type="text/javascript" src="../Style/js/jquery.spinner.js"></script><script type="text/javascript" src="../Style/js/scrollTop.js"></script><script type="text/javascript" src="../Style/js/indexshop.js"></script><script type="text/javascript" src="../Style/js/jquery.fly.min.js"></script><script>var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?5672db5ca194fe9a2ade4373b03c25f5";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script><style type="text/css">#rtt {width:30px; height:30px; background:url(../Style/images/rrt.png); background-size:cover; position:fixed; right:8px; bottom:70px; border-radius: 5px; z-index:999; display:none;}
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
</style><link rel="stylesheet" type="text/css" href="../Style/css/animate.min.css"/><style>
			* {
				font-family: "微软雅黑";
			}
			.topnav {
				width: 100%;
				height: 50px;
				line-height: 50px;
				color: white;
				font-size: 18px;
				text-align: center;
				position: fixed;
				top: 0;
				z-index: 999;
				background-color: rgb(240, 93, 0);
			}
			.topnav img{
				position: absolute; 
				left:5px; 
				top: 14px;
			}			
			/*头部*/
			
			/*选项卡*/
			.tab1{
				width:100%;margin:50px auto 0 auto;
			}
			.menu{
				width: 100%;height:28px;border-right:#cccccc solid 1px;
			}
			.menu li{
				float:left;width:25%;text-align:center;line-height:40px;height:40px;cursor:pointer;color:#666;font-size:14px;overflow:hidden;background-color: white;
			}
			.menu li.off{
				background:#FFFFFF;color:rgb(240,93,0); font-size: 17px;
			}
			.menu li.off span{	
				padding: 0px 0px 7px 0px; border-bottom: solid 1px rgb(240,93,0);
			}

			/*内容列表*/
			#con_one_1 dl dt,#con_one_2 dl dt,#con_one_3 dl dt,#con_one_4 dl dt{
				padding: 12px 8px 12px 8px ; font-weight: 400; margin-top: 7px;
			}
			#con_one_1 dl dt:first-child,#con_one_2 dl dt:first-child,#con_one_3 dl dt:first-child,#con_one_4 dl dt:first-child{
				margin-top: 0px;
			}
			/*时间选择S*/
			.choose-time{
				background-color: white; height: 38px; line-height: 38px; margin-top: 100px; border-bottom: solid 1px rgb(200,200,200);
			}
			.search-btn{
				text-align: right; padding-right: 5px;
			}
			.search-btn button{
				background-color: rgb(240,93,0); color: white; border: none; padding: 2px 9px 2px 9px; margin-top: -4px;
			}
			/*时间选择E*/
			
			#con_one_1 dl dt .title,#con_one_2 dl dt .title,#con_one_3 dl dt .title,#con_one_4 dl dt .title{
				font-size: 14px; padding-left: 8px;
			}
			#con_one_1 dl dt .content,#con_one_2 dl dt .content,#con_one_3 dl dt .content,#con_one_4 dl dt .content{
				font-size: 12px; padding-left: 8px; color: rgb(171,171,171);	
			}
			#con_one_1 dl dt .del,#con_one_2 dl dt .del,#con_one_3 dl dt .del,#con_one_4 dl dt .del{
				text-align: right;
			}
			#con_one_1 dl dt .del img,#con_one_2 dl dt .del img,#con_one_3 dl dt .del img,#con_one_4 dl dt .del img{
				width: 20px; height: 20px; margin-top: -6px;
			}
			#con_one_1 dl dt .time,#con_one_2 dl dt .time,#con_one_3 dl dt .time,#con_one_4 dl dt .time{
				text-align: right; color: rgb(171,171,171);
			}
			.am-margin-right{
				margin-right: 0px;
			}
			
			/*没有记录的时候*/
			.no-re{
				text-align: center; color: #555; margin-top: 90px;
			}
			.no-re img{
				width: 90px; height: 90px; margin-bottom: 15px;
			}
			.no-re p span{
				color: rgb(240,93,0); text-decoration: underline;
			}
			/*验证消息弹出框*/
			.showinfo {
	        	position: fixed;left: 25%;bottom: 180px;z-index: 99999;border-radius: 5px;background: #000;opacity: 0.9;filter: alpha(opacity=90);padding: 0 5px;height: 38px;box-shadow: 0px 0px 10px #000;margin: -60px auto;color: #FFFFFF;text-align: center;line-height: 38px;font-size: 14px;display: none;
	        }
			
		</style></head><body style="background:#f3f3f3;"><div class="topnav"><a href="<?php echo U('Zhuangpan/index');?>" onClick="window.history.back(-1);" class="back"><img src="../Style/images/fanhui1.png" width="25" /></a>
			中奖纪录
		</div><div class="tab1" id="tab1" style="margin-top: 90px;"><div class="menu" style="position:fixed;top:50px; left:0px; z-index: 1000; border: none; "><ul><li id="one1" onclick="setTab('one',1)"><span>全部</span></li><li id="one2" onclick="setTab('one',2)"><span>范票</span></li><li id="one3" onclick="setTab('one',3)"><span>抵扣券</span></li><li id="one4" onclick="setTab('one',4)"><span>实物</span></li></ul></div><!--时间选择器--><div class="am-g choose-time"><div class="am-u-sm-5" style="padding-left: 2px;"><span class="am-margin-right" id="my-start"><span>开始日期</span>：
			    	 	<span id="my-startDate" style="color: rgb(240,93,0);"><?php echo (date('Y-m-d',$stime)); ?></span></span></div><div class="am-u-sm-5" style="padding-left: 6px;"><span class="am-margin-right" id="my-end"><span>结束日期：</span><span id="my-endDate" style="color: rgb(240,93,0);"><?php echo (date('Y-m-d',$etime)); ?></span></span></div><div class="am-u-sm-2 search-btn"><button onclick="search();">查&nbsp;找</button></div></div><input type="hidden" name="stime" id="stime" value="<?php echo (date('Y-m-d',$stime)); ?>"><input type="hidden" name="etime" id="etime" value="<?php echo (date('Y-m-d',$etime)); ?>"><div class="menudiv" style="margin-top: px;"><!--全部--><div id="con_one_1"><?php if(!empty($list)): ?><dl><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><dt style="background-color: white;"><div class="am-g"><div class="am-u-sm-2"><img src="../Style/index-images/activity/<?php echo ($vol["pic"]); ?>" alt="" class="am-img-responsive" style="height: 43px;"/></div><div class="am-u-sm-7"><p class="title">获得<?php echo ($vol["prize"]); ?></p><?php if($vol["kind"] == 1): ?><p class="content">已存入"会员中心"-"我的范票"</p><?php endif; if($vol["kind"] == 2): ?><p class="content">已存入"会员中心"-"优惠券"</p><?php endif; if($vol["kind"] == 3): ?><p class="content">我们会尽快邮寄到您手中</p><?php endif; echo ($vol["username"]); ?></div><div class="am-u-sm-3"><p class="del" onclick="del(this,<?php echo ($vol["id"]); ?>);"><img src="../Style/index-images/shopcar_07.png" alt="删除"/></p><p class="time"><?php echo (date('m月d日',$vol["draw_time"])); ?></p></div></div></dt><?php endforeach; endif; else: echo "" ;endif; ?></dl><?php else: ?><!--没有记录的--><div class="no-re"><img src="../Style/index-images/activity/no-re.png" alt="没有记录"/><p>你还没开始抽奖耶~，赶快<span onclick="location.href='<?php echo U('Zhuangpan/index');?>'">试下手气</span>吧！</p></div><?php endif; ?></div><!--范票--><div id="con_one_2" style="display:none;"><?php if(!empty($list1)): ?><dl><?php if(is_array($list1)): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><dt style="background-color: white;"><div class="am-g"><div class="am-u-sm-2"><img src="../Style/index-images/activity/<?php echo ($vol["pic"]); ?>" alt="" class="am-img-responsive" style="height: 43px;"/></div><div class="am-u-sm-7"><p class="title">获得<?php echo ($vol["prize"]); ?></p><p class="content">已存入"会员中心"-"我的范票"</p></div><div class="am-u-sm-3"><p class="del" onclick="del(this,<?php echo ($vol["id"]); ?>);"><img src="../Style/index-images/shopcar_07.png" alt="删除"/></p><p class="time"><?php echo (date('m月d日',$vol["draw_time"])); ?></p></div></div></dt><?php endforeach; endif; else: echo "" ;endif; ?></dl><?php else: ?><!--没有记录的--><div class="no-re"><img src="../Style/index-images/activity/no-re.png" alt="没有记录"/><p>你还没开始抽奖耶~，赶快<span onclick="location.href='<?php echo U('Zhuangpan/index');?>'">试下手气</span>吧！</p></div><?php endif; ?></div><!--优惠券--><div id="con_one_3" style="display:none;"><?php if(!empty($list2)): ?><dl><?php if(is_array($list2)): $i = 0; $__LIST__ = $list2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><dt style="background-color: white;"><div class="am-g"><div class="am-u-sm-2"><img src="../Style/index-images/activity/<?php echo ($vol["pic"]); ?>" alt="" class="am-img-responsive" style="height: 43px;"/></div><div class="am-u-sm-7"><p class="title">获得<?php echo ($vol["prize"]); ?></p><p class="content">已存入"会员中心"-"优惠券"</p></div><div class="am-u-sm-3"><p class="del" onclick="del(this,<?php echo ($vol["id"]); ?>);"><img src="../Style/index-images/shopcar_07.png" alt="删除"/></p><p class="time"><?php echo (date('m月d日',$vol["draw_time"])); ?></p></div></div></dt><?php endforeach; endif; else: echo "" ;endif; ?></dl><?php else: ?><!--没有记录的--><div class="no-re"><img src="../Style/index-images/activity/no-re.png" alt="没有记录"/><p>你还没开始抽奖耶~，赶快<span onclick="location.href='<?php echo U('Zhuangpan/index');?>'">试下手气</span>吧！</p></div><?php endif; ?></div><!--实物--><div id="con_one_4" style="display:none;"><?php if(!empty($list3)): ?><dl><?php if(is_array($list3)): $i = 0; $__LIST__ = $list3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><dt style="background-color: white;"><div class="am-g"><div class="am-u-sm-2"><img src="../Style/index-images/activity/png/<?php echo ($vol["pic"]); ?>" alt="" class="am-img-responsive" style="height: 43px;"/></div><div class="am-u-sm-7"><p class="title">获得<?php echo ($vol["prize"]); ?></p><p class="content">我们会尽快邮寄到您手中</p></div><div class="am-u-sm-3"><p class="del" onclick="del(this,<?php echo ($vol["id"]); ?>);"><img src="../Style/index-images/shopcar_07.png" alt="删除"/></p><p class="time"><?php echo (date('m月d日',$vol["draw_time"])); ?></p></div></div></dt><?php endforeach; endif; else: echo "" ;endif; ?></dl><?php else: ?><!--没有记录的--><div class="no-re"><img src="../Style/index-images/activity/no-re.png" alt="没有记录"/><p>你还没开始抽奖耶~，赶快<span onclick="location.href='<?php echo U('Zhuangpan/index');?>'">试下手气</span>吧！</p></div><?php endif; ?></div></div></div><div class="showinfo animated shake"></div><!-- 	<link rel="stylesheet" type="text/css" href="../Style/css/animations.css"/><div data-am-widget="navbar" class="am-navbar am-cf am-navbar" id="" style="background-color:white; box-shadow:20px 20px 40px black;"><ul class="am-navbar-nav am-cf am-avg-sm-5"><li ><a href="<?php echo U('Index/index',array('shares'=>$info['id']));?>" class=""><img src="../Style/index-images/home.gif"><span class="am-navbar-label" style="color:rgb(137,137,137);">首页</span></a></li><li><a href="<?php echo U('Item/itemcate',array('shares'=>$info['id']));?>" class=""><img src="../Style/index-images/sort.gif" ><span class="am-navbar-label" style="color:rgb(137,137,137);">分类</span></a></li><li class="bottomhome"><a href='https://static.meiqia.com/dist/standalone.html?eid=23554&clientid=<?php echo ($server_u["id"]); ?>&metadata={"name":"<?php echo $server_u['username']; ?>","tel":"<?php echo $server_u['phone_mob']; ?>","email":"<?php echo $server_u['email']; ?>"}'><img src="../Style/index-images/erweima.png"><span class="am-navbar-label" style="color:rgb(137,137,137);">客服</span></a></li><li><a href="<?php echo U('Shopcart/index',array('shares'=>$info['id']));?>"><img src="../Style/index-images/shop-car.gif" ><span class="am-navbar-label" style="color:rgb(137,137,137);">购物车</span></a></li><li ><a href="<?php echo U('User/index',array('shares'=>$info['id']));?>" class=""><img src="../Style/index-images/mine.gif" ><span class="am-navbar-label" style="color:rgb(137,137,137);">我的</span></a></li></ul><img id="tixing" src="../Style/index-images/activity/99tixing.png" alt="满99" style="width:110px; position:fixed; right:18%; bottom:49px; z-index:800; display: none;"/></div><script type="text/javascript">
	$(function(){
		$("#tixing").show();
		$("#tixing").addClass("slideExpandUp");
		$("#tixing").delay(4500).fadeOut();
	})
</script> --><!--选项卡JS--><script type="text/javascript">
			function setTab(name,cursel){
				cursel_0=cursel;
				for(var i=1; i<=links_len; i++){
					var menu = document.getElementById(name+i);
					var menudiv = document.getElementById("con_"+name+"_"+i);
					if(i==cursel){
						menu.className="off";
						menudiv.style.display="block";
					}
					else{
						menu.className="";
						menudiv.style.display="none";
					}
				}
			}
			function Next(){                                                        
				cursel_0++;
				if (cursel_0>links_len)cursel_0=1
				setTab(name_0,cursel_0);
			} 
			var name_0='one';
			var cursel_0=1;
			
			var links_len,iIntervalId;
			onload=function(){
				var links = document.getElementById("tab1").getElementsByTagName('li')
				links_len=links.length;
				for(var i=0; i<links_len; i++){
					links[i].onmouseover=function(){
						clearInterval(iIntervalId);
						this.onmouseout=function(){
							iIntervalId = setInterval(Next,ScrollTime);;
						}
					}
				}
				document.getElementById("con_"+name_0+"_"+links_len).parentNode.onmouseover=function(){
					clearInterval(iIntervalId);
					this.onmouseout=function(){
						iIntervalId = setInterval(Next,ScrollTime);
					}
				}
				setTab(name_0,cursel_0);
				iIntervalId = setInterval(Next,ScrollTime);
			}
		</script><!--判断日期--><script>
			$(function() {
				var stime = $("#stime").val();
			    var etime = $("#etime").val();
				var sarr = stime.split('-');
				var earr = etime.split('-');
				
				var startDate = new Date(sarr[0],sarr[1]-1,sarr[2]);
				var endDate = new Date(earr[0],earr[1]-1,earr[2]);
				
				var $alert = $('#my-alert');
				$('#my-start').datepicker().
				on('changeDate.datepicker.amui', function(event) {
					if(event.date.valueOf() > endDate.valueOf()) {
						//$alert.find('p').text('开始日期应小于结束日期！').end().show();
						$(".showinfo").html('开始日期应小于结束日期！').show().delay(3000).fadeOut();
					} else {
						$alert.hide();
						startDate = new Date(event.date);
						$('#my-startDate').text($('#my-start').data('date'));
						$('#stime').val($('#my-start').data('date'));
					}
					$(this).datepicker('close');
				});
		
				$('#my-end').datepicker().
				on('changeDate.datepicker.amui', function(event) {
					if(event.date.valueOf() < startDate.valueOf()) {
						//$alert.find('p').text('结束日期应大于开始日期！').end().show();
						$(".showinfo").html('结束日期应大于开始日期！').show().delay(3000).fadeOut();
					} else {
						$alert.hide();
						endDate = new Date(event.date);
						$('#my-endDate').text($('#my-end').data('date'));
						$('#etime').val($('#my-end').data('date'));
					}
					$(this).datepicker('close');
				});
			});
			
			
		</script><script type="text/javascript" src="../Style/layer/layer.js" charset="utf-8"></script><script>
		//删除订单
		function del(obj,id){
			layer.open({
					title: '提示',
					content: '您确定要删除该中奖记录吗？',
					btn: ['确认', '取消'],
					yes: function(index){
						layer.close(index);
						var url="<?php echo U('Zhuangpan/delRecord',array('id'=>'" + id + "'));?>";
						$.post(url,{id:id},function(data){
							if(data=='true'){
								layer.open({content: '删除成功', time: 1});
								//location.reload();
								$(obj).parent().parent().parent().remove();
							}else{
								layer.open({content: '删除失败', time: 1});
							}
						});
						
					}
			});
		}
		
		function search(){
			var stime = $("#stime").val();
			var etime = $("#etime").val();
			location.href="<?php echo U('Zhuangpan/record');?>&stime="+stime+"&etime="+etime;
		}
		</script></body></html>