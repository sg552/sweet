<?php
class FunctionAction extends UserAction{
	function index(){
		$id=$this->_get('id','intval');
		if (!$id){
			$token=$this->token;
			$info=M('Wxuser')->find(array('token'=>$this->token));
		}else {
			$info=M('Wxuser')->find($id);
		}
		$token=$this->_get('token','trim');	
		if($info==false||$info['token']!=$token){
			$this->error('非法操作',U('Home/Index/index'));
		}
		session('token',$token);
		session('wxid',$info['id']);
		//第一次登陆　创建　功能所有权
		$token_open=M('Token_open');
		$toback=$token_open->field('id,queryname')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$open['uid']=session('uid');
		$open['token']=session('token');
		//遍历功能列表
		if (!C('agent_version')){
			$group=M('User_group')->field('id,name')->where('status=1')->order('id ASC')->select();
		}else {
			$group=M('User_group')->field('id,name')->where('status=1 AND agentid='.$this->agentid)->order('id ASC')->select();
		}
		$check=explode(',',$toback['queryname']);
		$this->assign('check',$check);
		foreach($group as $key=>$vo){
			if (C('agent_version')&&$this->agentid){
				$fun=M('Agent_function')->where(array('status'=>1,'gid'=>$vo['id']))->select();
			}else {
				$fun=M('Function')->where(array('status'=>1,'gid'=>$vo['id']))->select();
			}
			
			foreach($fun as $vkey=>$vos){
				$vos['groupName']=$vo['name'];
				$function[$key][$vkey]=$vos;
			}
		}
		$this->assign('fun',$function);
		//
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		//
		$this->display();
	}
}

?>