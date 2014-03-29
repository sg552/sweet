<?php
class CardAction extends BaseAction{
	public $wecha_id;
	public $thisUser;
	public function __construct(){
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}
		$this->wecha_id=$this->_get('wecha_id');
		$this->assign('wecha_id',$this->wecha_id);
		//
		$this->token=$this->_get('token');
		$this->thisUser = M('Userinfo')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->wecha_id))->find();
	}
	public function index(){
		//transfer start
		$data=M('Member_card_create');
		$cardByToken=M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->find();
		$data->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_exchange')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_coupon')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_vip')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_integral')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		//transfer end
		$member_create_db=M('Member_card_create');
		//
		$cards=$member_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
		$cardsByID=array();
		if ($cards){
			foreach ($cards as $c){
				$cardsByID[$c['cardid']]=$c;
			}
		}
		$cardsCount=count($cards);
		$this->assign('cards',$cards);
		$this->assign('memberCard',$cards[0]);
		if ($cardsCount&&isset($_GET['mycard'])){
			echo '<script>location.href="/index.php?g=Wap&m=Card&a=card&wecha_id='.$this->wecha_id.'&token='.$this->token.'&cardid='.$cards[0]['cardid'].'";</script>';
		}
		$this->assign('cardsCount',$cardsCount);
		//
		$userinfo_db=M('Userinfo');
		$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
		$userScore=0;
		if ($userInfos){
			$userScore=intval($userInfos[0]['total_score']);
		}
		$this->assign('userScore',$userScore);
		//
		$member_card_set_db=M('Member_card_set');
		$allCards=$member_card_set_db->where(array('token'=>$this->token))->order('miniscore ASC')->select();
		if ($allCards){
			$i=0;
			foreach ($allCards as $c){
				$allCards[$i]['applied']=$cardsByID[$c['id']]?1:0;
				if (isset($_GET['mycard'])&&!$allCards[$i]['applied']){
					unset($allCards[$i]);
				}
				$i++;
			}
		}
		$allCardsCount=count($allCards);
		$this->assign('allCards',$allCards);
		$this->assign('allCardsCount',$allCardsCount);
		//
		$thisCompany=M('Company')->where(array('token'=>$this->token,'isbranch'=>0))->find();
		$this->assign('thisCompany',$thisCompany);
		//
		$infoType='memberCardHome';
		if (isset($_GET['mycard'])){
			$infoType='myCard';
		}
		$this->assign('infoType',$infoType);
		//
		$this->display();
    }
    public function companyMap(){
    	//
    	$member_card_create_db=M('Member_card_create');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
    	//
    	$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		$infoType='companyDetail';
		$this->assign('infoType',$infoType);
		$this->display();
    }
    public function companyDetail(){
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token))->order('id ASC')->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	$member_card_create_db=M('Member_card_create');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
    	//
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$companies=$company_model->where($where)->order('isbranch ASC')->select();
		$this->assign('companies',$companies);
		$infoType='companyDetail';
		$this->assign('infoType',$infoType);
		$this->display();
    }
    public function companyIntro(){
    	//
    	$member_card_create_db=M('Member_card_create');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
    	//
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		$infoType='companyDetail';
		$this->assign('infoType',$infoType);
		$this->display();
    }
    function card(){
    	$this->assign('infoType','card');
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	$this->assign('card',$thisCard);
    	$error=0;
    	if ($thisCard){
    		$userinfo_db=M('Userinfo');
    		$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    		$userScore=0;
    		if ($userInfos){
    			$userScore=intval($userInfos[0]['total_score']);
    			$userInfo=$userInfos[0];
    		}
    		$this->assign('userScore',$userScore);
    		//
    		$member_card_create_db=M('Member_card_create');
    		$thisMember=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>intval($_GET['cardid'])))->find();
    		$hasApplied=count($thisMember);
    		//
    		if (!$hasApplied){
    			//
    			$card=M('Member_card_create')->field('id,number')->where("token='".$this->token."' and cardid=".$thisCard['id']." and wecha_id = ''")->find();
    			if(!$card){
    				$error=-1;
    			}else {
    				//
    				if (intval($thisCard['miniscore'])==0||$userScore>intval($thisCard['miniscore'])){
    					$error=-4;
    					header('Location:/index.php?g=Wap&m=Userinfo&a=index&token='.$this->token.'&wecha_id='.$this->wecha_id.'&cardid='.$thisCard['id']);
    				}else {
    					$error=-3;
    				}
    			}
    			//
    		}else{
    			$this->assign('thisMember',$thisMember);
    			//
    			$now=time();
    			//
    			$noticeCount=M('Member_card_notice')->where('cardid='.$thisCard['id'].' AND endtime>'.$now)->count();
    			$this->assign('noticeCount',$noticeCount);
    			//
    			$member_card_vip_db=M('Member_card_vip');
    			$previlegeCount=$member_card_vip_db->where('cardid='.$thisCard['id'].' AND ((type=0 AND statdate<'.$now.' AND enddate>'.$now.') OR type=1)')->count();
    			$this->assign('previlegeCount',$previlegeCount);
    			//
    			$member_card_integral_db=M('Member_card_integral');
    			$integralCount=$member_card_integral_db->where('cardid='.$thisCard['id'].' AND statdate<'.$now.' AND enddate>'.$now)->count();
    			$this->assign('integralCount',$integralCount);
    			//
    			$member_card_coupon_db=M('Member_card_coupon');
    			$couponCount=$member_card_coupon_db->where('cardid='.$thisCard['id'].' AND statdate<'.$now.' AND enddate>'.$now)->count();
    			$this->assign('couponCount',$couponCount);
    			//
    			$todaySigned=$this->_todaySigned();
    			$this->assign('todaySigned',$todaySigned);
    			//
    			$this->assign('userInfo',$userInfo);
    		}
    	}else {
    		$error=-2;
    	}
    	$this->assign('error',$error);
    	$this->display();
    }
    public function cardIntro(){
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	$data=M('Member_card_exchange')->where(array('token'=>$this->token,'cardid'=>$_GET['cardid']))->find();
    	$this->assign('data',$data);
    	//
    	$company_model=M('Company');
		$where=array('token'=>$this->token);
		$thisCompany=$company_model->where($where)->order('isbranch ASC')->find();
		$thisCompany['intro']=str_replace(array('&lt;','&gt;','&quot;','&amp;nbsp;'),array('<','>','"',' '),$thisCompany['intro']);
		$this->assign('thisCompany',$thisCompany);
    	//
    	$this->display();
    }
    public function signscore(){
    	$userinfo_db=M('Userinfo');
    	$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    	$userScore=0;
    	if ($userInfos){
    		$userScore=intval($userInfos[0]['total_score']);
    		$userInfo=$userInfos[0];
    	}
    	$this->assign('userInfo',$userInfo);
    	$this->assign('userScore',$userScore);
    	//
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	$todaySigned=$this->_todaySigned();
    	$this->assign('todaySigned',$todaySigned);
    	//
    	
    	$cardsign_db   = M('Member_card_sign');
    	$now=time();
    	$day=date('d',$now);
    	$year=date('Y',$now);
    	$month=date('m',$now);
    	if (isset($_GET['month'])){
    		$month=intval($_GET['month']);
    	}
    	$firstday = date('Y-m-01', strtotime($now));
    	$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
    	$firstSecondOfMonth=mktime(0,0,0,$month,1,$year);
    	$lastSecondOfMonth=mktime(23,59,59,$month,$lastday,$year);
    	$signRecords=$cardsign_db->where('token=\''.$this->token.'\' AND wecha_id=\''.$this->wecha_id.'\' AND sign_time>'.$firstSecondOfMonth.' AND sign_time<'.$lastSecondOfMonth)->order('sign_time DESC')->select();
    	$this->assign('signRecords',$signRecords);
    	//
    	$this->display();
    }
    public function addSign(){
    	$signed=$this->_todaySigned();
    	if ($signed){
    		echo'{"success":1,"msg":"您今天已经签到了"}';
    		exit();
    	}
    	$cardsign_db   = M('Member_card_sign');
    	$where    = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'score_type'=>1);
    	$sign = $cardsign_db->where($where)->order('sign_time desc')->find();
    	//
    	if($sign == null){
    		$cardsign_db->add($where);
    		$sign = $cardsign_db->where($where)->order('id desc')->find();
    	}
    	$get_card=M('member_card_create')->where(array('wecha_id'=>$this->wecha_id,'cardid'=>intval($_GET['cardid'])))->find();
    	//
    	if(empty($get_card)){
    		Header("Location: ".C('site_url').'/'.U('Wap/Card/card',array('token'=>$this->token,'wecha_id'=>$this->wecha_id)));
    		exit('领卡后才可以签到.');
    	}
    	//
    	$set_exchange = M('Member_card_exchange')->where(array('token'=>$this->token,'cardid'=>intval($_GET['cardid'])))->find();
        $this->assign('set_exchange',$set_exchange);
        //
        $userinfo = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
    	//
    	$data['sign_time']  = time();
    	$data['is_sign']    = 1;
    	$data['score_type'] = 1;
    	$data['token']      = $this->token;
    	$data['wecha_id']   = $this->wecha_id;
    	$data['expense']    = intval($set_exchange['everyday']);
    	$rt=$cardsign_db->where($where)->add($data);
    	//
    	if ($rt){
    		$da['total_score'] = $userinfo['total_score'] +  $data['expense'];
    		$da['sign_score']  = $userinfo['sign_score'] + $data['expense'];
    		$da['continuous']  =  1;
    		//
    		M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->save($da);
    		echo'{"success":1,"msg":"签到成功，成功获取了'.$set_exchange['everyday'].'个积分"}';
    	}else {
    		echo'{"success":1,"msg":"暂时无法签到"}';
    	}
    }
    function _todaySigned(){
    	$signined=0;
    	$now=time();
    	$member_card_sign_db   = M('Member_card_sign');
    	$where    = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'score_type'=>1);
    	$sign = $member_card_sign_db->where($where)->order('sign_time desc')->find();
    	$today = date('Y-m-d',$now);
    	$itoday = date('Y-m-d',intval($sign['sign_time']));
    	if($sign&&$itoday == $today){
    		$signined = 1;
    	}
    	return $signined;
    }
    public function _thisCard(){
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	return $thisCard;
    }
    public function notice(){
    	$this->assign('infoType','notice');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	//
    	$member_card_notice_db=M('Member_card_notice');
    	$now=time();
    	//
    	$notices=$member_card_notice_db->where('cardid='.$thisCard['id'].' AND endtime>'.$now)->select();
    	if ($notices){
    		$i=0;
    		foreach ($notices as $n){
    			$notices[$i]['content']=html_entity_decode($n['content']);
    			$i++;
    		}
    	}
    	$this->assign('notices',$notices);
    	$this->assign('firstItemID',$notices[0]['id']);
    	$this->display();
    }
    public function previlege(){
    	$this->assign('infoType','privelege');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	//
    	$now=time();
    	//
    	$member_card_vip_db=M('Member_card_vip');
    	$list=$member_card_vip_db->where('cardid='.$thisCard['id'].' AND ((type=0 AND statdate<'.$now.' AND enddate>'.$now.') OR type=1)')->order('create_time desc')->select();
    	if ($list){
    		$i=0;
    		foreach ($list as $n){
    			$list[$i]['info']=html_entity_decode($n['info']);
    			$i++;
    		}
    	}
    	$this->assign('firstItemID',$list[0]['id']);
    	$this->assign('list',$list);
    	//
    	$this->display();
    }
    public function integral(){
    	$this->assign('infoType','integral');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	//
    	$now=time();
    	//
    	$member_card_integral_db=M('Member_card_integral');
    	$list=$member_card_integral_db->where('cardid='.$thisCard['id'].' AND statdate<'.$now.' AND enddate>'.$now)->order('create_time desc')->select();
    	$this->assign('integralCount',$integralCount);
    	if ($list){
    		$i=0;
    		foreach ($list as $n){
    			$list[$i]['info']=html_entity_decode($n['info']);
    			$i++;
    		}
    	}
    	$this->assign('firstItemID',$list[0]['id']);
    	$this->assign('list',$list);
    	//
    	$this->display();
    }
    public function coupon(){
    	$this->assign('infoType','coupon');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	//
    	$now=time();
    	//
    	$member_card_coupon_db=M('Member_card_coupon');
    	$list=$member_card_coupon_db->where('cardid='.$thisCard['id'].' AND statdate<'.$now.' AND enddate>'.$now)->order('create_time desc')->select();
    	if ($list){
    		$i=0;
    		foreach ($list as $n){
    			$list[$i]['info']=html_entity_decode($n['info']);
    			$list[$i]['useCount']=M('Member_card_use_record')->where(array('itemid'=>$n['id'],'cat'=>3,'wecha_id'=>$this->wecha_id))->sum('usecount');
    			$list[$i]['nouseCount']=intval($n['people'])-$list[$i]['useCount'];
    			$i++;
    		}
    	}
    	$this->assign('firstItemID',$list[0]['id']);
    	$this->assign('list',$list);
    	//
    	$this->display();
    }
    public function action_usePrivelege(){
    	if (IS_POST){
    		$itemid=intval($_POST['itemid']);
    		$db=M('Member_card_vip');
    		$thisItem=$db->where(array('id'=>$itemid))->find();
    		if (!$thisItem){
    			echo'{"success":-2,"msg":"不存在指定特权"}';
    			exit();
    		}
    		//
    		$member_card_set_db=M('Member_card_set');
    		$thisCard=$member_card_set_db->where(array('id'=>intval($thisItem['cardid'])))->find();
    		$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
    		if (!$thisCard){
    			echo'{"success":-3,"msg":"会员卡不存在"}';
    			exit();
    		}
    		//
    		$staff_db=M('Company_staff');
    		$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();
    		if (!$thisStaff){
    			echo'{"success":-4,"msg":"用户名和密码不匹配"}';
    			exit();
    		}else {
    			if (md5($this->_post('password'))!=$thisStaff['password']){
    				echo'{"success":-4,"msg":"用户名和密码不匹配"}';
    				exit();
    			}else {
    				$now=time();
    				//score
    				$arr=array();
    				$arr['itemid']=$this->_post('itemid');
    				$arr['token']=$thisItem['token'];
    				$arr['wecha_id']=$this->_post('wecha_id');
    				$arr['expense']=$this->_post('money');
    				$arr['time']=$now;
    				$arr['cat']=$this->_post('cat');
    				$arr['staffid']=$thisStaff['id'];
    				//
    				
    				$arr['score']=intval($set_exchange['reward'])*$arr['expense'];
    				//
    				M('Member_card_use_record')->add($arr);
    				$userinfo_db=M('Userinfo');
    				$thisUser = $this->thisUser;
    				$userArr=array();
    				$userArr['total_score']=$thisUser['total_score']+$arr['score'];
    				$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
    				$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
    				//
    				$useCount=intval($thisItem['usetime'])+1;
    				$db->where(array('id'=>$itemid))->save(array('usetime'=>$useCount));
    				echo'{"success":1,"msg":"数据提交成功"}';
    			}
    		}
    		//
    	}else {
    		echo'{"success":-1,"msg":"不是post数据"}';
    	}
    }
    function action_useIntergral(){
    	$now=time();
    	if (IS_POST){
    		$itemid=intval($_POST['itemid']);
    		$db=M('Member_card_integral');
    		$thisItem=$db->where('id='.$itemid.' AND statdate<'.$now.' AND enddate>'.$now)->find();
    		if (!$thisItem){
    			echo'{"success":-2,"msg":"不存在指定信息"}';
    			exit();
    		}
    		
    		//
    		$member_card_set_db=M('Member_card_set');
    		$thisCard=$member_card_set_db->where(array('id'=>intval($thisItem['cardid'])))->find();
    		if (!$thisCard){
    			echo'{"success":-3,"msg":"会员卡不存在"}';
    			exit();
    		}
    		//
    		$userinfo_db=M('Userinfo');
    		$thisUser = $this->thisUser;
    		if ($thisUser['total_score']<$thisItem['integral']){
    			echo'{"success":-5,"msg":"您只有'.intval($thisUser['total_score']).'个积分，无法兑换"}';
    			exit();
    		}
    		//
    		$staff_db=M('Company_staff');
    		$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();
    		if (!$thisStaff){
    			echo'{"success":-4,"msg":"用户名和密码不匹配"}';
    			exit();
    		}else {
    			if (md5($this->_post('password'))!=$thisStaff['password']){
    				echo'{"success":-4,"msg":"用户名和密码不匹配"}';
    				exit();
    			}else {
    				//score
    				$arr=array();
    				$arr['itemid']=$this->_post('itemid');
    				$arr['wecha_id']=$this->_post('wecha_id');
    				$arr['expense']=0;
    				$arr['time']=$now;
    				$arr['token']=$thisItem['token'];
    				$arr['cat']=$this->_post('cat');
    				$arr['staffid']=$thisStaff['id'];
    				//
    				$arr['score']=0-intval($thisItem['integral']);
    				//
    				M('Member_card_use_record')->add($arr);
    				
    				$userArr=array();
    				$userArr['total_score']=$thisUser['total_score']+$arr['score'];
    				$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
    				$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
    				//
    				$useCount=intval($thisItem['usetime'])+1;
    				$db->where(array('id'=>$itemid))->save(array('usetime'=>$useCount));
    				echo'{"success":1,"msg":"兑换成功"}';
    			}
    		}
    		//
    	}else {
    		echo'{"success":-1,"msg":"不是post数据"}';
    	}
    }
    function action_useCoupon(){
    	$now=time();
    	if (IS_POST){
    		$itemid=intval($_POST['itemid']);
    		$db=M('Member_card_coupon');
    		$thisItem=$db->where('id='.$itemid.' AND statdate<'.$now.' AND enddate>'.$now)->find();
    		if (!$thisItem){
    			echo'{"success":-2,"msg":"不存在指定信息"}';
    			exit();
    		}
    		
    		//
    		$member_card_set_db=M('Member_card_set');
    		$thisCard=$member_card_set_db->where(array('id'=>intval($thisItem['cardid'])))->find();
    		if (!$thisCard){
    			echo'{"success":-3,"msg":"会员卡不存在"}';
    			exit();
    		}
    		//
    		$userinfo_db=M('Userinfo');
    		$thisUser = $this->thisUser;
    		//
    		$useTime=intval($_POST['usetime']);
    		$useCount=M('Member_card_use_record')->where(array('itemid'=>$itemid,'cat'=>3,'wecha_id'=>$this->_post('wecha_id')))->sum('usecount');
    		$useCount=$useCount+$useTime;
    		if (intval($useCount)>intval($thisItem['people'])){
    			echo'{"success":-5,"msg":"最多能用'.$thisItem['people'].'次"}';
    			exit();
    		}
    		//
    		$staff_db=M('Company_staff');
    		$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();
    		if (!$thisStaff){
    			echo'{"success":-4,"msg":"用户名和密码不匹配"}';
    			exit();
    		}else {
    			if (md5($this->_post('password'))!=$thisStaff['password']){
    				echo'{"success":-4,"msg":"用户名和密码不匹配"}';
    				exit();
    			}else {
    				//score
    				$arr=array();
    				$arr['itemid']=$this->_post('itemid');
    				$arr['wecha_id']=$this->_post('wecha_id');
    				$arr['expense']=$this->_post('money');
    				$arr['time']=$now;
    				$arr['token']=$thisItem['token'];
    				$arr['cat']=$this->_post('cat');
    				$arr['staffid']=$thisStaff['id'];
    				$arr['usecount']=$useTime;
    				//
    				$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
    				$arr['score']=intval($set_exchange['reward'])*$arr['expense'];
    				//
    				M('Member_card_use_record')->add($arr);
    				$userArr=array();
    				$userArr['total_score']=$thisUser['total_score']+$arr['score'];
    				$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
    				$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
    				//
    				$thisuseCount=intval($thisItem['usetime'])+$useTime;
    				$db->where(array('id'=>$itemid))->save(array('usetime'=>$thisuseCount));
    				echo'{"success":1,"msg":"成功提交记录"}';
    			}
    		}
    		//
    	}else {
    		echo'{"success":-1,"msg":"不是post数据"}';
    	}
    }
    public function expense(){
    	$userinfo_db=M('Userinfo');
    	$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    	$userScore=0;
    	if ($userInfos){
    		$userScore=intval($userInfos[0]['total_score']);
    		$userInfo=$userInfos[0];
    	}
    	$this->assign('userInfo',$userInfo);
    	$this->assign('userScore',$userScore);
    	//
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	
    	$db   = M('Member_card_use_record');
    	$now=time();
    	$day=date('d',$now);
    	$year=date('Y',$now);
    	$month=date('m',$now);
    	if (isset($_GET['month'])){
    		$month=intval($_GET['month']);
    	}
    	$firstday = date('Y-m-01', strtotime($now));
    	$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
    	$firstSecondOfMonth=mktime(0,0,0,$month,1,$year);
    	$lastSecondOfMonth=mktime(23,59,59,$month,$lastday,$year);
    	$records=$db->where('token=\''.$this->token.'\' AND wecha_id=\''.$this->wecha_id.'\' AND time>'.$firstSecondOfMonth.' AND time<'.$lastSecondOfMonth)->order('time DESC')->select();
    	$this->assign('records',$records);
    	//
    	$this->display();
    }
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
	public function request(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
		$token=$this->_get('token');
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			//卡号
			$card=M('member_card_create')->where(array('token'=>$token))->order('id asc')->find();
			//联系方式
			$contact=M('member_card_contact')->where(array('token'=>$token))->order('sort desc')->find();
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('info',$info);			
		}else{
			$this->error('无此信息');
		}
		$this->display();	
    }

	public function get_card(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$card=M('member_card_create')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();
		if($card){
			header('Location:'.rtrim(C('site_url'),'/').U('Wap/Card/vip',array('token'=>$token,'wecha_id'=>$wecha_id)));
		}
		
		
		
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
		
		$get_card=M('member_card_create')->where(array('wecha_id'=>$wecha_id))->find();

		if($get_card!=false){
			Header("Location: ".C('site_url').'/'.U('Wap/Card/vip',array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))); 
		}
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			//联系方式
		
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$contact=M('company')->where(array('token'=>$token,'branch'=>0))->find();
			$this->assign('contact',$contact);
			$this->assign('info',$info);
		}else{
			$this->error('无此信息');
		}
		$this->display();	
    }

	public function info(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
		$token=$this->_get('token');
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			$info['description']=nl2br($info['description']);
			//联系方式
			$contact=M('member_card_contact')->where(array('token'=>$token))->order('sort desc')->find();
			//我的卡号
			$mycard=M('member_card_create')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))->find();
			$this->assign('mycard',$mycard);
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('info',$info);
		}else{
			$this->error('无此信息');
		}
		$this->display();	
    }

	public function vip(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$card=M('member_card_create')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();
		if($card==false){
			header('Location:'.rtrim(C('site_url'),'/').U('Wap/Card/get_card',array('token'=>$token,'wecha_id'=>$wecha_id)));
		}
		//
	   $agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}

		 
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			//卡号
			$card=M('member_card_create')->where(array('token'=>$token,'wecha_id'=>$this->_get('wecha_id')))->find();
			//var_dump($card);exit;
			//dump(array('token'=>$token,'wecha_id'=>$this->get('wecha_id')));
			//联系方式
			$contact=M('company')->where(array('token'=>$token,'branch'=>0))->find();
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('info',$info);			
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//dump($data);
			$this->assign('card',$data);
			//特权服务
			$vip=M('member_card_vip')->where(array('token'=>$token))->order('id desc')->find();
			//dump($vip);
			$this->assign('vip',$vip);
			//优惠卷
			$coupon=M('member_card_coupon')->where(array('token'=>$token))->find();
			$this->assign('coupon',$coupon);
			//兑换
			$integral=M('member_card_integral')->where(array('token'=>$token))->find();
			$this->assign('integral',$integral);
		}else{
			$this->error('无此信息');
		}
	
		$this->display();
	
	}
	public function addr(){
	$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
	
		$token=$this->_get('token');
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			//$addr=M('member_card_contact')->where(array('token'=>$token))->select();
			//if (!$addr){
			$addr=M('Company')->where(array('token'=>$token))->order('isbranch ASC')->select();
			if ($addr){
				$i=0;
				foreach ($addr as $a){
					$addr[$i]['info']=$a['address'];
					$addr[$i]['tel']=$a['tel'];
					$i++;
				}
			}
			//}
			//联系方式
			$contact=M('member_card_contact')->where(array('token'=>$token))->order('sort desc')->find();
			//我的卡号
			$mycard=M('member_card_create')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))->find();
			$this->assign('mycard',$mycard);
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('addr',$addr);
		}else{
			$this->error('无此信息');
		}
		$this->display();
	
	}
}
?>