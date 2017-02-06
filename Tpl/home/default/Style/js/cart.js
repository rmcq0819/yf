function drop_cart_item(rec_id){
	
    var tr = $('#cart_item_' + rec_id);
    var amount_span = $('#cart_amount');
    var cart_goods_kinds = $('#cart_goods_kinds');
   
    $.post("index.php?m=Shopcart&a=remove_cart_item",{itemId:rec_id},function(data){
    	if(data.status==1)
    	{
    		window.location.reload(); 
    	}
    },'json');
    
    /*
    $.getJSON('index.php?app=cart&act=drop&rec_id=' + rec_id, function(result){
        if(result.done){
            //删除成功
            if(result.retval.cart.quantity == 0){
                window.location.reload();    //刷新
            }
            else{
                tr.remove();        //移除
                amount_span.html(price_format(result.retval.amount));  //刷新总费用
                cart_goods_kinds.html(result.retval.cart.kinds);       //刷新商品种类
            }
        }
    });
    */
}
function move_favorite(store_id, rec_id, goods_id){
    var tr = $('#cart_item_' + rec_id);
    $.getJSON('index.php?app=my_favorite&act=add&type=goods&item_id=' + goods_id, function(result){
        //没有做收藏后的处理，比如从购物车移除
        if(result.done){
            //drop_cart_item(store_id, rec_id);
            alert(result.msg);
        }
        else{
            alert(result.msg);
        }

    });
}
function change_quantity(rec_id,seid,input,size){
	
    var subtotal_span = $('#item' + seid + '_subtotal');
    var amount_span = $('#cart_amount');
    //暂存为局部变量，否则如果用户输入过快有可能造成前后值不一致的问题
    var _v = input.value;
  // alert($(input).attr('changed')); 
  
  if(isNaN(input.value)||input.value<1) 
  {
  	alert('请输入大于0的数字');
  	 //$(input).attr('changed', _v);
  	 $(input).val($(input).attr('changed'));
  	 return false;
  }
  
   $.post("index.php?m=Shopcart&a=change_quantity",{itemId:rec_id,seid:seid,quantity:_v,size:size},function(data){
 
    	if(data.status==1)
    	{
		//subtotal_span.html(price_format(data.item.price*data.item.num*data.discount));
    	//var price = round(paresFloat(data.item.price),2);
    	 subtotal_span.html(price_format((data.item.price*data.item.num).toFixed(2)));




        /************************
        *   总价的显示否改为由选中否控制
        *   @author  May
        *   date    2016-07-26      
        ************************/
        //amount_span.html(price_format(data.sumPrice)); 
        ids = $("input:checkbox[name='id']:checked").map(function(index,elem) {
            return $(elem).val();
        }).get().join(',');
        idArr = ids.split(",");
        len = idArr.length;
        if(idArr == ''){
            len = idArr.length-1;
        }
        var price = 0;
        var i=0;
        for(;i<len;i++){
            price += parseFloat($("#price_"+idArr[i]).text())*$("#input_item_"+idArr[i]).val();
        }
        if(price<99&&price>0){
			document.getElementById("cart_amount").innerHTML = "￥"+price+"+￥10（运费）";
		}else{
			document.getElementById("cart_amount").innerHTML = "￥"+price;
		}



         $(input).attr('changed',_v);
    	}else
    	{
    		alert(data.msg);
    		 $(input).val($(input).attr('changed'));
    		return false;
    	}
    },'json');
   
    /*
    $.getJSON('index.php?app=cart&act=update&spec_id=' + spec_id + '&quantity=' + _v, function(result){
        if(result.done){
            //更新成功
            $(input).attr('changed', _v);
            subtotal_span.html(price_format(result.retval.subtotal));
            amount_span.html(price_format(result.retval.amount));
        }
        else{
            //更新失败
            alert(result.msg);
            $(input).val($(input).attr('changed'));
        }
    });
    */
}
function decrease_quantity(rec_id){
    var item = $('#input_item_' + rec_id);
    var orig = Number(item.val());
    if(orig > 1){
        item.val(orig - 1);
        item.attr('changed',orig);
        item.keyup();
    }
}
function add_quantity(rec_id){
    var item = $('#input_item_' + rec_id);
    var orig = Number(item.val());
    item.attr('changed',orig);
    item.val(orig + 1);
    item.keyup();
}