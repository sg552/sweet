<?php

/**
 * 通用模板管理
 * */
class TmplsAction extends UserAction {

    public function index() {
        $db = D('Wxuser');
        $where['token'] = session('token');
        $where['uid'] = session('uid');
        $info = $db->where($where)->find();
        $this->assign('info', $info);
        //模板提示信息
        $desinfo[1]= "";
        $desinfo[2]="列表式图片模版，缩略图建议使用150*150或近似尺寸比例的图片。";
        $desinfo[3]="列表式图片模版，缩略图建议使用150*150或近似尺寸比例的图片。";
        $desinfo[4]="列表式图片模版，缩略图建议使用150*150或近似尺寸比例的图片。";
        $desinfo[5]="文字标签式模版，顶部幻灯片尺寸为640*320或近似等比例图片。";
        $desinfo[6]="";
        $desinfo[7]="";
        $desinfo[8]="";
        $desinfo[9]="";
        $desinfo[10]="";
        $desinfo[11]="左右图文式模版，顶部幻灯片建议使用尺寸为640*320或近似等比例图片；分类图片建议使用450*300或近似等比例图片，请不要使用高度大于或接近于宽度的图片。";
        $desinfo[12]="";
        $desinfo[13]="";
        $desinfo[14]="";
        $desinfo[15]="";
        
        $this->assign('desinfo',$desinfo);
        //
        $this->display();
    }

    public function add() {
        $gets = $this->_get('style');
        $db = M('Wxuser');
        switch ($gets) {
            case 1:
                $data['tpltypeid'] = 1;
                $data['tpltypename'] = '101_index';
                break;
            case 2:
                $data['tpltypeid'] = 2;
                $data['tpltypename'] = '102_index';
                break;
            case 3:
                $data['tpltypeid'] = 3;
                $data['tpltypename'] = '103_index';
                break;
            case 4:
                $data['tpltypeid'] = 4;
                $data['tpltypename'] = '104_index';
                break;
            case 5:
                $data['tpltypeid'] = 5;
                $data['tpltypename'] = '105_index';
                break;
            case 6:
                $data['tpltypeid'] = 6;
                $data['tpltypename'] = '106_index_ydkds';
                break;
            case 7:
                $data['tpltypeid'] = 7;
                $data['tpltypename'] = '107_index_2d8si';
                break;
            case 8:
                $data['tpltypeid'] = 8;
                $data['tpltypename'] = '108_index_giw93x';
                break;
            case 9:
                $data['tpltypeid'] = 9;
                $data['tpltypename'] = '109_index_0fdis';
                break;
            case 10:
                $data['tpltypeid'] = 10;
                $data['tpltypename'] = '110_index_2skz7';
                break;
            case 11:
                $data['tpltypeid'] = 11;
                $data['tpltypename'] = '111_index_78yus';
                break;
            case 12:
                $data['tpltypeid'] = 12;
                $data['tpltypename'] = '112_index_kj7y5';
                break;
            case 13:
                $data['tpltypeid'] = 13;
                $data['tpltypename'] = '113_index_jks6z';
                break;
            case 14:
                $data['tpltypeid'] = 14;
                $data['tpltypename'] = '114_index_mnsz6';
                break;
			case 15:
                $data['tpltypeid'] = 15;
                $data['tpltypename'] = '115_index_ms76x';
                break;
        }
        $where['token'] = session('token');
        $db->where($where)->save($data);
        //
        M('Home')->where(array('token'=>session('token')))->save(array('advancetpl'=>0));
        if (isset($_GET['noajax'])) {
        	$this->success('设置成功','/index.php?g=User&m=Tmpls&a=index&token='.$this->token);
        }
    }

    public function lists() {
        $gets = $this->_get('style');
        $db = M('Wxuser');
        switch ($gets) {
            case 4:
                $data['tpllistid'] = 4;
                $data['tpllistname'] = 'ktv_list';
                break;
            case 1:
                $data['tpllistid'] = 1;
                $data['tpllistname'] = 'yl_list';
                break;
        }
        $where['token'] = session('token');
        $db->where($where)->save($data);
    }

    public function content() {
        $gets = $this->_get('style');
        $db = M('Wxuser');
        switch ($gets) {
            case 1:
                $data['tplcontentid'] = 1;
                $data['tplcontentname'] = 'yl_content';
                break;
            case 3:
                $data['tplcontentid'] = 3;
                $data['tplcontentname'] = 'ktv_content';
                break;
        }
        $where['token'] = session('token');
        $db->where($where)->save($data);
    }
    
    public function background() {
        $data['color_id'] = $this->_get('style');
        $db = M('Wxuser');
        $where['token'] = session('token');
        $db->where($where)->save($data);
    }

    public function insert() {
        
    }

    public function upsave() {
	
    }

}

?>