<?php
class FunctionLibrary_invite{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'邀请函',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$game=new game();
			$items=M('invite')->select(array('token'=>$this->token));
			$arr=array(
			'name'=>'邀请函',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$link=$game->getLink($v,'{wechat_id}');
					$arr['subkeywords'][$v['id']]=array('name'=>$v['title'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['title'],'link'=>$link);
				}
			}
			return $arr;
		}
		
	}
}