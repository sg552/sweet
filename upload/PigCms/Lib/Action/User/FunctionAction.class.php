<?php
class FunctionAction extends UserAction{
	function index(){
		$id=$this->_get('id','intval');

		if (!$id){
			//$token=$this->token;
			$info=M('Wxuser')->find(array('where'=>array('token'=>$this->token)));
		}else {
			$info=M('Wxuser')->find($id);
		}
		
		$token=$this->_get('token','trim');	
		if($info==false||$info['token']!=$token){
			$this->error('非法操作',U('Home/Index/index'));
		}
		session('token',$token);
		session('wxid',$info['id']);
		session('companyid', null);
		//第一次登陆　创建　功能所有权
		$token_open=M('Token_open');
		
		$toback=$token_open->field('id,queryname')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$open['uid']=session('uid');
		$open['token']=session('token');
		//遍历功能列表
		if (!C('agent_version')){
			$group=M('User_group')->field('id,name,func')->where('status=1 AND id = '.session('gid'))->order('id ASC')->find();
			$funcs = M('Function')->where("1 = 1")->select();
		}else {
			$group=M('User_group')->field('id,name,func')->where('status=1 AND agentid='.$this->agentid.' AND id = '.session('gid'))->order('id ASC')->find();
			$funcs = M('Agent_function')->where(array('agentid'=>$this->agentid))->select();
		}

		$check=explode(',',trim($toback['queryname'],','));
/*
		foreach ($check as $ck => $cv){
			if(strpos($group['func'],$cv) === false){
				$group['func'] .= ','.$cv;
			}
		
		}

		
*/
		$group['func'] = explode(',',$group['func']);
		
			foreach ($group['func'] as $gk=>$gv){
				
					$open_func = M('Token_open')->where(array('token'=>session('token'),'uid'=>session('uid')))->getField('queryname');

					if(strpos($open_func,$gv) === false){
						$where = array('funname'=>$gv,'status'=>1);
					}else{
						$where = array('funname'=>$gv);
					}
					
					if (C('agent_version')&&$this->agentid){
						$where['agentid'] = $this->agentid;
						$group['func'][$gk] = M('Agent_function')->where($where)->field('id,funname,name,info')->find();
					}else{
						$group['func'][$gk] = M('Function')->where($where)->field('id,funname,name,info')->find();
					}
					
				if($group['func'][$gk] == NULL){
					unset($group['func'][$gk]);
				}
			}
			
			
		$this->assign('fun',$group);
		
		//
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		$this->assign('check',$check);
		$this->display();
	}




	function welcome(){

		$data = array();

		$data['mp'] = M('Wxuser')->where(array('uid'=>intval(session('uid'))))->count();
		$data['card'] = M('Member_card_create')->where(array('token'=>$this->token))->count();
		$data['active'] = M('Lottery')->where(array('token'=>$this->token))->count();
		$data['img'] = M('Img')->where(array('token'=>$this->token))->count();
		$this->assign('data',$data);
		// data
		if($this->_get('month')==false){
			$month=date('m');
		}else{
			$month=$this->_get('month');
		}
		$thisYear=date('Y');
		if($this->_get('year')==false){
			$year=$thisYear;
		}else{
			$year=$this->_get('year');
		}
		$this->assign('month',$month);
		$this->assign('year',$year);
		$where=array('token'=>$this->token,'month'=>$month,'year'=>$year);
		$list=M('Requestdata')->where($where)->limit(31)->order('id ASC')->select();

		

		foreach ($list as $k => $v){
			$charts['xAxis'] .= '"'.$v['day'].'日",';
			$charts['follow'] .= '"'.$v['follownum'].'",';
			$charts['text'] .= '"'.$v['textnum'].'",';
			
		}

		function trim_map($val){
			return rtrim($val,',');
		}

		$charts = array_map('trim_map',$charts);

		
		$this->assign('charts',$charts);


		
		$this->display();
	}




}

?>