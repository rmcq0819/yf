$(function(){

	
	/*分类页面搜索效果*/
	$("#indexsreach").click(function(){
		//显示搜索页面

		$(".sreach").slideDown('1500');
		$(document.body).css("overflow","hidden");
		$("#keyword").focus();
		
	});
	
	$("#sreach").focus(function(){
		//显示搜索页面
		$(".sreach").slideDown('1500');
		$(document.body).css("overflow","hidden");
		$("#keyword").focus();
	});
	$("#back").click(function(){
		//收起搜索页面
		$(".sreach").slideUp('1500');
		$(document.body).css("overflow","auto");
	});
	
	/*ajax搜索分类+产品*/
	$("#keyword").focus(function(){
		$(this).val("");
	});
	$("#sousou").click(function(){
		$("#itemlist").html("");
		//获取文本框的值
		//var keyword = $.trim($(this).val());
		var keyword = $.trim($("#keyword").val());//2016-04-20 by shuguang

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
		$('#actprice').text($('input[name=actprice'+i+']').val());
		$('#goods_stock').text($('input[name=goods_stock'+i+']').val());
		
		if($('input[name=goods_stock'+i+']').val()<=0){
			$("#goods_stock_msg").html('宝贝太火，已售罄，正在补仓中！');
			$("#no_stock").fadeIn(300);
			$("#yes_cart").hide();
			$("#yes_pay").hide();
		}else{
			$("#goods_stock_msg").html('');
			$("#no_stock").hide();
			$("#yes_cart").show();
			$("#yes_pay").show();
		} 
		
		//赋值隐藏域，提供购物规格
		$('input[name=size]').val($(this).text());
		$('input[name=yhprice]').val($('input[name=yhprice'+i+']').val());
		$('#goods_stock').text($('input[name=goods_stock'+i+']').val());
		$('input[name=goods_stock]').val($('input[name=goods_stock'+i+']').val());
		$('input[name=cost]').val($('input[name=cost'+i+']').val());
		$('input[name=price]').val($('input[name=price'+i+']').val());
		$('input[name=aprice]').val($('input[name=aprice'+i+']').val());
		$('input[name=actprice]').val($('input[name=actprice'+i+']').val());
		var img = $('input[name=size_imgs'+i+']').val();
		if(img != ''){
			$('#ab li').html("<li><img src='data/upload/item_size/"+img+"'></li>");
		}
		
	});
	
	/*商品内页tab切换*/
	$('.proneitimain li').click(function(){ 
		$(this).addClass("am-active").siblings().removeClass();
		$(".am-tabs-bd > div").slideUp('1000').eq($('.proneitimain li').index(this)).slideDown('1000');
	}); 
	
});
