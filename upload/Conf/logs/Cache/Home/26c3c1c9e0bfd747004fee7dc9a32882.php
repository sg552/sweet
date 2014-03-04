<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>资费说明－<?php echo C('site_title');?></title>
<meta name="keywords" content="微信帮手 微信公众账号 微信公众平台 微信公众账号开发 微信二次开发 微信接口开发 微信托管服务 微信营销 微信公众平台接口开发"/>
<meta name="description" content="微信公众平台接口开发、托管、营销活动、二次开发"/>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/index.css"/>
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/ie6.css"/>
<![endif]-->
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo RES;?>/js/lang.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/config.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/page.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/jquery.lazyload.js"></script>
<script type="text/javascript">
GoMobile('');
var searchid = 5;
</script>
</head>
<body>
<div class="topbg">
<div class="top">
<div class="toplink">
<div class="welcome">欢迎使用<?php echo C('SITE_NAME');?>多用户微信营销服务平台!</div>
    <div class="memberinfo"  id="destoon_member">	
		<?php if($_SESSION[uid]==false): ?>你好&nbsp;&nbsp;<span class="f_red">游客</span>&nbsp;&nbsp;
			<a href="<?php echo U('Index/login');?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href="<?php echo U('Index/login');?>">注册</a>
			<?php else: ?>
			你好,<a href="/#" hidefocus="true"  ><span style="color:red"><?php echo (session('uname')); ?></span></a>（uid:<?php echo (session('uid')); ?>）
			<a href="/#" onclick="Javascript:window.open('<?php echo U('System/Admin/logout');?>','_blank')" >退出</a><?php endif; ?>	
	</div>
</div>
    <div class="logo">
        <div style="float:left"></div>
            <h1><a href="/" title="多用户微信营销服务平台"><img src="<?php echo RES;?>/images/logo-pigcms.png" /></a></h1>
            <div class="navr">
        <ul id="topMenu">           
			 <li <?php if((ACTION_NAME == 'index') and (GROUP_NAME == 'Home')): ?>class="menuon"<?php endif; ?> ><a href="/" >首页</a></li>
                <li <?php if((ACTION_NAME) == "fc"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/fc');?>">功能介绍</a></li>
                <li <?php if((ACTION_NAME) == "about"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/about');?>">关于我们</a></li>
                <li <?php if((ACTION_NAME) == "price"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/price');?>">资费说明</a></li>
                <li <?php if((ACTION_NAME) == "common"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/common');?>">微信导航</a></li>
                <li <?php if((GROUP_NAME) == "User"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('User/Index/index');?>">管理中心</a></li>
                <li <?php if((ACTION_NAME) == "help"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/help');?>">帮助中心</a></li>
            
            </ul>
        </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style-price.css"/>
<div class="banner jbanner"></div>
<div class="main">
   <div class="pos">当前位置: <a href="<?php echo C('site_url');?>"><?php echo C('site_name');?></a> &raquo; <a href="<?php echo C('site_url');?>">资费说明</a></div>
<div class="abody">
             <div class="qtcontent">
        <div class="document faq">
            <div class="normalTitle"><h2>资费</h2></div>
            <div class="normalContent">
                <div class="section lastSection">
                	<table width="100%" border="0" cellpadding="0" cellspacing="0" class=" ListProduct8">
<thead>
                			<tr>
                				<th class="lefttitle"><strong>微信号流量套餐</strong></th>
                				<th width="100">vip0</th>
                				<th width="100">vip1</th>
                				<th width="100">vip2</th>
                				<th width="100">vip3</th>
                				<th width="100">vip4</th>
                				<th width="100">vip5</th>
                				<th width="100" class="norightborder">vip6</th>
               				</tr>
</thead>
<tbody>
                			<tr>
                				<td height="60" valign="middle" class="lefttitle">VIP价格
                					<a  class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p>VIP只是流量套餐（自定义数和赠送的请求数不同而已），点击问号查看详细购买流程！</p>
</span></a></td>
                				<td><span class="price">1<p>元 / 月</p></span></td>
                				<td><span class="price">30<p>元 / 月</p></span></td>
                				<td><span class="price">50<p>元 / 月</p></span></td>
                				<td><span class="price">100<p>元 / 月</p></span></td>
                				<td><span class="price">150<p>元 / 月</p></span></td>
                				<td><span class="price">200<p>元 / 月</p></span></td>
                				<td class="norightborder"><span class="price">300<p>元 / 月</p></span></td>
               				</tr>
                			<tr>
                				<td height="32" class="lefttitle">自定义条数限额 <span class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p><strong>什么是自定义限额数？</strong></p>
<p>自定义分：自定义文本、自定义图文、自定义语音。意思是，你在pigcms写一个图文就少一个自定义图文〔vip0图文、文本、语音都有2000自定义，够挥霍了。〕</p>
</span></span></td>
                				<td>2000</td>
                				<td>3000</td>
                				<td>5000</td>
                				<td>10000</td>
                				<td>15000</td>
                				<td>20000</td>
                				<td class="norightborder">30000</td>
               				</tr>
                			<tr>
                				<td height="32" class="lefttitle">赠送月请求次数 <span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>什么是请求数？</strong></p>
<p>用户发送一句话，就代表一个请求。
比如：用户发送 "你好！"，系统回复"你也好！"
这就算一个请求，如果系统没回复，则不计。
〔温馨提示：<?php echo C('site_name');?>3G功能〔分类功能〕请求也算在内。3G请求看不到，只是粉丝在浏览里3G网站时候，浏览一篇文章，或者重新打开一个分类就算一个请求。目前3G功能只统计请求并不收费。〕</p>
<p><strong>什么是赠送请求？</strong></p>
<p>用户购买VIP流量套餐后会赠送用户一些功能和请求数，这个请求数被<?php echo C('site_name');?>称为赠送请求数。</p>
</span></span></td>
                				<td>10000</td>
                				<td>150000</td>
                				<td>300000</td>
                				<td>700000</td>
                				<td>1000000</td>
                				<td>2000000</td>
                				<td class="norightborder">3500000</td>
               				</tr>
                			<tr>
             
                		
               				</tr>
                            <tr >
                				<td height="50" class="lefttitle">每月免收活动创建费次数<span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>什么是活动创建费？</strong></p>
<p>创建1次活动的基础费是10元,这就是活动创建费。免收活动创建费就是免10元，其他资源消耗还是要正常扣费（如：SN码费用和实际参与抽奖人数的费用）!</p>
</span></span></td>
                				<td>0次</td>
                				<td>1次</td>
                				<td>2次</td>
                				<td>3次</td>
                				<td>4次</td>
                				<td>5次</td>
                				<td class="norightborder">6次</a></td>
               				</tr>
                             <tr >
                				<td height="50" class="lefttitle">底部版权信息<span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>版权信息？</strong></p>
<p>	V0 页面有:此页面是由【<a href="<?php echo C('site_url');?>"><?php echo C('site_name');?>接口平台</a>】系统生成 版权信息</p>
</span></span></td>
                				<td>有</td>
                				<td>无</td>
                				<td>无</td>
                				<td>无</td>
                				<td>无</td>
                				<td>无</td>
                				<td class="norightborder">无</a></td>
               				</tr>
                			<tr >
                				<td height="50" class="lefttitle"> <span class="red">先充值，在购买VIP套餐。</span><span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>简单购买流程提醒</strong></p>
<p></p>
</span></span></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>0));?>"><em>立即充值</em></a></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>1));?>"><em>立即充值</em></a></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>2));?>"><em>立即充值</em></a></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>3));?>"><em>立即充值</em></a></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>4));?>"><em>立即充值</em></a></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>5));?>"><em>立即充值</em></a></td>
                				<td><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>6));?>"><em>立即充值</em></a></td>
                				
                				
               				</tr>
                			<tr>
                				<td height="36" class="lefttitle"><strong>基础功能</strong></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td class="norightborder"></td>
               				</tr>
                			<tr>
                				<td class="lefttitle">天气</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">查快递</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                                <tr>
                				<td class="lefttitle">股票查询</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">翻译</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">百科</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">百度问答</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">人品计算</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">手机归属地查询</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">身份证查询</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">糗事</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">笑话</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">成语字典</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">谜语</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">成语接龙</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">诗歌接龙</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">藏头藏尾诗</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">诗歌赏析</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">解梦</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">号码吉凶</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">健康指数计算器</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">公交查询</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">火车查询</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">电影检索</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">网络音乐搜索</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">今日体彩</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">陪聊纯洁版</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">小黄鸡中文陪聊</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">中文过滤敏感词汇</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">小黄鸡英文版</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr >
                				<td class="lefttitle">音英语4-6级(2012-12月)</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>
<td class="lefttitle">语音播报天气</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">机器人学习功能</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">文字转语音</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr >
                				<td class="lefttitle">黄金白银期货</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>

<tr>
                				<td class="lefttitle">翻译朗读开关</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">路况查询</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">步行导航</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">驾车导航</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">周边生活地图版</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">公交换乘地图版</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">发地理位置直接显示周边</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">自定义LBS数据</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">文本回复模糊匹配</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">图文回复包含匹配</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">回答不上启用第三方接口</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr >
                				<td class="lefttitle">第三方接口优先支持LBS</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
<tr>

<td class="lefttitle">第三方接口优先(无触发词)</td>
                				<td class="error ">&nbsp;</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>



                			<tr>
                				<td height="36" class="lefttitle"><strong>高级功能（需要单独收费）</strong></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td class="norightborder"></td>
               				</tr>
                			<tr>
                				<td class="lefttitle">刮刮卡活动</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle"><a class="green"  href="###">大转盘活动</a> <a class="tooltips" href="###"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p>大转盘活动收费详情。</p>
</span></a></td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle"><a class="green"  href="###">优惠券活动</a> <a class="tooltips" href="###"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p>优惠券活详情。</p>
</span></a></td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>

<tr>
                				<td class="lefttitle">投票活动 <span class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p>投票活动需要单独收费，</p></span></span></td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">会员卡管理</td>
                				<td class="error">&nbsp;</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td class="norightborder">待定</td>
               				</tr>
                			<tr>
                				<td class="lefttitle">店铺管理</td>
                				<td class="error">&nbsp;</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td class="norightborder">待定</td>
               				</tr>
                			<tr>
                				<td class="lefttitle"><a class="green"  href="###">第三方接口融合</a> <a class="tooltips" href="###"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>

</span></a></td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>

                			<tr>
                				<td class="lefttitle">其他功能（待定）</td>
                				<td class="error">&nbsp;</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td>待定</td>
                				<td class="norightborder">待定</td>
               				</tr>
                			<tr>
                				<td height="36" class="lefttitle"></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td class="norightborder"></td>
               				</tr>
                			<tr>
                				<td class="lefttitle">技术指导</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">3G网站</td>
                				<td class="checked " >&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">自定义语音</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">自定义图文</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">自定义文本</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">文本导入导出</td>
                				<td class="error ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder ">&nbsp;</td>
                				</tr>
                			<tr>
                				<td class="lefttitle">图文导入导出</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
                                <tr>
                				<td class="lefttitle">语音导入导出</td>
                				<td class="error">&nbsp;</td>
                				<td class="error">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked ">&nbsp;</td>
                				<td class="checked norightborder">&nbsp;</td>
                				</tr>
</tbody>
               			</table>
                </div>
            <div class="section lastSection">
<p>有疑问的请QQ<?php echo C('site_qq');?>提问。</p>
               		</div>
            </div>
        </div>
    </div>
    </div>
    </div>
<script type="text/javascript">try{Dd('webpage_6').className='left_menu_on';}catch(e){}</script>
</div>
<div class="IndexFoot" style="height:120px;">
<div class="foot">
<div class="foot_page">
<a href="<?php echo C('site_url');?>"><?php echo C('SITE_NAME');?>,微信公众平台营销源码</a><br/>
帮助您快速搭建属于自己的营销平台,构建自己的客户群体。<br/>
大转盘、刮刮卡，会员卡,优惠卷,订餐,订房等营销模块,客户易用,易懂,易营销。
</div>
<div id="copyright">
	<?php echo C('site_name');?>(c) 版权所有<br/>
	QQ咨询：<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo C('site_qq');?>&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo C('site_qq');?>:51" alt="联系我吧" title="联系我吧"/></a>

</div>
    </div>
</div>
<div style="display:none">
<?php echo base64_decode(C('countsz'));?>
</div>
</body>
</html>