$(function(){
	
	//顶部导航
    var winHeight = $(document).scrollTop();
    $(window).scroll(function() {
        var scrollY = $(document).scrollTop();// 获取垂直滚动的距离，即滚动了多少

        if (scrollY > 350){ //如果滚动距离大于550px则隐藏，否则删除隐藏类
			$('.top-title').addClass('hiddened');
			//$('.top-title2').fadeIn(300);
		} 
        else {
			$('.top-title').removeClass('hiddened');
			//$('.top-title2').fadeOut(300); 
		}

        if (scrollY > winHeight){ //如果没滚动到顶部，删除显示类，否则添加显示类
			$('.top-title').removeClass('showed');
		} 
        else {
			$('.top-title').addClass('showed');
		}				

     });
	 
	//加入购物车动画
	$('.m-tip').bind('click', addCarts);
});

function addCarts(event) {
var htm = '<i class="am-icon-cart-plus" style="color:#FF3300;"></i>';
var size = $("input[name=size]").val();
if(size==""){
	return;
}
var offset = $('#end').offset(), flyer = $(htm);
	flyer.fly({
		start: {
			left: event.pageX,
			top: event.pageY
		},
		end: {
			left: offset.left,
			top: offset.top,
			width: 20,
			height: 20
		}
	});
}