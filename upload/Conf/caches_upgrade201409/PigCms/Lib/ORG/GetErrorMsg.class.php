<?php

//-------------------------------
// 微信平台和cUrl错误号信息对照表
//------------------------------
class GetErrorMsg{

	//微信错误号列表
	 public static function wx_error_msg($code){
		//if(!is_numeric($code)){
		//	return "非法错误号！";
		//}
		if($code == -1){
            return "微信平台系统繁忙";
        }
        $error_codes = array(
            "40001"=>"获取access_token时AppSecret错误，或者access_token无效",
            "40002"=>"不合法的凭证类型",
            "40003"=>"不合法的OpenID",
            "40004"=>"不合法的媒体文件类型",
            "40005"=>"不合法的文件类型",
            "40006"=>"不合法的文件大小",
            "40007"=>"不合法的媒体文件id",
            "40008"=>"不合法的消息类型",
            "40009"=>"不合法的图片文件大小",
            "40010"=>"不合法的语音文件大小",
            "40011"=>"不合法的视频文件大小",
            "40012"=>"不合法的缩略图文件大小",
            "40013"=>"不合法的APPID",
            "40014"=>"不合法的access_token",
            "40015"=>"不合法的菜单类型",
            "40016"=>"不合法的按钮个数",
            "40017"=>"不合法的按钮个数",
            "40018"=>"不合法的按钮名字长度",
            "40019"=>"不合法的按钮KEY长度",
            "40020"=>"不合法的按钮URL长度",
            "40021"=>"不合法的菜单版本号",
            "40022"=>"不合法的子菜单级数",
            "40023"=>"不合法的子菜单按钮个数",
            "40024"=>"不合法的子菜单按钮类型",
            "40025"=>"不合法的子菜单按钮名字长度",
            "40026"=>"不合法的子菜单按钮KEY长度",
            "40027"=>"不合法的子菜单按钮URL长度",
            "40028"=>"不合法的自定义菜单使用用户",
            "40029"=>"不合法的oauth_code",
            "40030"=>"不合法的refresh_token",
            "40031"=>"不合法的openid列表",
            "40032"=>"不合法的openid列表长度",
            "40033"=>"不合法的请求字符，不能包含\uxxxx格式的字符",
            "40035"=>"不合法的参数",
            "40038"=>"不合法的请求格式",
            "40039"=>"不合法的URL长度",
            "40050"=>"不合法的分组id",
            "40051"=>"分组名字不合法",
            "41001"=>"缺少access_token参数",
            "41002"=>"缺少appid参数",
            "41003"=>"缺少refresh_token参数",
            "41004"=>"缺少secret参数",
            "41005"=>"缺少多媒体文件数据",
            "41006"=>"缺少media_id参数",
            "41007"=>"缺少子菜单数据",
            "41008"=>"缺少oauth code",
            "41009"=>"缺少openid",
            "42001"=>"access_token超时",
            "42002"=>"refresh_token超时",
            "42003"=>"oauth_code超时",
            "43001"=>"需要GET请求",
            "43002"=>"需要POST请求",
            "43003"=>"需要HTTPS请求",
            "43004"=>"需要接收者关注",
            "43005"=>"需要好友关系",
            "44001"=>"多媒体文件为空",
            "44002"=>"POST的数据包为空",
            "44003"=>"图文消息内容为空",
            "44004"=>"文本消息内容为空",
            "45001"=>"多媒体文件大小超过限制",
            "45002"=>"消息内容超过限制",
            "45003"=>"标题字段超过限制",
            "45004"=>"描述字段超过限制",
            "45005"=>"链接字段超过限制",
            "45006"=>"图片链接字段超过限制",
            "45007"=>"语音播放时间超过限制",
            "45008"=>"图文消息超过限制",
            "45009"=>"接口调用超过限制",
            "45010"=>"创建菜单个数超过限制",
            "45015"=>"回复时间超过限制",
            "45016"=>"系统分组，不允许修改",
            "45017"=>"分组名字过长",
            "45018"=>"分组数量超过上限",
            "46001"=>"不存在媒体数据",
            "46002"=>"不存在的菜单版本",
            "46003"=>"不存在的菜单数据",
            "46004"=>"不存在的用户",
            "47001"=>"解析JSON/XML内容错误",
            "48001"=>"api功能未授权",
            "50001"=>"用户未授权该api",
        );
        if(isset($error_codes[$code])){
            return $error_codes[$code];
        }else{
            return "错误号：{$code},未知错误";
        }
	
	}
	
	//cUrl error code
	
	 public static function cUrl_error_msg($code){
		$error_codes=array(
			1 => 'CURLE_UNSUPPORTED_PROTOCOL', 
			2 => 'CURLE_FAILED_INIT', 
			3 => 'CURLE_URL_MALFORMAT', 
			4 => 'CURLE_URL_MALFORMAT_USER', 
			5 => 'CURLE_COULDNT_RESOLVE_PROXY', 
			6 => 'CURLE_COULDNT_RESOLVE_HOST', 
			7 => 'CURLE_COULDNT_CONNECT', 
			8 => 'CURLE_FTP_WEIRD_SERVER_REPLY',
			9 => 'CURLE_REMOTE_ACCESS_DENIED',
			11 => 'CURLE_FTP_WEIRD_PASS_REPLY',
			13 => 'CURLE_FTP_WEIRD_PASV_REPLY',
			14 =>'CURLE_FTP_WEIRD_227_FORMAT',
			15 => 'CURLE_FTP_CANT_GET_HOST',
			17 => 'CURLE_FTP_COULDNT_SET_TYPE',
			18 => 'CURLE_PARTIAL_FILE',
			19 => 'CURLE_FTP_COULDNT_RETR_FILE',
			21 => 'CURLE_QUOTE_ERROR',
			22 => 'CURLE_HTTP_RETURNED_ERROR',
			23 => 'CURLE_WRITE_ERROR',
			25 => 'CURLE_UPLOAD_FAILED',
			26 => 'CURLE_READ_ERROR',
			27 => 'CURLE_OUT_OF_MEMORY',
			28 => 'CURLE_OPERATION_TIMEDOUT',
			30 => 'CURLE_FTP_PORT_FAILED',
			31 => 'CURLE_FTP_COULDNT_USE_REST',
			33 => 'CURLE_RANGE_ERROR',
			34 => 'CURLE_HTTP_POST_ERROR',
			35 => 'CURLE_SSL_CONNECT_ERROR',
			36 => 'CURLE_BAD_DOWNLOAD_RESUME',
			37 => 'CURLE_FILE_COULDNT_READ_FILE',
			38 => 'CURLE_LDAP_CANNOT_BIND',
			39 => 'CURLE_LDAP_SEARCH_FAILED',
			41 => 'CURLE_FUNCTION_NOT_FOUND',
			42 => 'CURLE_ABORTED_BY_CALLBACK',
			43 => 'CURLE_BAD_FUNCTION_ARGUMENT',
			45 => 'CURLE_INTERFACE_FAILED',
			47 => 'CURLE_TOO_MANY_REDIRECTS',
			48 => 'CURLE_UNKNOWN_TELNET_OPTION',
			49 => 'CURLE_TELNET_OPTION_SYNTAX',
			51 => 'CURLE_PEER_FAILED_VERIFICATION',
			52 => 'CURLE_GOT_NOTHING',
			53 => 'CURLE_SSL_ENGINE_NOTFOUND',
			54 => 'CURLE_SSL_ENGINE_SETFAILED',
			55 => 'CURLE_SEND_ERROR',
			56 => 'CURLE_RECV_ERROR',
			58 => 'CURLE_SSL_CERTPROBLEM',
			59 => 'CURLE_SSL_CIPHER',
			60 => 'CURLE_SSL_CACERT',
			61 => 'CURLE_BAD_CONTENT_ENCODING',
			62 => 'CURLE_LDAP_INVALID_URL',
			63 => 'CURLE_FILESIZE_EXCEEDED',
			64 => 'CURLE_USE_SSL_FAILED',
			65 => 'CURLE_SEND_FAIL_REWIND',
			66 => 'CURLE_SSL_ENGINE_INITFAILED',
			67 => 'CURLE_LOGIN_DENIED',
			68 => 'CURLE_TFTP_NOTFOUND',
			69 => 'CURLE_TFTP_PERM',
			70 => 'CURLE_REMOTE_DISK_FULL',
			71 => 'CURLE_TFTP_ILLEGAL',
			72 => 'CURLE_TFTP_UNKNOWNID',
			73 => 'CURLE_REMOTE_FILE_EXISTS',
			74 => 'CURLE_TFTP_NOSUCHUSER',
			75 => 'CURLE_CONV_FAILED',
			76 => 'CURLE_CONV_REQD',
			77 => 'CURLE_SSL_CACERT_BADFILE',
			78 => 'CURLE_REMOTE_FILE_NOT_FOUND',
			79 => 'CURLE_SSH',
			80 => 'CURLE_SSL_SHUTDOWN_FAILED',
			81 => 'CURLE_AGAIN',
			82 => 'CURLE_SSL_CRL_BADFILE',
			83 => 'CURLE_SSL_ISSUER_ERROR',
			84 => 'CURLE_FTP_PRET_FAILED',
			84 => 'CURLE_FTP_PRET_FAILED',
			85 => 'CURLE_RTSP_CSEQ_ERROR',
			86 => 'CURLE_RTSP_SESSION_ERROR',
			87 => 'CURLE_FTP_BAD_FILE_LIST',
			88 => 'CURLE_CHUNK_FAILED'
			);
			if(isset($error_codes[$code])){
				return $error_codes[$code];
			}else{
				return "错误号：{$code},未知错误";
			}
	}

}