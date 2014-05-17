<?php
class PhotoAction extends UserAction{
	public function index(){
		$reply_info_db=M('Reply_info');
		$config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
		if ($config){
			$headpic=$config['picurl'];
		}else {
			$headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
		}
		$this->assign('headpic',$headpic);
		//
		$this->canUseFunction('album');
		//相册列表
		$data=M('Photo');
		$count      = $data->where(array('token'=>$_SESSION['token']))->count();
		$Page       = new Page($count,12);
		$show       = $Page->show();
		$list = $data->where(array('token'=>$_SESSION['token']))->limit($Page->firstRow.','.$Page->listRows)->select();	
		$this->assign('page',$show);		
		$this->assign('photo',$list);
		$this->display();		
	}
	public function config(){
		$reply_info_db=M('Reply_info');
		$config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
		$configArr=array();
		$configArr['title']='相册';
		$configArr['info']='';
		$configArr['picurl']=$this->_post('picurl');
		$configArr['token']=$this->token;
		$configArr['apiurl']='';
		$configArr['infotype']='album';
		//
		if ($config){
			$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->save($configArr);
		}else {
			$reply_info_db->add($configArr);
		}
		$this->success('操作成功');
	}
	public function edit(){
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$data=D('Photo');
		if(IS_POST){
			$this->all_save('Photo');
		}else{
			$photo=$data->where(array('token'=>session('token'),'id'=>$this->_get('id')))->find();
			if($photo==false){
				$this->error('相册不存在');
			}else{
				$this->assign('photo',$photo);
			}
			$this->display();		
		
		}
	}
	public function list_edit(){
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$check=M('Photo_list')->field('id,pid')->where(array('token'=>$_SESSION['token'],'id'=>$this->_post('id')))->find();
		if($check==false){$this->error('照片不存在');}
		if(IS_POST){
			$this->all_save('Photo_list','/list_add?id='.$check['pid']);		
		}else{
			$this->error('非法操作');
		}
	}
	public function list_del(){
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$check=M('Photo_list')->field('id,pid')->where(array('token'=>$_SESSION['token'],'id'=>$this->_get('id')))->find();
		if($check==false){$this->error('服务器繁忙');}
		if(empty($_POST['edit'])){
			if(M('Photo_list')->where(array('id'=>$check['id']))->delete()){
				M('Photo')->where(array('id'=>$check['pid']))->setDec('num');
				$this->success('操作成功');
			}else{
				$this->error('服务器繁忙,请稍后再试');
			}
		}
	}
	public function list_add(){
		
		$checkdata=M('Photo')->where(array('token'=>$_SESSION['token'],'pid'=>$this->_get('pid')))->find();
		if($checkdata==false){$this->error('相册不存在');}
		if(IS_POST){			
			M('Photo')->where(array('token'=>session('token'),'id'=>$this->_post('pid')))->setInc('num');
			$this->all_insert('Photo_list','/list_add?id='.$_POST['pid']);						
		}else{
			$data=M('Photo_list');
			$count      = $data->where(array('token'=>$_SESSION['token'],'pid'=>$this->_get('pid')))->count();
			$Page       = new Page($count,120);
			$show       = $Page->show();
			$list = $data->where(array('token'=>$_SESSION['token'],'pid'=>$this->_get('id')))->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();	
			$this->assign('page',$show);		
			$this->assign('photo',$list);
			$this->display();	
		
		}
		
	}
	public function add(){
		if(IS_POST){			
			$this->all_insert('Photo','/add');			
		}else{
			$this->display();	
		
		}
		
	}
	public function del(){
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$check=M('Photo')->field('id')->where(array('token'=>$_SESSION['token'],'id'=>$this->_get('id')))->find();
		if($check==false){$this->error('服务器繁忙');}
		if(empty($_POST['edit'])){
			if(M('Photo')->where(array('id'=>$check['id']))->delete()){
				M('Photo_list')->where(array('pid'=>$check['id']))->delete();
				$this->success('操作成功');
			}else{
				$this->error('服务器繁忙,请稍后再试');
			}
		}
	
	}


}


?>