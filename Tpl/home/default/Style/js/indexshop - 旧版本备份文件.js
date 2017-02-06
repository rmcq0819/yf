$(function(){
	/* 商品列表页状态商品切换视图 */
 	$('.prorenqimain .prorenqi').click(function(){ 
		$(this).find('a').addClass("onselect");
		$(this).siblings().find('a').removeClass();
		
		//状态值准备
		sta = $(this).attr('id');
		cate_id = $("input[name='cate_id']").val();
		type = $("input[name='type']").val(); //0属于分类请求，1属于全部请求
		
		if(sta=='p'){
			c = $(this).find('span').attr('class');
			if(c=='am-icon-caret-up'){
				sta = 'pdesc';
			}
			if(c==''||c=='am-icon-caret-down'){
				sta = 'pasc';
			}
		}
		//发送请求
		url = './index.php?m=Item&a=goodsList';
		$.ajax({
			type:'get',
			url:url,
			data:'cate_id='+cate_id+'&sta='+sta+'&type='+type,
			beforeSend:function(){
				$('.load').show();
			},
			success:function(data){
				//处理返回的json数据
				if(data=='0'){
					//无数据处理
					$("#list").html("");
					$("#list").html($("#list").html()+'<div class="nolist">没有找到相关商品~~</div>');
				}else{
					$("#list").html("");
					json = eval("("+data+")");
					$.each(json, function (index, item) {
						//列表模板
						var title = json[index].title;
						if(title.length>13){
							var txt = title.substr(0,12)+"...";//截取从首个字符开始的15个字符  
						}else{
							var txt = title;
						}
						
						htm = '<li class="am-thumbnail">';
						if(json[index].itemtype==0){		
							htm =htm+ '<img src="./Tpl/home/default/Style/images/baosui.png" style="position:absolute;width:30px;" />';							
						} 
						htm =htm+ '<a href="./index.php?m=Item&a=index&id='+json[index].id+'"><img src="data/upload/item/'+json[index].img+'"><div style="width:100%; height:65px;"><div style="width:100%; padding:4px 2px 2px 2px; border-bottom:1px dashed #ccc;"><b style="font-size:14px; color:#FF0000;">¥'+json[index].price+'</b>';
						if(json[index].priceyuan!=0){
							htm =htm+'<s style="font-size:10px; color:#0183D7;">¥'+json[index].priceyuan+'</s>';
						}
						htm =htm+ '<a style="float:right;font-size:13px;">销量'+json[index].buy_num+'</a></div><div style="padding:4px 2px 2px 2px;">'+txt+'</div></div></a></li>';
						$("#list").html($("#list").html() + htm); 
					});
				}
			},
			complete:function(){
				$('.load').hide();
			}
			
		});

		
	}); 
	
	$("#p").click(function(){
		c = $(this).find('span').attr('class');
		if(c==""){
			$(this).find('span').addClass('am-icon-caret-up');
		}
		if(c=="am-icon-caret-up"){
			$(this).find('span').removeClass('am-icon-caret-up');
			$(this).find('span').addClass('am-icon-caret-down');
		}
		if(c=="am-icon-caret-down"){
			$(this).find('span').removeClass('am-icon-caret-down');
			$(this).find('span').addClass('am-icon-caret-up');			
		}

	});
	
	/*分类页面搜索效果*/
	$("#indexsreach").click(function(){
		//显示搜索页面
		$(".sreach").slideDown('1500');
		$(document.body).css("overflow","hidden");
		
	});
	
	$("#sreach").focus(function(){
		//显示搜索页面
		$(".sreach").slideDown('1500');
		$(document.body).css("overflow","hidden");
	});
	$("#back").click(function(){
		//收起搜索页面
		$(".sreach").slideUp('1500');
		$(document.body).css("overflow","auto");
	});
	
	/*ajax搜索分类+产品*/
	$("#keyword").focus(function(){
		$(this).val("");
	}).blur(function(){
		$("#itemlist").html("");
		//获取文本框的值
		var keyword = $.trim($(this).val());
		//如果文本框的值不为空，则调用Ajax发送相关数据
		if($.trim(keyword)!=""){
			//商品名称
			var url =  "./index.php?m=Item&a=itemsearch";
			$.ajax({
				type:"get",
				url:url,
				data:"keyword="+keyword,
				datatype:"json",
				success: function(msg){
					if(msg==0){
						$("#itemlist").html($("#itemlist").html()+'<li>暂无搜索结果</li>');
					}
					json = eval("("+msg+")");
					$.each(json, function (index, item) {
						//$("#itemlist").html($("#itemlist").html()+'<li><a href="./index.php?m=Item&a=index&id='+json[index].id+'">'+json[index].title+'</a></li>');
						
						htm='<li class="pdt_ser_li am-thumbnail">';
						

						
						htm=htm+'<a href="./index.php?m=Item&a=index&id='+json[index].id+'"><img src="data/upload/item/'+json[index].img+'" >';
						if(json[index].itemtype==0){
							htm=htm+'<p style="color:red;">保税产品</p>';
						}
						htm=htm+'<div style="width:100%; height:65px;"><div style="width:100%; padding:4px 2px 2px 2px; border-bottom:1px dashed #ccc;"><b style="font-size:14px; color:#FF0000;">¥'+json[index].price+'</b>';
						
						if(json[index].priceyuan!=0){
							htm=htm+'<s style="font-size:10px; color:#0183D7;">¥'+json[index].priceyuan+'</s>';
						}
						
						var title = json[index].title;
						if(title.length>13){
							var txt = title.substr(0,12)+"...";//截取从首个字符开始的15个字符  
						}else{
							var txt = title;
						}
						
						htm=htm+'<a style="float:right;font-size:13px;">销量'+json[index].buy_num+'</a></div><div style="padding:4px 2px 2px 2px;">'+txt+'</div></div></a></li>';
						
						$("#itemlist").html($("#itemlist").html()+htm);
					
					});
				}
			});
			
			//商品分类
			/*var url =  "./index.php?m=Item&a=catesearch";
			$.ajax({
				type:"get",
				url:url,
				data:"keyword="+keyword,
				datatype:"json",
				success: function(msg){
					json = eval("("+msg+")");
					$.each(json, function (index, item) {
						$("#itemlist").append('<li><a href="./index.php?m=Item&a=itemlist&id='+json[index].id+'">'+json[index].name+'</li>');
					});
				}
			});*/
			
		}
		
	});	
	
	/*商品分类选择*/
	$('.proshumainlei a').click(function(){
		$(this).addClass('hongbian').siblings().removeClass();
		//获取当前分类标识，显示不同分类下的价格，库存
		var i = $(this).children('input').val();
		$('#yhprice').text($('input[name=yhprice'+i+']').val());
		$('#goods_stock').text($('input[name=goods_stock'+i+']').val());
		
		if($('input[name=goods_stock'+i+']').val()<=0){
			
			$("#goods_stock_msg").html('宝贝太火,已售空,正在补仓中!');
			
		} 
		
		//赋值隐藏域，提供购物规格
		$('input[name=size]').val($(this).text());
		$('input[name=yhprice]').val($('input[name=yhprice'+i+']').val());
		$('#goods_stock').text($('input[name=goods_stock'+i+']').val());
		$('input[name=goods_stock]').val($('input[name=goods_stock'+i+']').val());
		$('input[name=cost]').val($('input[name=cost'+i+']').val());
		
	});
	
	/*商品内页tab切换*/
	$('.proneitimain li').click(function(){ 
		$(this).addClass("am-active").siblings().removeClass();
		$(".am-tabs-bd > div").slideUp('1000').eq($('.proneitimain li').index(this)).slideDown('1000');
	}); 
	
});
