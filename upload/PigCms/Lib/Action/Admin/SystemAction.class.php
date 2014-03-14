<?php
/**
 *网站后台
 *@package 
 *@author 
 **/
class SystemAction extends BackAction{
	public $server_url;
	public $key;
	public $topdomain;
	public $dirtype;
	public function _initialize() {
		parent::_initialize();
		$this->server_url=trim(C('server_url'));
		if (!$this->server_url){
			$this->server_url='http://up.pigcms.cn/';
		}

		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/Lib')&&is_dir($_SERVER['DOCUMENT_ROOT'].'/Lib')){
			$this->dirtype=2;
		}else {
			$this->dirtype=1;
		}
	}
	public function index(){
		$where['display']=1;
		$where['status']=1;
		$order['sort']='asc';
		$nav=M('node')->where($where)->order($order)->select();
		$this->assign('nav',$nav);
		$this->display();
	}

	public function menu(){
		if(empty($_GET['pid'])){
			$where['display']=2;
			$where['status']=1;
			$where['pid']=2;
			$where['level']=2;
			$order['sort']='asc';
			$nav=M('node')->where($where)->order($order)->select();
			$this->assign('nav',$nav);
		}
		$this->display();
	}

	public function main(){
		/*
		require_once('test.php');
		if (!class_exists('test')){
		$canEnUpdate=0;
		}else {
		$canEnUpdate=1;
		}
		*/
		$canEnUpdate=1;
		$this->assign('canEnUpdate',$canEnUpdate);

		//
		//
		$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
		if ($updateRecord['lastsqlupdate']>$updateRecord['version']){
			$updateRecord['version']=$updateRecord['lastsqlupdate'];
		}
		$this->assign('updateRecord',$updateRecord);
		$this->display();
	}
	//
	public function _needUpdate(){
		$Model = new Model();
		//检查system表是否存在
		$Model->query("CREATE TABLE IF NOT EXISTS `".C('DB_PREFIX')."system_info` (`lastsqlupdate` INT( 10 ) NOT NULL ,`version` VARCHAR( 10 ) NOT NULL) ENGINE = MYISAM CHARACTER SET utf8");
		$Model->query("CREATE TABLE IF NOT EXISTS `".C('DB_PREFIX')."update_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` varchar(600) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8");
		$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
		//
		$key=$this->key;
		$url=$this->server_url.'server.php?key='.$key.'&lastversion='.$updateRecord['version'].'&domain='.$this->topdomain.'&dirtype='.$this->dirtype;
		$remoteStr=@pigcms_getcontents($url);
		//
		$rt=json_decode($remoteStr,1);
		return $rt;
	}
	public function _needSqlUpdate(){
		$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
		//
		$key=$this->key;
		$url=$this->server_url.'sqlserver.php?key='.$key.'&lastsqlupdate='.$updateRecord['lastsqlupdate'].'&domain='.$this->topdomain.'&dirtype='.$this->dirtype;
		$remoteStr=pigcms_getcontents($url);

		//
		$rt=json_decode($remoteStr,1);
		return $rt;
	}
	public function checkUpdate(){
		$rt=$this->_needUpdate();
		$needUpdate=0;
		if ($rt['success']<1){
			$sqlrt=$this->_needSqlUpdate();
			if ($sqlrt['success']<1){
			}else {
				$needUpdate=1;
			}
		}else {
			$needUpdate=1;
		}
		$this->assign('needUpdate',$needUpdate);

		$this->display();
	}
	public function doUpdate(){
		$cannotWrite=0;
		if (!class_exists('ZipArchive')){
			$this->error('您的服务器不支持php zip扩展，请配置好此扩展再来升级',U('System/main'));
		}
		if (!isset($_GET['ignore'])){
			if (!is_writable($_SERVER['DOCUMENT_ROOT'].'/PigCms')){
				$cannotWrite=1;
				$this->error('您的服务器PigCms文件夹不可写入，设置好再升级',U('System/main'));
			}
			if (!is_writable($_SERVER['DOCUMENT_ROOT'].'/tpl')){
				$this->error('您的服务器tpl文件夹不可写入，设置好再升级',U('System/main'));
			}
		}
		/*
		require_once('test.php');
		if (!class_exists('test')){
		$this->success('检查更新',U('System/doSqlUpdate'));
		}
		*/
	
		//
		$now=time();
		$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
		$key=$this->key;
		$url=$this->server_url.'server.php?key='.$key.'&lastversion='.$updateRecord['version'].'&domain='.$this->topdomain.'&dirtype='.$this->dirtype;
		$remoteStr=@pigcms_getcontents($url);
		//
		$rt=json_decode($remoteStr,1);
		if (intval($rt['success'])<1){
			if (intval($rt['success'])==0){
				if (!isset($_GET['ignore'])){
					$this->success('继续检查更新了'.$rt['msg'],U('System/doSqlUpdate'));
				}else {
					$this->success('继续检查更新了'.$rt['msg'],U('System/doSqlUpdate',array('ignore'=>1)));
				}
			}else {
				$this->success($rt['msg'],U('System/main'));
			}
		}else {
			$locationZipPath=RUNTIME_PATH.$now.'.zip';
			$filename=$this->server_url.'server.php?getFile=1&key='.$key.'&lastversion='.$updateRecord['version'].'&domain='.$this->topdomain.'&dirtype='.$this->dirtype;
			@file_put_contents($locationZipPath,@pigcms_getcontents($filename));
			//
			$zip = new ZipArchive();

			$rs = $zip->open($locationZipPath);
			if($rs !== TRUE)
			{
				$this->error('解压失败_2!Error Code:'. $rs);
			}
			//
			$cacheUpdateDirName='caches_upgrade'.date('Ymd',time());
			if(!file_exists(RUNTIME_PATH.$cacheUpdateDirName)) {
				@mkdir(RUNTIME_PATH.$cacheUpdateDirName,0777);
			}
			//
			$zip->extractTo(RUNTIME_PATH.$cacheUpdateDirName);
			recurse_copy(RUNTIME_PATH.$cacheUpdateDirName,$_SERVER['DOCUMENT_ROOT']);
			$zip->close();
			//delete
			if (!$cannotWrite){
				@deletedir(RUNTIME_PATH.$cacheUpdateDirName);
			}
			@unlink($locationZipPath);
			//record to database
			if ($rt['time']){
				M('System_info')->where(array('version'=>$updateRecord['version']))->save(array('version'=>$rt['time']));
				M('Update_record')->add(array('msg'=>$rt['msg'],'time'=>$rt['time'],'type'=>$rt['type']));
			}
			if (isset($_GET['ignore'])){
				$this->success('进入下一步:'.$rt['msg'],U('System/doUpdate',array('ignore'=>1)));
			}else {
				$this->success('进入下一步:'.$rt['msg'],U('System/doUpdate'));
			}
		}
	}
	public function doSqlUpdate(){
		//
		$now=time();
		$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
		$key=$this->key;
		$url=$this->server_url.'sqlserver.php?key='.$key.'&excute=1&lastsqlupdate='.$updateRecord['lastsqlupdate'].'&domain='.$this->topdomain.'&dirtype='.$this->dirtype;
		$remoteStr=pigcms_getcontents($url);
		//
		$rt=json_decode($remoteStr,1);
		if (intval($rt['success'])<1){
			if (intval($rt['success'])==0){
				$this->success('升级完成',U('System/main'));
			}else {
				$this->error($rt['msg'],U('System/main'));
			}
		}else {
			$Model = new Model();
			error_reporting(0);
			@mysql_query(str_replace('{tableprefix}',C('DB_PREFIX'),$rt['sql']));
			//record to database
			if ($rt['time']){
				M('System_info')->where(array('lastsqlupdate'=>$updateRecord['lastsqlupdate']))->save(array('lastsqlupdate'=>$rt['time']));
			}
			if (!isset($_GET['ignore'])){
				$this->success('进入下一步:'.$rt['msg'],U('System/doSqlUpdate'));
			}else {
				$this->success('进入下一步:'.$rt['msg'],U('System/doSqlUpdate',array('ignore'=>1)));
			}

		}
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
}
function recurse_copy($src,$dst) {  // 原目录，复制到的目录

	$dir = opendir($src);
	@mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				recurse_copy($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}
function deletedir($dirname){
	$result = false;
	if(! is_dir($dirname)){
		echo " $dirname is not a dir!";
		exit(0);
	}
	$handle = opendir($dirname); //打开目录
	while(($file = readdir($handle)) !== false) {
		if($file != '.' && $file != '..'){ //排除"."和"."
			$dir = $dirname.DIRECTORY_SEPARATOR.$file;
			//$dir是目录时递归调用deletedir,是文件则直接删除
			is_dir($dir) ? deletedir($dir) : unlink($dir);
		}
	}
	closedir($handle);
	$result = rmdir($dirname) ? true : false;
	return $result;
}
function pigcms_getcontents($url){
	if (function_exists('curl_init')){
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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}else {
		$str=file_get_contents($url);
		return $str;
	}
}