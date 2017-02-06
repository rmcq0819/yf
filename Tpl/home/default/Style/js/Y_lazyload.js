/**
 * ���� jQuery ͼƬ������
 * @author  ����
 * Email    yybawang@sina.com
 */
(function ($) {
    $.fn.Y_lazyload = function (options) {
    /**
    *   option ��Ĭ������˵��
    *
    *   event           //����img�Ĵ����¼�
    *   img             //������ img ��dom
    *   real_src        //Ҫ������ʵ src ʹ�õ����Զ�������(attr)
    *   animate         //����Ч��
    *   animate_delay   //�����ȴ�ʱ�䣬�ȴ�ͼƬ�������
    *   animate_time    //����ִ��ʱ��
    *   time_out        //�����ӳټ��أ���ʱIE�����̫����и���ͼƬ�޷�Ӧ����������ô���ʱ�Ϳ��Խ��
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
        //����д�Լ��ķ���
        var client_h = 0;   //��������������
        if (window.innerHeight) 
            client_h = window.innerHeight; 
        else if ((document.body) && (document.body.clientHeight)) 
            client_h = document.body.clientHeight;
        //��װ���������ڶ�ε���
        var foreach_img = function(){
            $(options.img).each(function(){
                var dom = $(this);
                //Ԫ�� offset ��
                var offset_top = $(this).offset().top;
                //������ѻ����ĸ߶�
                var scroll_height = client_h + $(document).scrollTop();
                //����������������
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
                 
                    return true;    //��������ѭ��
                }
                return false;       //�˳�ѭ��
            });
        };
         
        //ҳ��������ִ��һ��
        setTimeout(function(){
            foreach_img();
        },options.time_out);
        //�󶨴����¼�
        $(this).bind(options.event,function(){
            setTimeout(function(){foreach_img();},options.time_out);
        });
         
    }); 
    }
})(jQuery);