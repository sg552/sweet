<?php
class TenpayAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->token){
			//
			$product_cart_model=M('product_cart');
			$out_trade_no = $this->_get('out_trade_no');
			$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
			if (!$order){
				$order=$product_cart_model->where(array('id'=>intval($this->_get('out_trade_no'))))->find();
			}
			$this->token=$order['token'];
		}
		//读取支付宝配置
		$pay_config_db=M('Alipay_config');
		$this->payConfig=$pay_config_db->where(array('token'=>$this->token))->find();
	}
	public function pay(){
		//参数数据
		$price=$_GET['price'];
		$orderName=$_GET['orderName'];
		$orderid=$_GET['orderid'];
		if (!$orderid){
			$orderid=$_GET['single_orderid'];//单个订单
		}
		$notify_url = C('site_url').'/index.php?g=Wap&m=Tenpay&a=notify_url';
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = C('site_url').'/index.php?g=Wap&m=Tenpay&a=return_url&from='.$_GET['from'];
		//
		if(!$price)exit('必须有价格才能支付');
		$total_fee =floatval($price);
		import("@.ORG.Tenpay.WapPayRequest");
		$out_trade_no = $orderid;
		$req = new WapPayRequest($this->payConfig['partnerkey']);
		$req->setInSandBox(false);
		$req->setAppid($this->payConfig['partnerid']);
		
		$req->setParameter('total_fee', $total_fee*100);
		
		$req->setParameter('body', '财付通在线支付');
		$req->setParameter('notify_url', $notify_url);
		$req->setParameter('out_trade_no', $out_trade_no);
		$req->setParameter('return_url', $return_url);
		$req->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
		$req->setParameter('request_token', $_GET['token']);
		
		header('Location:'.$req->getURL());
	}
	//同步数据处理
	public function return_url (){
		$out_trade_no = $this->_get('out_trade_no');
		if(intval($_GET['total_fee'])) {
			$product_cart_model=M('product_cart');
			$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
			if (!$this->wecha_id){
				$this->wecha_id=$order['wecha_id'];
			}
			$sepOrder=0;
			if (!$order){
				$order=$product_cart_model->where(array('id'=>$out_trade_no))->find();
				$sepOrder=1;
			}
			if($order){
				if($order['paid']==1){exit('该订单已经支付,请勿重复操作');}
				if (!$sepOrder){
					$product_cart_model->where(array('orderid'=>$out_trade_no))->setField('paid',1);
				}else {
					$product_cart_model->where(array('id'=>$out_trade_no))->setField('paid',1);
				}
				/************************************************/
				$member_card_create_db=M('Member_card_create');
				$userCard=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
				$member_card_set_db=M('Member_card_set');
				$thisCard=$member_card_set_db->where(array('id'=>intval($userCard['cardid'])))->find();
				$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
				//
				$arr['token']=$this->token;
				$arr['wecha_id']=$this->wecha_id;
				$arr['expense']=$order['price'];
				$arr['time']=time();
				$arr['cat']=99;
				$arr['staffid']=0;
				$arr['score']=intval($set_exchange['reward'])*$order['price'];
				M('Member_card_use_record')->add($arr);
				$userinfo_db=M('Userinfo');
				$thisUser = $userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->find();
				$userArr=array();
				$userArr['total_score']=$thisUser['total_score']+$arr['score'];
				$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
				$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
				/************************************************/
				//if($back!=false&&$add!=false){
				switch ($_GET['from']){
						default:
							$this->redirect('?g=Wap&m=Product&a=my&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
							break;
						case 'groupon':
							$this->redirect('?g=Wap&m=Groupon&a=myOrders&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
							break;
						case 'dining':
							$this->redirect('?g=Wap&m=Product&a=my&dining=1&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
							break;
					}
				//}else{
				//$this->error('充值失败,请在线客服,为您处理',U('User/Index/index'));
				//}
			}else{
				exit('订单不存在：'.$out_trade_no);
			}
		}else {
			exit('付款失败');
		}
	}
	public function notify_url(){
		echo "success"; 
		eixt();
	}
}
?>