<?php
class IndexAction extends BaseAction{
	public $includePath;
	protected function _initialize(){
		parent::_initialize();
		$this->home_theme=$this->home_theme?$this->home_theme:'pigcms';
		$this->includePath='./tpl/Home/'.$this->home_theme.'/';

		$this->assign('includeHeaderPath',$this->includePath.'Public_header.html');
		$this->assign('includeFooterPath',$this->includePath.'Public_footer.html');

	}
	public function clogin()
	{
		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
		$k = isset($_GET['k']) ? $_GET['k'] : '';
		$this->assign('cid', $cid);
		$this->assign('k', $k);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	//关注回复
	public function index(){
		$where['status']=1;
		if (C('agent_version')){
			$where['agentid']=$this->agentid;
		}
		$links=D('Links')->where($where)->select();
		$this->assign('links',$links);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function verify(){
		Image::buildImageVerify(4,1,'png',0,28,'verify');
	}
	public function verifyLogin(){
		Image::buildImageVerify(4,1,'png',0,28,'loginverify');
	}
	public function resetpwd(){
		$uid=$this->_get('uid','intval');
		$code=$this->_get('code','trim');
		$rtime=$this->_get('resettime','intval');
		$info=M('Users')->find($uid);
		if( (md5($info['uid'].$info['password'].$info['email'])!==$code) || ($rtime<time()) ){
			$this->error('非法操作',U('Index/index'));
		}
		$this->assign('uid',$uid);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function fc(){
		if (isset($_GET['id'])){
			$fun=M('Funintro')->where(array('id'=>intval($_GET['id']),'type'=>0))->find();
		}else {
			$fun=M('Funintro')->where('id>0 and type=0')->order('id ASC')->find();
		}
		$funs=M('Funintro')->where('id>0 and type=0')->select();
		$this->assign('funs',$funs);
		$this->assign('fun',$fun);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function qrcode(){
		if (isset($_SESSION['preuid'])){
			$thisUser=M('Users')->where(array('id'=>intval($_SESSION['preuid'])))->find();
			$this->assign('thisUser',$thisUser);
			$this->display($this->home_theme.':Index:'.ACTION_NAME);
		}else {
			exit();
		}

	}
	public function qrcodeLogin(){
		if (isset($_SESSION['preuid'])){
			$thisUser=M('Users')->where(array('id'=>intval($_SESSION['preuid'])))->find();
			session('uid',$thisUser['id']);
			session('gid',$thisUser['gid']);
			session('uname',$thisUser['username']);
			session('diynum',0);
			session('connectnum',0);
			session('activitynum',0);
			//session('gname',$info['name']);
			$this->success('现在进入体验',U('User/Index/bindTip'));
		}else {
			$this->success('超时，请联系客服审核账号',U('Home/Index/index'));
		}
	}
	public function isfollow(){
		$thisUser=M('Users')->where(array('id'=>intval($_SESSION['preuid'])))->find();
		echo '{"openid":"'.$thisUser['openid'].'"}';
	}
	public function about(){

		$fun=M('Funintro')->where('type=1')->find();
		$this->assign('fun',$fun);
		//var_dump($funs);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function price(){
		$groupWhere=array();
		$groupWhere['status']=1;
		if (C('agent_version')){
			$groupWhere['agentid']=$this->agentid;
		}
		$groups=M('User_group')->where($groupWhere)->order('id ASC')->select();
		$this->assign('groups',$groups);
		$count=count($groups);
		$this->assign('count',$count);
		//
		$prices=array();
		$isCopyright=array();
		$wechatNums=array();
		$diynums=array();
		$connectnums=array();
		$activitynums=array();
		$create_card_nums=array();
		if ($groups){
			foreach ($groups as $g){
				array_push($prices,$g['price']);
				array_push($isCopyright,$g['iscopyright']);
				array_push($wechatNums,$g['wechat_card_num']);
				array_push($diynums,$g['diynum']);
				array_push($connectnums,$g['connectnum']);
				array_push($activitynums,$g['activitynum']);
				array_push($create_card_nums,$g['create_card_num']);
			}
		}
		$this->assign('prices',$prices);
		$this->assign('copyrights',$isCopyright);

		$this->assign('wechatNums',$wechatNums);
		$this->assign('diynums',$diynums);
		$this->assign('connectnums',$connectnums);
		$this->assign('activitynums',$activitynums);
		$this->assign('create_card_nums',$create_card_nums);
		//
		if (C('agent_version')&&$this->agentid){
			$funs=M('Agent_function')->where(array('status'=>1,'agentid'=>$this->agentid))->order('id ASC')->select();
		}else {
			$funs=M('Function')->where(array('status'=>1))->order('id ASC')->select();
		}
		if ($funs){

			foreach ($funs as $fk => $f){
				$funs[$fk]['access']=array();
				if ($groups){
					foreach ($groups as $g){
						if(strpos($g['func'],$f['funname']) === false){
							$canUse = 0;
						}else{
							$canUse = 1;
						}
						$funs[$fk]['access'][$g['id']] = $canUse;
					}
				}

			}
		}
		$this->assign('funs',$funs);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function help(){
		if (isset($_GET['token'])){
			if (isset($_SESSION['uid'])){
				$thisWx=apiInfo::info($_SESSION['uid'],0,$this->_get('token'));
		
				$this->assign('wxuser',$thisWx);
			}else {
				$this->error('无权查看');
			}
		}
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	function think_encrypt($data, $key = '', $expire = 0) {
		$key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
		$data = base64_encode($data);
		$x    = 0;
		$len  = strlen($data);
		$l    = strlen($key);
		$char = '';

		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) $x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}

		$str = sprintf('%010d', $expire ? $expire + time():0);

		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
		}
		return str_replace('=', '',base64_encode($str));
	}
	function text(){
		$domain=$_GET['domain'];
		$domains=explode('.',$domain);

		echo '<a href="http://'.$domain.'/index.php?g=Home&m=T&a=test&n='.$this->think_encrypt($domains[1].'.'.$domains[2]).'" target="_blank">http://'.$domain.'/index.php?g=Home&m=T&a=test&n='.$this->think_encrypt($domains[1].'.'.$domains[2]).'</a><br>';
		echo '<a href="http://'.$domain.'/index.php?g=User&m=Create&a=index" target="_blank">http://'.$domain.'/index.php?g=User&m=Create&a=index</a><br>';
	}
	function common(){
		$where['status']=1;
		if (C('agent_version')){
			$where['agentid']=$this->agentid;
		}
		$cases=M('Case')->where($where)->order('id DESC')->select();
		$this->assign('cases',$cases);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}

	 public function userJson(){
    	if (C('server_topdomain')=='pigcms.cn'&&$this->_get('key')==C('server_key')){
    		$id=intval($_GET['id']);
    		$users=M('users')->where('id>'.$id)->order('id ASC')->limit(0,400)->select();
    		if ($users){
    			$i=0;
    			foreach ($users as $u){
    				unset($users[$i]['password']);
    				$i++;
    			}
    		}
    	}
    	echo json_encode($users);
    }


	public function login(){
		$business = include('./PigCms/Lib/ORG/Business.php');
		$i=0;
		foreach ($business as $k => $v){
			$data[$i]['key'] = $k;
			$data[$i]['val'] = $v;
			$i++;
		}
		$this->assign('business',$data);
		$this->display('login');
	}
	public function reg(){
		$business = include('./PigCms/Lib/ORG/Business.php');
		$i=0;
		foreach ($business as $k => $v){
			$data[$i]['key'] = $k;
			$data[$i]['val'] = $v;
			$i++;
		}
		$this->assign('business',$data);
		$this->display('login');
	}
}