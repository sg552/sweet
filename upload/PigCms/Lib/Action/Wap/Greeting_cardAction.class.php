<?php
class Greeting_cardAction extends BaseAction{
	public function index(){
		if(isset($_GET['id'])){
			$data['id']=$this->_get('id','intval');
			$greeting=D('Greeting_card');
			$greeting_card=$greeting->where($data)->find();
			$greeting->where($data)->setInc("click");
		}else{ 
			$greeting_card['name']=$this->_get('name','htmlspecialchars');
			$greeting_card['info']=$this->_get('info','htmlspecialchars');
			$greeting_card['id']=$data['id'];
			$type=$this->_get('type','htmlspecialchars');
		}
		
		$greeting_card['type']=$this->type($type);
		//dump($greeting_card['type']);
		$this->assign('greeting_card',$greeting_card);
		if($type==5){ $str='donkey';}
		$this->display($str);
	}
	private function type($type){
		switch($type){
			case 1:
			$type='realLeaf';
			break;
			case 2:
			$type='snow';
			break;
			case 0:
			$type='flower_';
			case 3:
			$type='meigui';
			break;
			case 4:
			$type='meigui';
			break;
			case 5:
			$type='meigui';
			break;
		
		}
		return $type;
	}

}
?>

