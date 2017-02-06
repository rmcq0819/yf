<?php
/**
 * 百度数据统计图表echart的PHP实现类
 * 
 * 原作者： 
 * @author:   chenliujin  <liujin.chen@qq.com> 
 * @since    2013-12-12 
 *  
 * 修改者：
 * @author:   iamlintao   <http://www.iamlintao.com>
 * @since:    2014-06-25
 * @version:
 * @revision:
 * 
 *            修改后支持 柱形图(bar)、线形图(line)、饼形图(pie)
 * 
 * @example:
 * 
 *        HTML代码部分：
 *            <script src="js/esl.js"></script>
 *            <body>
<!-- 线形图、柱形图显示 -->
<div id="chartArea" style="height:300px;border:1px solid #ccc;padding:10px;"></div>
<!-- 饼形图显示 -->
<div id="pieArea" style="height:300px;border:1px solid #ccc;padding:10px;"></div>
</body>

PHP代码部分：

//  柱形图、线形图模拟数据
$option = array(
"legend"=>array("邮件营销","联盟广告","视频广告","直接访问","搜索引擎"),
"xaxis"=>array("type"=>"category","boundaryGap"=>"true","data"=>array("周一","周二","周三","周四","周五","周六","周日")),     
"series"=>array(
array("name"=>"邮件营销","type"=>"bar","stack"=>"总量","data"=>array("120","132","101","134","90","230","210")),                  
array("name"=>"联盟广告","type"=>"bar","stack"=>"总量","data"=>array("220","182","191","234","290","330","310")),             
array("name"=>"视频广告","type"=>"bar","stack"=>"总量","data"=>array("150","232","201","154","190","330","410")),             
array("name"=>"直接访问","type"=>"bar","stack"=>"总量","data"=>array("320","332","301","334","390","330","320")),                 
array("name"=>"搜索引擎","type"=>"bar","stack"=>"总量","data"=>array("820","932","901","934","1290","1330","1320")),          
),
);

$ec = new Echarts();
echo $ec->show('chartArea', $option);  // 显示在指定的dom节点上

// 饼形图模拟数据
$optionPie = array(
"legend"=>array("邮件营销","联盟广告","视频广告","直接访问","搜索引擎"),
"series"=>array(
array("name"=>"邮件营销","type"=>"pie","stack"=>"总量",
"data"=>array(
array("value"=>"335","name"=>"直接访问"),
array("value"=>"310","name"=>"邮件营销"),
array("value"=>"234","name"=>"联盟广告"),
array("value"=>"135","name"=>"视频广告"),
array("value"=>"1548","name"=>"搜索引擎"),
),
),
),
);

$ec = new Echarts();
echo $ec->show('pieArea', $optionPie); // 显示在指定的dom节点上
 */

class Echarts{
    
    public static function show($id, array $data,array $data1,$loging){
        
        $xaxis = "";
        $series = "";
        
        if (empty($data)) {           
            $data = array(
                'legend' => array(
                    'data' => array('-')
                ),
                'xaxis' => array(
                    'type' => 'category',
                    'boundaryGap' => 'false',
                    'data' => array('')
                ),
                'series' => array(
                    array(
                        'name' => '-',
                        'type' => 'line',
                        'itemStyle' => "{normal: {areaStyle: {type: 'default'}}}",
                        'data' => array()
                    ),
                )
            );
        }
        
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'tooltip':
                    foreach ($value as $k => $v) {
                        $tooltip[] = $k . ":'" . $v . "'";
                    }
                    if ($tooltip)
                    {
                    	$tooltip = '{' . implode(', ', $tooltip) . '}';
                        $tooltip='tooltip:'. $tooltip.',';
                    }
                    else
                    {
                        $tooltip='tooltip:{trigger: "axis"},';
                    }
                    break;
                case 'grid':
                    foreach ($value as $k => $v) {
                        $grid[] = $k . ":'" . $v . "'";
                    }
                    if ($grid)
                    {
                    	$grid = '{' . implode(', ', $grid) . '}';
                        $grid='grid:'. $grid.',';
                    }
                    break;
                case 'legend':
                    foreach ($value as $k => $v) {
                        $legend[] = json_encode($v);
                    }
                    $legend = 'data: [' . implode(', ', $legend) . '],';
					$legend .= "x:'left',";
					$legend .= "y:'-1'";
                    break;
                case 'title':
					foreach ($value as $k => $v) {
						switch ($k) {
                            case 'y':
                                $title[] = $k . ":'" . $v . "'";
                                break;
                            case 'x':
                                $title[] = $k . ":'" . $v."'";
                                break;
							case 'textStyle':
								foreach($v as $i => $j){
									$textStyle[] = $i . ":'" . $j."'";
								}
								$title[] = $k .':{' . implode(', ', $textStyle) . '}';
                            case 'text':
                                $title[] = $k . ':' . json_encode($v);
                                break;
                        }
					}	
					$title = '{' . implode(',', $title) . '}';
					break;
                case 'xaxis':
                    foreach ($value as $k => $v) {
                        switch ($k) {
                            case 'type':
                                $xaxis[] = $k . ":'" . $v . "'";
                                break;
                            case 'boundaryGap':
                                $xaxis[] = $k . ':' . $v;
                                break;
                            case 'data':
                                $xaxis[] = $k . ':' . json_encode($v);
                                break;
							case 'axisLabel':
                                foreach ($v as $key1 => $v1) {
                                    $text .= $key1.":".$v1.","; 
                                }
                                $xaxis[] = $k.":{".$text."}";
                                break;   	
                        }
                    }
                    $xaxis = '{' . implode(', ', $xaxis) . '}';
                    break;
                
                case 'series':
                    foreach ($value as $list) {
                        $tmp = array();
                        foreach ($list as $k => $v) {
                            switch ($k) {
                                case 'name':
                                case 'type':
                                    $tmp[] = $k . ":'" . $v . "'";
                                    break;
                                case 'itemStyle':
                                    $tmp[] = $k . ':' . $v;
                                    break;
                                case 'smooth';
                                    $tmp[] = $k . ':' . $v;
                                    break;
                                case 'symbol';
                                    $tmp[] = $k .':'. $v;   
                                case 'itemStyle';
                                    $tmp[] = $k .':'. $v;   
                                case 'data':
                                    $tmp[] = $k . ':' . json_encode($v);
                            }
                        }
                        $series[] = '{' . implode(', ', $tmp) . '}';
                    }
                    $series = implode(', ', $series);
                    break;
            }
        }
        if (!$tooltip)
        {
        	$tooltip='tooltip:{trigger: "axis"},';
        }
        foreach ($data1 as $key => $value) {
            switch ($key) {
                case 'tooltip':
                    foreach ($value as $k => $v) {
                        $tooltip1[] = $k . ":'" . $v . "'";
                    }
                    if ($tooltip1)
                    {
                    	$tooltip1 = '{' . implode(', ', $tooltip1) . '}';
                        $tooltip1='tooltip:'. $tooltip1.',';
                    }
                    else
                    {
                        $tooltip1='tooltip:{trigger: "axis"},';
                    }
                    break;
                case 'legend':
                    foreach ($value as $k => $v) {
                        $legend1[] = json_encode($v);
                    }
                    $legend1 = 'data: [' . implode(', ', $legend1) . ']';
                    break;
				case 'title':
					foreach ($value as $k => $v) {
						switch ($k) {
                            case 'y':
                                $title1[] = $k . ":'" . $v . "'";
                                break;
                            case 'x':
                                $title1[] = $k . ":'" . $v."'";
                                break;
                            case 'text':
                                $title1[] = $k . ':' . json_encode($v);
                                break;
                        }
					}	
					$title1 = '{' . implode(',', $title1) . '}';
					break;
                case 'grid':
                    foreach ($value as $k => $v) {
                        $grid1[] = $k . ":'" . $v . "'";
                    }
                    if ($grid1)
                    {
                    	$grid1 = '{' . implode(', ', $grid1) . '}';
                        $grid1='grid:'. $grid1.',';
                    }
                    break;
                case 'xaxis':
                    foreach ($value as $k => $v) {
                        switch ($k) {
                            case 'type':
                                $xaxis1[] = $k . ":'" . $v . "'";
                                break;
                            case 'boundaryGap':
                                $xaxis1[] = $k . ':' . $v;
                                break;
                            case 'data':
                                $xaxis1[] = $k . ':' . json_encode($v);
                                break;
                        }
                    }
                    $xaxis1 = '{' . implode(', ', $xaxis1) . '}';
                    break;
                
                case 'series':
                    foreach ($value as $list) {
                        $tmp = array();
                        foreach ($list as $k => $v) {
                            switch ($k) {
                                case 'name':
                                case 'type':
                                    $tmp1[] = $k . ":'" . $v . "'";
                                    break;
                                case 'itemStyle':
                                    $tmp1[] = $k . ':' . $v;
                                    break;
                                case 'data':
                                    $tmp1[] = $k . ':' . json_encode($v);
                            }
                        }
                        $series1[] = '{' . implode(', ', $tmp1) . '}';
                    }
                    $series1 = implode(', ', $series1);
                    break;
            }
        }
        if (!$tooltip1)
        {
        	$tooltip1='tooltip:{trigger: "axis"},';
        }
        $script;
        if ($loging==0)
        {
            $script = <<<eof
            <script type="text/javascript">
            // Step:3 conifg ECharts's path, link to echarts.js from current page.
            // Step:3 为模块加载器配置echarts的路径，从当前页面链接到echarts.js，定义所需图表路径
 
            // 把所需图表指向单文件
            require.config({
            paths: {
                echarts: '/static/js/echarts'
            }
             });
 
            // Step:4 require echarts and use it in the callback.
            // Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
 
            // 按需加载所需图表
            require(
                [
                    'echarts',
                    'echarts/chart/bar',
                    'echarts/chart/line',
                   'echarts/chart/pie'
                ],
                function(ec) {
                    var myChart = ec.init(document.getElementById('$id'));
                    var option = {
						title:$title,
                        $tooltip
                        $grid
                       legend: {
                            $legend
                       },
                       toolbox: {
                           show: false,
                           feature: {
                               mark: {
                                   show: true
                               },
                               dataView: {
                                   show: true,
                                   readOnly: true
                               },
                               magicType: {
                                   show: false,
                                   type: ["line", "bar"]
                               },
                               restore: {
                                   show: true
                               },
                               saveAsImage: {
                                   show: true
                               }
                           }
                       },
                       xAxis: [$xaxis],
                       yAxis: [{type : 'value'}],
                       series: [$series]
                    }
                    myChart.setOption(option);
                }
            );
            </script>
eof;
        }
        else
        {
            $script = <<<eof
            <script type="text/javascript">
            // 把所需图表指向单文件
            require.config({
            paths: {
                echarts: '/static/js/echarts'
            }
             });
            require(
                [
                    'echarts',
                    'echarts/chart/bar',
                    'echarts/chart/line',
                   'echarts/chart/pie'
                ],
                DrawCharts
                
            );
            function DrawCharts(ec) {
                FunDraw1(ec);
                FunDraw2(ec);
            }
            function FunDraw1(ec) {
                       var myChart$id = ec.init(document.getElementById('$id'));
                    var option$id = {
					/* title:$title, */
                        $tooltip
                        toolbox: {
                           show: false,
                           feature: {
                               mark: {
                                   show: true
                               },
                               dataView: {
                                   show: true,
                                   readOnly: true
                               },
                               magicType: {
                                   show: false,
                                   type: ["line", "bar"]
                               },
                               restore: {
                                   show: true
                               },
                               saveAsImage: {
                                   show: true
                               }
                           }
                       },
                       legend: {
                            $legend
                       },
                       xAxis: [$xaxis],
                       yAxis: [{type : 'value'}],
                       series: [$series]
                    }
 
                    myChart$id.setOption(option$id);
                }
                function FunDraw2(ec) {
                       var myChart = ec.init(document.getElementById('js_turnover'));
                    var option = {
                         $tooltip1
                       $grid1
                       xAxis: [$xaxis1],
                       yAxis: [{type : 'value'}],
                       series: [$series1]
                    }
 
                    myChart.setOption(option);
                }
            </script>
eof;
        }
        
       
        
        return $script;
    }
}