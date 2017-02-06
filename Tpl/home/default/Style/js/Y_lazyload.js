/**
 * 基于 jQuery 图片懒加载
 * @author  晏勇
 * Email    yybawang@sina.com
 */
(function ($) {
    $.fn.Y_lazyload = function (options) {
    /**
    *   option 的默认属性说明
    *
    *   event           //加载img的触发事件
    *   img             //随后加载 img 的dom
    *   real_src        //要加载真实 src 使用到的自定义属性(attr)
    *   animate         //动画效果
    *   animate_delay   //动画等待时间，等待图片下载完成
    *   animate_time    //动画执行时间
    *   time_out        //设置延迟加载，有时IE下如果太快会有个别图片无反应的情况，设置此延时就可以解决
    **/
    var defaults = {
        event : 'scroll',
        img : 'img[real_src]',
        real_src : 'real_src',
        animate : "",
        animate_delay : 500,
        animate_time : 1000,
        time_out : 0
    }; 
    var options = $.extend(defaults, options); 
    return this.each(function(){
        //这里写自己的方法
        var client_h = 0;   //浏览器可视区域高
        if (window.innerHeight) 
            client_h = window.innerHeight; 
        else if ((document.body) && (document.body.clientHeight)) 
            client_h = document.body.clientHeight;
        //封装函数，便于多次调用
        var foreach_img = function(){
            $(options.img).each(function(){
                var dom = $(this);
                //元素 offset 高
                var offset_top = $(this).offset().top;
                //计算出已划过的高度
                var scroll_height = client_h + $(document).scrollTop();
                //如果在浏览器可视内
                if(offset_top <= scroll_height){
                    var find_img = false;
                     
                    $(this).prop("src",$(this).attr(options.real_src));
                    $(this).removeAttr(options.real_src);
                    switch(options.animate){
                        case 'fadeIn' : 
                        $(this).css({"opacity":"0"}).delay(options.animate_delay).animate({"opacity":"1"},options.animate_time);break;
                        case 'slideDown' : 
                        $(this).css({"height":"0px","background":"url('.')"}).delay(options.animate_delay).animate({"height":$(this).height()+"px"},options.animate_time);break;
                        default : break;
                    }
                 
                    return true;    //返回重新循环
                }
                return false;       //退出循环
            });
        };
         
        //页面加载完就执行一次
        setTimeout(function(){
            foreach_img();
        },options.time_out);
        //绑定触发事件
        $(this).bind(options.event,function(){
            setTimeout(function(){foreach_img();},options.time_out);
        });
         
    }); 
    }
})(jQuery);