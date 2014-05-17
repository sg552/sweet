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
		$info=$db->where($where)->order('usort DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);

		$this->display();
		
	}
	
	public function add(){
		$classify_db=M('Classify');

		$class=$classify_db->field("fid,id,name,concat(path,'-',id) as bpath")->order('bpath ASC')->where(array('token'=>session('token')))->select();
		foreach($class as $k=>$v){
			$total=(count(explode('-',$v['bpath']))-2)*10;
				for($i=0;$i<$total;$i++){

					$class[$k]['fg'].='-';
				}

			$id = $v['id'];
			$fidArr[] = $classify_db->field('distinct(fid)')->where(array('token'=>session('token'),"fid"=>$id))->select();
			if(!$fidArr[$k][0]['fid'] == NULL){
				$fid[] = $fidArr[$k][0]['fid'];
			}
		}

		
		if($class==false){$this->error('请先添加3G网站分类',U('Classify/index',array('token'=>session('token'))));}
		$this->assign('info',$class);
		$this->assign('fid',$fid);
		$this->display();
	}
	
	
	public function edit(){
		$db=M('Classify');
		$where['token']=session('token');

		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$res=D('Img')->where($where)->find();
		
		$thisClass=M('Classify')->field('id,path')->where(array('id'=>$res['classid']))->find();

		$path = $thisClass['path'].'-'.$thisClass['id'];
		$tree = explode('-',$path);
		foreach($tree as $k=>$v){
			if($v != 0){
				$name[] = $db->field("name")->where(array('token'=>session('token'),'id'=>$v))->find();
			}else{
				unset($tree[$k]);
			}
		}
		foreach ($name as $key=>$val){
			$t .= $val['name'].' >> ';
			$lastName = $val['name'];
		}

		$t = rtrim($t,' >> ');

		$this->assign('classValue',array_pop($tree).','.$lastName);
		
		$this->assign('thisClass',$thisClass);
		$this->assign('classtree',$t);
		$this->assign('fid',$fid);
		$this->assign('info',$res);
		$this->assign('class',$class);
		$this->assign('res',$class);
		$this->display();
	}
	
	
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['token']=$this->token;
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
	
	public function editClass(){
		$token = $this->token;
		$db = M('Classify');
		$id = $this->_get('id','intval');
		$class = $db->field('id,name,path')->where("token = '$token' AND fid = $id")->select();
		
		foreach($class as $k=>$v){
		
			$fid = $v['id'];
			$class[$k]['sub'] = $db->where("token = '$token' AND fid = $fid")->field('id,name')->select();
		}
		
				

		$this->assign('class',$class);
		
		$this->display();
	}
	
	public function editUsort(){
		$id = $this->_post('id','intval');
		$usort = $this->_post('v','intval');
		$token = $this->_post('token',"htmlspecialchars");
		
		if(M('Img')->where("token = '$token' AND id = $id")->setField('usort',$usort)){
			echo $usort;
		}else{
			echo 'error';
		}
	}
}
?>