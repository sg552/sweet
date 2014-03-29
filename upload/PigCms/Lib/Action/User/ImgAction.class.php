<?php
/**
 *文本回复
**/
class ImgAction extends UserAction{
	public function index(){
		$db=D('Img');
		//$where['uid']=session('uid');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	public function add(){
		$classify_db=M('Classify');
		$class=$classify_db->where(array('token'=>session('token')))->select();

		//区分二级分类

		
		$where=array('token'=>session('token'),'fid'=>0);
		$class=$classify_db->where($where)->select();
		foreach($class as $key=>$val){
			$class[$key]['vo']=$classify_db->where(array('token'=>session('token'),'fid'=>$val['id']))->select();
		}
		if($class==false){$this->error('请先添加3G网站分类',U('Classify/index',array('token'=>session('token'))));}
		$this->assign('info',$class);
		$this->display();
	}
	public function edit(){
		$db=M('Classify');
		$where['token']=session('token');
		//$info=$db->where($where)->select();
		$class=$db->where(array('token'=>session('token'),'fid'=>0))->select();
		foreach($class as $key=>$val){
			$class[$key]['vo']=$db->where(array('token'=>session('token'),'fid'=>$val['id']))->select();
		}
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$res=D('Img')->where($where)->find();
		$this->assign('info',$res);
		$this->assign('res',$class);
		$this->display();
	}
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		if(D(MODULE_NAME)->where($where)->delete()){
			M('Keyword')->where(array('pid'=>$this->_get('id','intval'),'token'=>session('token'),'module'=>'Img'))->delete();
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	public function insert(){
		//$pat = "/<(\/?)(script|i?frame|style|html|body|title|font|strong|span|div|marquee|link|meta|\?|\%)([^>]*?)>/isU";
		//$_POST['info'] = preg_replace($pat,"",$_POST['info']);
		//$_POST['info']=strip_tags($this->_post('info'),'<a> <p> <br>'); 
		$_POST['info']=str_replace('\'','&apos;',$_POST['info']);
		//dump($_POST['info']);
		$usersdata=M('Users');
		$usersdata->where(array('id'=>$this->user['id']))->setInc('diynum');
		$this->all_insert();
	}
	public function upsave(){
		$_POST['info']=str_replace('\'','&apos;',$_POST['info']);
		$this->all_save();
	}
}
?>