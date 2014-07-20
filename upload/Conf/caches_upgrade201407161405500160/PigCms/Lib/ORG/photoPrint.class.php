<?php
class photoPrint {

	public $wxid;
	public $openid;
	public $serverUrl;
	public $key;
	public $topdomain;
	public function __construct($wxuser,$openid){
		$this->wxid=$wxuser['wxid'];
		$this->openid=$openid;
		$this->serverUrl='http://up.pigcms.cn/';
		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
	}
	public function initUser(){
		$url=$this->serverUrl.'server.php?m=server&c=photoPrint&a=initUser&key='.$this->key.'&domain='.$this->topdomain.'&openid='.$this->openid.'&wxid='.$this->wxid;
		$rt=$this->curlGet($url);
	}
	public function uploadPic($picUrl){
		$downurl=urlencode($picUrl);
		$url=$this->serverUrl.'server.php?m=server&c=photoPrint&a=initUser&key='.$this->key.'&domain='.$this->topdomain.'&openid='.$this->openid.'&wxid='.$this->wxid.'&picUrl='.$downurl;
		$rt=$this->curlGet($url);
		$arr=json_decode($rt,1);
		if ($arr['msg']){
			return array($arr['msg'],'text');
		}
		return $arr;
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
		
	}
	
	function getTopDomain(){
		$host=$_SERVER['HTTP_HOST'];
		$host=strtolower($host);
		if(strpos($host,'/')!==false){
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str='';
		foreach($topleveldomaindb as $v){
			$str.=($str ? '|' : '').$v;
		}
		$matchstr="[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if(preg_match("/".$matchstr."/ies",$host,$matchs)){
			$domain=$matchs['0'];
		}else{
			$domain=$host;
		}
		return $domain;
	}
}
