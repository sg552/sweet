<?php
class GameAction extends BaseAction{
	public $token;
	public $gameConfig;
	public $uid;

	public function _initialize() {
		parent::_initialize();
		$this->uid=intval($_GET['uid']);
		$this->gameConfig=M('Game_config')->where(array('uid'=>$this->uid))->find();
		$this->token=$this->gameConfig['token'];
		//
	}
	function api_playuser(){
		$wecha_id=$_GET['openid'];
		$score=$_GET['score'];
		$gameid=intval($_GET['gameid']);
		
		if ($_GET['key']==$this->gameConfig['key']){
			$data=array(
			'token'=>$this->token,
			'gameid'=>$gameid,
			'wecha_id'=>$wecha_id,
			'score'=>$score,
			'time'=>time()
			);
			M('game_records')->add($data);
		}
	}
	function api_playcount(){
		if ($_GET['key']==$this->gameConfig['key']){
			M('games')->where(array('id'=>intval($_GET['gameid'])))->setInc('playcount');
		}
	}
}


?>