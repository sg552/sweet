<?php
class RepastAction extends WapAction {
	public $token;
	public $wecha_id = '';
	
	public $session_dish_info;//
	public $session_dish_user;
	public $_cid = 0;
	
	public function _initialize(){
		parent::_initialize();
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if (!strpos($agent, "MicroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		
		//$this->token = isset($_REQUEST['token']) ? $_REQUEST['token'] : session('token');//$this->_get('token');
		
		//$this->assign('token', $this->token);
		//$this->wecha_id	= isset($_REQUEST['wecha_id']) ? $_REQUEST['wecha_id'] : '';
		//$this->assign('wecha_id', $this->wecha_id);
		
		$this->_cid = $_SESSION["session_company_{$this->token}"];
		$this->assign('cid', $this->_cid);
		
		$this->session_dish_info = "session_dish_{$this->_cid}_info_{$this->token}";
		$this->session_dish_user = "session_dish_{$this->_cid}_user_{$this->token}";
		$menu = $this->getDishMenu();
		$count = count($menu);
		$this->assign('totalDishCount', $count);
	}
	
	/**
	 * 餐厅分布
	 */
	public function index() {
		$company = M('Company')->where("`token`='{$this->token}' AND ((`isbranch`=1 AND `display`=1) OR `isbranch`=0)")->select();
		if (count($company) == 1) {
			$this->redirect(U('Repast/select',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $company[0]['id'])));
		}
		$this->assign('company', $company);
		$this->assign('metaTitle', '餐厅分布');
		$this->display();
	}
	
	/**
	 *就餐形式选择页 
	 */
	public function select() {
		$istakeaway = 0;
		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
		if ($company = M('Company')->where(array('token' => $this->token, 'id' => $cid))->find()) {
			$_SESSION["session_company_{$this->token}"] = $cid;
		} else {
			$this->redirect(U('Repast/index',array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
		}
		
		if ($dishCompany = M('Dish_company')->where(array('cid' => $cid))->find()) {
			$istakeaway = $dishCompany['istakeaway'];
		}
		$this->assign('dishCompany', $dishCompany);
		$this->assign('istakeaway', $istakeaway);
		$this->assign('metaTitle', '点餐选择');
		$this->display();
	}
	
	/**
	 * 餐厅介绍
	 */
	public function virtual() {
		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
		$company = M('Company')->where(array('token' => $this->token, 'id' => $cid))->find();
		$this->assign('company', $company);
		$this->assign('metaTitle', '餐厅介绍');
		$this->display();
	}
	/**
	 * 选取餐桌与填写个人信息
	 */
	public function selectTable() {
		//$takeaway 0:在线预定 1：外卖2：现场点餐
		//$thisUser = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		//$this->assign('thisUser', $thisUser);
		$takeaway = isset($_GET['takeaway']) ? intval($_GET['takeaway']) : 0;
		$_SESSION[$this->session_dish_user] = null;
		unset($_SESSION[$this->session_dish_user]);
		$time = time();
		$orderTable = M('Dish_table')->where(array('reservetime' => array('elt', $time + 2 * 3600), 'reservetime' => array('egt', $time - 2 * 3600), 'cid' => $this->_cid, 'isuse' => 0))->select();
		$tids = array();
		foreach ($orderTable as $row) {
			$tids[] = $row['tableid'];
		}
		if ($tids) {
			$table = M('Dining_table')->where(array('id' => array('not in', $tids), 'cid' => $this->_cid))->select();
		} else {
			$table = M('Dining_table')->where(array('cid' => $this->_cid))->select();
		}
		
		$dates = array();
		$dates[] = array('k' => date("Y-m-d"), 'v' => date("m月d日"));
		for ($i = 1; $i <= 90; $i ++) {
			$dates[] = array('k' => date("Y-m-d", strtotime("+{$i} days")), 'v' => date("m月d日", strtotime("+{$i} days")));
		}
		$hours = array();
		for ($i = 0; $i < 24; $i ++) {
			$hours[] = array('k' => $i, 'v' => $i . "时");
		}
		
		$seconds = array();
		for ($i = 0; $i < 60; $i ++) {
			$seconds[] = array('k' => $i, 'v' => $i . "分");
		}
		
		$selhour = date('H', time() + 1800);
		$selsecond = date('i', time() + 1800);
		
		$this->assign('selhour', $selhour);
		$this->assign('selsecond', $selsecond);
		
		$this->assign('dates', $dates);
		$this->assign('seconds', $seconds);
		$this->assign('hours', $hours);
		$this->assign('takeaway', $takeaway);
		$this->assign('tables', $table);
		$this->assign('metaTitle', '填写个人信息');
		$this->assign('time', date("Y-m-d H:i:s"));
		$this->display();
	}
	
	/**
	 * ajax请求获取餐桌信息
	 */
	public function getTable() {
		$date = isset($_POST['redate']) ? htmlspecialchars($_POST['redate']) : '';
		$hour = isset($_POST['rehour']) ? htmlspecialchars($_POST['rehour']) : '';
		$second = isset($_POST['resecond']) ? htmlspecialchars($_POST['resecond']) : '';
		$time = strtotime($date . ' ' . $hour . ':' . $second . ':00');
		$orderTable = M('Dish_table')->where(array('reservetime' => array('elt', $time + 2 * 3600), 'reservetime' => array('egt', $time - 2 * 3600), 'cid' => $this->_cid, 'isuse' => 0))->select();
		$tids = array();
		foreach ($orderTable as $row) {
			$tids[] = $row['tableid'];
		}
		if ($tids) {
			$table = M('Dining_table')->where(array('id' => array('not in', $tids), 'cid' => $this->_cid))->select();
		} else {
			$table = M('Dining_table')->where(array('cid' => $this->_cid))->select();
		}
		exit(json_encode($table));
	}
	
	/**
	 * 保存订餐人的信息到session
	 */
	public function saveUser() {
		$takeaway = isset($_POST['takeaway']) ? intval($_POST['takeaway']) : 0;
		$tel = $table = $address = $des = $name = '';
		$sex = $nums = 1;
		$price = 0;
		//if ($takeaway == 1) {
		$dishCompany = M('Dish_company')->where(array('cid' => $this->_cid))->find();
		if (isset($dishCompany['istakeaway']) && $dishCompany['istakeaway'] && $takeaway == 1) $price = $dishCompany['price'];
		//}
		if ($takeaway != 2) {
			$tel = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
			if (empty($tel)) {
				$this->error('电话号码不能为空');
				die;
			}
			$name = isset($_POST['guest_name']) ? $_POST['guest_name'] : '';
			if (empty($name)) {
				$this->error('姓名不能为空');
				die;
			}
			$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
			$sex = isset($_POST['sex']) ? intval($_POST['sex']) : 0;
			$date = isset($_POST['redate']) ? htmlspecialchars($_POST['redate']) : '';
			$hour = isset($_POST['rehour']) ? htmlspecialchars($_POST['rehour']) : '';
			$second = isset($_POST['resecond']) ? htmlspecialchars($_POST['resecond']) : '';
			$reservetime = strtotime($date . ' ' . $hour . ':' . $second . ':00');
			if ($reservetime < time()) {
				$this->error('预约用餐时间不可以小于当前时间');
				die;
			}
			$nums = isset($_POST['nums']) ? intval($_POST['nums']) : 1;
		} else {
			$reservetime = time() + 600;
			$name = empty($this->fans['truename']) ? '' : $this->fans['truename'];
			$tel = empty($this->fans['tel']) ? '' : $this->fans['tel'];
			$address = empty($this->fans['address']) ? '' : $this->fans['address'];
		}
		$table = isset($_POST['table']) ? intval($_POST['table']) : 0;
		$des = isset($_POST['remark']) ? htmlspecialchars($_POST['remark']) : '';
		$paystatus = isset($_POST['paystatus']) ? intval($_POST['paystatus']) : 1;
		$data = array('tableid' => $table, 'tel' => $tel, 'takeaway' => $takeaway, 'address' => $address, 'name' => $name, 'sex' => $sex, 'reservetime' => $reservetime, 'price' => $price, 'nums' => $nums, 'des' => $des);
		if ($paystatus) {
			$_SESSION[$this->session_dish_user] = serialize($data);
			$this->redirect(U('Repast/dish', array('token'=>$this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)));
		} else {
			
			$data = array('tableid' => $table, 'tel' => $tel, 'takeaway' => $takeaway, 'address' => $address, 'name' => $name, 'sex' => $sex, 'reservetime' => $reservetime, 'price' => $dishCompany['subscription'], 'nums' => 0, 'des' => $des);
			$data['cid'] = $this->_cid;
			$data['wecha_id'] = $this->wecha_id;
			$data['token'] = $this->token;
			$data['info'] = '';
			$data['time'] = time();
			$data['orderid'] = substr($this->wecha_id, -1, 4) . date("YmdHis");
			$doid = D('Dish_order')->add($data);
		
			
			if ($doid) {
				//TODO 短信提示
				Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$data['name']}刚刚预约了一个餐桌，订单号：{$data['orderid']}，请您注意查看并处理");
				
				if ($data['tableid']) {
					$table_order = array('cid' => $this->_cid, 'tableid' => $data['tableid'], 'orderid' => $doid, 'wecha_id' => $this->wecha_id, 'reservetime' => $data['reservetime'], 'creattime' => time());
					$doid = D('Dish_table')->add($table_order);
					$t_table = M("Dining_table")->where(array('id' => $data['tableid']))->find();
				}
				
			
				$company = M("Company")->where(array('token' => $this->token, 'id' => $this->_cid))->find();
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'des' => $data['des'], 'companytel' => $company['tel'], 'truename' => $data['name'], 'tel' => $data['tel'], 'takeAwayPrice' => 0, 'address' => isset($data['address']) ? $data['address'] : '', 'buytime' => $data['time'], 'orderid' => $data['orderid'], 'sendtime' => $data['reservetime'], 'price' => 0, 'total' => 0);
				$msg['typename'] =  '预约餐桌';
				$msg['tablename'] = isset($t_table['name']) ? $t_table['name'] : '';
				$msg = ArrayToStr::array_to_str($msg);
				$op->printit($this->token, $this->_cid, 'Repast', $msg, 0);
			
			
				$_SESSION[$this->session_dish_info] = $_SESSION[$this->session_dish_user] = '';
				unset($_SESSION[$this->session_dish_info], $_SESSION[$this->session_dish_user]);
				$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
				//$dishCompany = M('Dish_company')->where(array('cid' => $this->_cid))->find();
			
				if ($alipayConfig['open'] && $dishCompany['payonline']) {
					$this->success('正在提交中...', U('Alipay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Repast', 'orderName' => $data['orderid'], 'single_orderid' => $data['orderid'], 'price' => $dishCompany['subscription'])));
				} elseif ($this->fans['balance'] && $dishCompany['payonline']) {
					$this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Repast', 'orderName' => $data['orderid'], 'single_orderid' => $data['orderid'], 'price' => $dishCompany['subscription'])));
				} else {
					$this->success('预定成功,进入您的订单页', U('Repast/myOrder',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid,'success'=>1)));
				}
			} else {
				$this->error('订单出错，请重新下单');
			}
		
		}
	}
	
	
	/**
	 * 点餐页
	 */
	public function dish() {
		$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
		$userInfo = unserialize($_SESSION[$this->session_dish_user]);
		if (empty($userInfo)) {
			$this->redirect(U('Repast/select',array('token' => $this->token,'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)));
		}
		$this->assign('metaTitle', $company['name']);
		$this->display();
	}
	
	/**
	 * 菜单列表
	 */
	public function GetDishList() {
		$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
		$dish_sort = M('Dish_sort')->where(array('cid' => $this->_cid))->order("`sort` ASC")->select();
		$dish = M('Dish')->where(array('cid' => $this->_cid, 'isopen' => 1))->order("`sort` ASC")->select();
		$dish_like = M('Dish_like')->where(array('cid' => $this->_cid, 'wecha_id' => $this->wecha_id))->select();
		$like = array();
		foreach ($dish_like as $dl) {
			$like[$dl['did']] = 1;
		}
		$mymenu = $this->getDishMenu();
		$list = array();
		foreach ($dish as $d) {
			$t = array();
			$t['id'] = $d['id'];
			$t['aid'] = $d['cid'];
			$t['name'] = $d['name'];
			$t['price'] = $d['price'];
			$t['discount_name'] = '';
			$t['discount_price'] = '';
			$t['class_id'] = $d['sid'];
			$t['pic'] = $d['image'];
			$t['note'] = $d['des'];
			$t['unit'] = $d['unit'];
			$t['tag_name'] = $d['ishot'] ? '推荐' : '';
			$t['html_name'] = '';
			$t['check'] = isset($like[$d['id']]) ? $like[$d['id']] : 0;
			$t['select'] = isset($mymenu[$d['id']]) ? 1 : 0;
			$list[$d['sid']][] = $t;
		}
		$result = array();
		foreach ($dish_sort as $sort) {
			$r = array();
			$r['id'] = $sort['id'];
			$r['aid'] = $sort['cid'];
			$r['name'] = $sort['name'];
			$r['dishes'] = isset($list[$sort['id']]) ? $list[$sort['id']] : '';
			$result[] = $r;
		}
		exit(json_encode($result));
	}
	
	/**
	 * 对某个菜进行喜欢标记操作
	 */
	public function dolike() {
		if (empty($this->wecha_id)) {
			exit(json_encode(array('status' => 0)));
		}
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$check = isset($_POST['check']) ? intval($_POST['check']) : 0;
		if ($id) {
			$dishLike = D('Dish_like');
			$data = array('did' => $id, 'cid' => $this->_cid, 'wecha_id' => $this->wecha_id);
			if ($check) {
				$dishLike->add($data);
			} else {
				$dishLike->where($data)->delete();
				exit(json_encode(array('status' => 1)));
			}
		}
		exit(json_encode(array('status' => 0)));
	}
	
	/**
	 * 喜欢餐店中的某些菜的列表
	 */
	public function like() {
		if ($this->wecha_id) {
			$mymenu = $this->getDishMenu();
			$dish_like = M('Dish_like')->where(array('cid' => $this->_cid, 'wecha_id' => $this->wecha_id))->select();
			$dids = array();
			foreach ($dish_like as $like) {
				$dids[] = $like['did'];
			}
			$dish = array();
			if ($dids) {
				$list = M('Dish')->where(array('id' => array('in', $dids), 'cid' => $this->_cid))->select();
				foreach ($list as $row) {
					$row['select'] = isset($mymenu[$row['id']]) ? 1 : 0;
					$dish[] = $row;
				}
			}
		} else {
			$dish = array();
		}
		$this->assign('dishlist', $dish);
		$this->assign('metaTitle', '我喜欢的菜');
		$this->display();
	}
	
	
	/**
	 * 点餐操作
	 */
	public function editOrder() {
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$num = isset($_POST['num']) ? intval($_POST['num']) : 0;
		$des = isset($_POST['des']) ? htmlspecialchars($_POST['des']) : '';
		if ($id) {
			$oldMenu = $this->getDishMenu();
			if (isset($oldMenu[$id])) {
				$oldMenu[$id]['des'] = $des ? $des : $oldMenu[$id]['des'];
				$oldMenu[$id]['num'] += $num;
				if ($oldMenu[$id]['num'] == 0) {
					unset($oldMenu[$id]);
				}
			} elseif ($num > 0) {
				$oldMenu[$id]['des'] = $des ;
				$oldMenu[$id]['num'] = $num;
			}
			$_SESSION[$this->session_dish_info] = serialize($oldMenu);
		}
	}
	
	/**
	 * 我的菜单（我的购物车）
	 */
	public function mymenu() {
		$userInfo = unserialize($_SESSION[$this->session_dish_user]);
		if (empty($userInfo)) {
			$this->error('没有填写用餐信息，先填写信息，再提交订单！', U('Repast/select',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)));
		}
		$menu = $this->getDishMenu();
		$data = array();
		$totalNum = $totalPrice = 0;
		if ($menu) {
			$dids = array_keys($menu);
			$dishList = M('Dish')->where(array('cid' => $this->_cid, 'id' => array('in', $dids)))->select();
			foreach ($dishList as $dish) {
				if (isset($menu[$dish['id']])) {
					$totalNum += $menu[$dish['id']]['num'];
					$totalPrice += $menu[$dish['id']]['num'] * $dish['price'];
					$r = array();
					$r['id'] = $dish['id'];
					$r['name'] = $dish['name'];
					$r['price'] = $dish['price'];
					$r['unit'] = $dish['unit'];
					$r['nums'] = $menu[$dish['id']]['num'];
					$r['des'] = $menu[$dish['id']]['des'];
					$data[] = $r;
				}
			}
		}
		
		$tableName = '';
		if ($userInfo['tableid']) {
			$diningTable= M('Dining_table')->where(array('cid' => $this->_cid, 'id' => $userInfo['tableid']))->find();
			$tableName = isset($diningTable['name']) && isset($diningTable['isbox']) ? ($diningTable['isbox'] ?  $diningTable['name'] . '(包厢'. $diningTable['num']. '座)' : $diningTable['name'] . '(大厅'. $diningTable['num']. '座)') : '';
		}
		
		$company = M("Dish_company")->where(array('cid' => $this->_cid))->find();
		
		$this->assign('tableName', $tableName);
		$this->assign('company', $company);
		$this->assign('userInfo', $userInfo);
		$this->assign('totalNum', $totalNum);
		$this->assign('totalPrice', $totalPrice);
		$this->assign('my_dish', $data);
		$this->assign('metaTitle', '我的订单');
		//是否要支付
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$this->assign('alipayConfig', $alipayConfig);
		$this->display();
	}
	
	public function getInfo() 
	{
		if (empty($this->wecha_id)) {
			exit(json_encode(array('success' => 0, 'msg' => '无法获取您的微信身份，请关注“公众号”，然后回复“订餐”来使用此功能')));
		}
		exit(json_encode(array('success' => 1, 'msg' => 'ok')));
	}
	
	/**
	 * 保存我的订单
	 */
	public function saveMyOrder()
	{
		if (empty($this->wecha_id)) {
			unset($_SESSION[$this->session_dish_info]);
			$this->error('您的微信账号为空，不能订餐!');
		}
		$dishs = $this->getDishMenu();
		if (empty($dishs)) {
			$this->error('没有点餐，请去点餐吧!');
		}
		$userInfo = unserialize($_SESSION[$this->session_dish_user]);
		if (empty($userInfo)) {
			$this->error('您的个人信息有误，请重新下单!', U('Repast/selectTable',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)));
		}
		$userInfo['tel'] = isset($userInfo['tel']) && $userInfo['tel'] ? $userInfo['tel'] : '';
		$userInfo['name'] = isset($userInfo['name']) && $userInfo['name']  ? $userInfo['name'] : '';
		$userInfo['address'] = isset($userInfo['address']) && $userInfo['address']  ? $userInfo['address'] : '';
		$userInfo['cid'] = $this->_cid;
		$userInfo['wecha_id'] = $this->wecha_id;
		$userInfo['token'] = $this->token;
		
		$total = $price = 0;
		$dids = array_keys($dishs);
		$dishList = M('Dish')->where(array('cid' => $this->_cid, 'id' => array('in', $dids)))->select();
		$temp = array();
		foreach ($dishList as $r) {
			if (isset($dishs[$r['id']])) {
				$temp[$r['id']] = array('price' => $r['price'], 'num' => $dishs[$r['id']]['num'], 'name' => $r['name'], 'des' => $dishs[$r['id']]['des']);
				$total += $dishs[$r['id']]['num'];
				$price += $dishs[$r['id']]['num'] * $r['price'];
			}
		}
		$takeAwayPrice = 0;
		if (isset($userInfo['price']) && $userInfo['price']) {
			$price += $userInfo['price'];
			$takeAwayPrice = $userInfo['price'];
		}
		$userInfo['total'] = $total;
		$userInfo['price'] = $price;
		$userInfo['info'] = serialize(array('takeAwayPrice' => $takeAwayPrice, 'list' => $temp));
		$userInfo['time'] = time();
		$userInfo['orderid'] = substr($this->wecha_id, -1, 4) . date("YmdHis");
		$doid = D('Dish_order')->add($userInfo);
	
		//保存个人信息
		$userinfo_model = M('Userinfo');
		$thisUser = $userinfo_model->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
		if (empty($thisUser)){
			$userRow = array('tel' => $userInfo['tel'], 'truename' => $userInfo['name'], 'address' => isset($userInfo['address']) ? $userInfo['address'] : '');
			$userRow['token'] = $this->token;
			$userRow['wecha_id'] = $this->wecha_id;
			$userRow['wechaname'] = '';
			$userRow['qq'] = 0;
			$userRow['sex'] = -1;
			$userRow['age'] = 0;
			$userRow['birthday'] = '';
			$userRow['info'] = '';

			$userRow['total_score'] = 0;
			$userRow['sign_score'] = 0;
			$userRow['expend_score'] = 0;
			$userRow['continuous'] = 0;
			$userRow['add_expend'] = 0;
			$userRow['add_expend_time'] = 0;
			$userRow['live_time'] = 0;
			$userinfo_model->add($userRow);
		}
			
		if ($doid) {
			//TODO 短信提示
			if ($userInfo['takeaway'] != 2) {
				if ($userInfo['takeaway'] == 1) {
					Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$userInfo['name']}刚刚叫了一份外卖，订单号：{$userInfo['orderid']}，请您注意查看并处理");
				} else {
					Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$userInfo['name']}刚刚预约了一次用餐，订单号：{$userInfo['orderid']}，请您注意查看并处理");
				}
			}
			if ($userInfo['tableid']) {
				$table_order = array('cid' => $this->_cid, 'tableid' => $userInfo['tableid'], 'orderid' => $doid, 'wecha_id' => $this->wecha_id, 'reservetime' => $userInfo['reservetime'], 'creattime' => time());
				$doid = D('Dish_table')->add($table_order);
				$t_table = M("Dining_table")->where(array('id' => $userInfo['tableid']))->find();
			}
			
			$company = M("Company")->where(array('token' => $this->token, 'id' => $this->_cid))->find();
			$op = new orderPrint();
			$msg = array('companyname' => $company['name'], 'des' => $userInfo['des'], 'companytel' => $company['tel'], 'truename' => $userInfo['name'], 'tel' => $userInfo['tel'], 'takeAwayPrice' => $takeAwayPrice, 'address' => isset($userInfo['address']) ? $userInfo['address'] : '', 'buytime' => $userInfo['time'], 'orderid' => $userInfo['orderid'], 'sendtime' => $userInfo['reservetime'], 'price' => $userInfo['price'], 'total' => $userInfo['total'], 'list' => $temp);
			$msg['typename'] =  $userInfo['takeaway'] == 1 ? '外卖' : ($userInfo['takeaway'] == 2 ? '现在点餐' : '预约点餐');
			$msg['tablename'] = isset($t_table['name']) ? $t_table['name'] : '';
			$msg = ArrayToStr::array_to_str($msg);
			$op->printit($this->token, $this->_cid, 'Repast', $msg, 0);
				
			
			$_SESSION[$this->session_dish_info] = $_SESSION[$this->session_dish_user] = '';
			unset($_SESSION[$this->session_dish_info], $_SESSION[$this->session_dish_user]);
			$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			
			$dishCompany = M('Dish_company')->where(array('cid' => $this->_cid))->find();
			
// 			if ($_POST['paymode'] == 1 && $alipayConfig['open'] && $dishCompany['payonline']) {
// 				$this->success('正在提交中...', U('Alipay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Repast', 'orderName' => $userInfo['orderid'], 'single_orderid' => $userInfo['orderid'], 'price' => $price)));
// 			} elseif ($_POST['paymode'] == 4 && $this->fans['balance'] && $dishCompany['payonline']) {
// 				$this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Repast', 'orderName' => $userInfo['orderid'], 'single_orderid' => $userInfo['orderid'], 'price' => $price)));
// 			} else {
// 				$model = new templateNews();
// 				$model->sendTempMsg('TM00820', array('href' => U('Repast/myOrder',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)), 'wecha_id' => $this->wecha_id, 'first' => '订餐交易提醒', 'keynote1' => '订单未支付', 'keynote2' => date("Y年m月d日H时i分s秒"), 'remark' => '预订成功，感谢您的光临，欢迎下次再次光临！'));
// 				$this->success('预定成功,进入您的订单页', U('Repast/myOrder',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid,'success'=>1)));
// 			}		

			if ($_POST['paymode'] == 4 && $this->fans['balance'] && $dishCompany['payonline']) {
				$this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Repast', 'orderName' => $userInfo['orderid'], 'single_orderid' => $userInfo['orderid'], 'price' => $price)));
			} else {
				$this->success('正在提交中...', U('Alipay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Repast', 'orderName' => $userInfo['orderid'], 'single_orderid' => $userInfo['orderid'], 'price' => $price)));
			}
		} else {
			$this->error('订单出错，请重新下单');
		}
	}
	
	/**
	 * 清空我的菜单
	 */
	public function clearMyMenu() {
		$_SESSION[$this->session_dish_info] = null;
		unset($_SESSION[$this->session_dish_info]);
	}
	
	
	/**
	 * 我的订单记录
	 */
	public function myOrder() {
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = array('cid' => $this->_cid, 'wecha_id' => $this->wecha_id);
		if ($status == 4) {
			$where['isuse'] = 1;
			$where['paid'] = 1;
		} elseif ($status == 3) {
			$where['isuse'] = 0;
			$where['paid'] = 1;
		} elseif ($status == 2) {
			$where['isuse'] = 1;
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where['isuse'] = 0;
			$where['paid'] = 0;
		}
		$dish_order = M('Dish_order')->where($where)->order('id DESC')->select();
		$list = array();
		foreach ($dish_order as $row) {
			$row['info'] = unserialize($row['info']);
			$list[] = $row;
		}
		$this->assign('orderList', $list);
		$this->assign('status', $status);
		$this->assign('metaTitle', '我的订单');
		$this->display();
	}
	
	
	/**
	 * 点餐信息
	 */
	public function getDishMenu() {
		if (!isset($_SESSION[$this->session_dish_info]) || !strlen($_SESSION[$this->session_dish_info])) {
			$dish = array();
		} else {
			$dish = unserialize($_SESSION[$this->session_dish_info]);
		}
		return $dish;
	}
	
	/**
	 * 支付成功后的回调函数
	 */
	public function payReturn() {
	   $orderid = $_GET['orderid'];
	   if ($order = M('dish_order')->where(array('orderid' => $orderid, 'token' => $this->token))->find()) {
			//TODO 发货的短信提醒
			if ($order['paid']) {
				$temp = unserialize($order['info']);
				$takeAwayPrice = $temp['takeAwayPrice'];
				$company = M("Company")->where(array('token' => $this->token, 'id' => $order['cid']))->find();
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'des' => $order['des'], 'companytel' => $company['tel'], 'truename' => $order['name'], 'tel' => $order['tel'], 'takeAwayPrice' => $takeAwayPrice, 'address' => $order['address'], 'buytime' => $order['time'], 'orderid' => $order['orderid'], 'sendtime' => $order['reservetime'], 'price' => $order['price'], 'total' => $order['nums'], 'list' => $temp['list']);
				
				$msg['typename'] =  $order['takeaway'] == 1 ? '外卖' : ($order['takeaway'] == 2 ? '现在点餐' : '预约点餐');
				if ($order['tableid']) {
					$t_table = M("Dining_table")->where(array('id' => $order['tableid']))->find();
					$msg['tablename'] = isset($t_table['name']) ? $t_table['name'] : '';
				}
				
				$msg = ArrayToStr::array_to_str($msg);
				$op->printit($this->token, $this->_cid, 'Repast', $msg, 1);
				
				Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
				$model = new templateNews();
				$model->sendTempMsg('TM00820', array('href' => U('Repast/myOrder',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)), 'wecha_id' => $this->wecha_id, 'first' => '订餐交易提醒', 'keynote1' => '订单已支付', 'keynote2' => date("Y年m月d日H时i分s秒"), 'remark' => '预订成功，感谢您的光临，欢迎下次再次光临！'));
			}
			$this->redirect(U('Repast/myOrder', array('token'=>$this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid)));
	   }else{
	      exit('订单不存在');
	    }
	}
}
?>