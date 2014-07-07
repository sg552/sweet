<?php

class EstateAction extends BaseAction{
    public $token;
    public $wecha_id;
    public function _initialize() {
        parent::_initialize();
        $this->token = $this->_get('token');
        $this->wecha_id =$this->_get('wecha_id');
        $this->assign('token',$this->token);
        $this->assign('wecha_id',$this->wecha_id);
        $get_ids = M('Estate')->where(array('token'=>$this->token))->field('res_id,classify_id')->find();
        $this->assign('rid',$get_ids['res_id']);

    }

    public function index(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $data = M("Estate");
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $es_data = $data->where($where)->find();
        $this->assign('es_data',$es_data);
        $this->display();
    }

    public function news(){
        $data = M("Estate");
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $cid = $data->where($where)->getField('classify_id');
        if($cid != null){
            $t_classify = M('Classify');
            $where = array('token'=>$this->token,'id'=>$cid);
            $classify = $t_classify->where($where)->find();
        }
        $t_img = M('Img');
        $where = array('classid'=>$classify['id'],'token'=>$this->_get('token'));
        $imgtxt = $t_img->where($where)->field('id as mid,title,pic,createtime')->order('createtime desc,uptatetime desc')->select();

        $this->assign('imgtxt',$imgtxt);
        $this->assign('classify',$classify);
        $this->display();
    }

    public function  newlist(){
        $token = $this->_get('token');
        $mid = (int)$this->_get('mid');
        $t_img = M('Img');
        $where = array('id'=>$mid,'token'=>$token);
        $imgtxt = $t_img->where($where)->find();
        $this->assign('imgtxt',$imgtxt);

        $this->display();
    }


    public function housetype(){
        $t_housetype = M('Estate_housetype');
        $where = array('token'=>$this->_get('token'));
        $housetype = $t_housetype->where($where)->order('sort desc')->select();
        foreach ($housetype as $k => $v) {
            $son_type[] = M("Estate_son")->where(array('id'=>$v['son_id']))->field('id as sid,title,description as desc_son')->find();
        }
        foreach ($son_type as $key => $value) {
             foreach ($value as $k => $v) {
                  $housetype[$key][$k] = $v;
             }

        }
        //var_dump($housetype);
        $this->assign('housetype',$housetype);
        $data = M("Estate");
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $es_data = $data->where($where)->field('title,house_banner,panorama_id')->find();
        $this->assign('es_data',$es_data);
        $this->display();
    }



    public function album(){
        $this->token=$this->_get('token');
        $reply_info_db=M('Reply_info');
        $config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
        if ($config){
            $headpic=$config['picurl'];
        }else {
            $headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
        }
        $this->assign('headpic',$headpic);

        $Photo = M("Photo");
        $t_album = M('Estate_album');
        $album = $t_album->where(array('token'=>$this->_get('token')))->field('id,poid')->select();
        $list_photo = array();
        foreach ($album as $k => $v) {
             $list_photo[] = $Photo->where(array('token'=>$this->_get('token'),'id'=>$v['poid']))->find();
        }
        $this->assign('photo',$list_photo);
        $this->display('Photo:index');
    }

    public function show_album(){
        $this->token=$this->_get('token');
        $reply_info_db=M('Reply_info');
        $config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
        if ($config){
            $headpic=$config['picurl'];
        }else {
            $headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
        }
        $this->assign('headpic',$headpic);
        $t_housetype = M('Estate_housetype');
        $id = (int)$this->_get('id');
        $where = array('token'=>$this->_get('token'),'id'=>$id);
        $housetype = $t_housetype->where($where)->order('sort desc')->find();
        $this->assign('shareid',$id);

        $data = M("Estate");
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $es_data = $data->where($where)->field('id,title')->find();
        if(!empty($es_data)){
            $housetype = array_merge($housetype,$es_data);
        }
        $this->assign('housetype',$housetype);
        $this->display();
    }

    public function impress(){
        $t_impress = M('Estate_impress');
        $where     = array('token'=>$this->_get('token'));
        $where2    = array('token'=>$this->_get('token'),'is_show'=>1);
        $impress   = $t_impress->where($where2)->order('sort desc')->select();
        $count2    = $t_impress->where($where2)->count();
        $Page2     = new Page($count2,18);
        $show2     = $Page2->show();
        $impress   = $t_impress->where($where2)->limit($Page2->firstRow.','.$Page2->listRows)->order('sort desc')->select();
        $this->assign('impress',$impress);
        $this->assign('page2',$show2);

        $t_expert = M('Estate_expert');
        $count      = $t_expert->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $expert     = $t_expert->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('expert',$expert);

        $this->display();
    }

    public function impress_add(){
        $t_impress  = M('Estate_impress'); //comment 统计数
        $t_imp_add  = M("Estate_impress_add");
        $imp_id     = (int)$this->_post('imp_id');
        $token      = $this->_post('token');
        $wecha_id   = $this->_post('wecha_id');
        $where      =  array('wecha_id'=>$wecha_id,'token'=>$token);
        $check      = $t_imp_add->where($where)->find();

         //$data=array('imp_id'=>$imp_id,'msg'=>$wecha_id);
         //echo json_encode($data);
         //exit;


        if($check != null){
            $data=array('errno'=>2,'msg'=>"您已经点过赞了哦");
            echo json_encode($data);
            exit;
        }

         if($id=$t_imp_add->add($_POST)){
            $t_impress->where(array('id'=>$imp_id,'token'=>$token))->setInc('comment');
             $data=array('errno'=>1,'msg'=>"谢谢您的赞。");
              echo json_encode($data);
             exit;
         }else{
            $data=array('errno'=>0,'msg'=>"点赞失败，请再来一次吧。");
            echo json_encode($data);
            exit;
         }

    }

    public function  aboutus(){
        $company = M('Company');
        $token=$this->_get('token');
        $about = $company->where(array('token'=>$token,'shortname'=>'loupan','isbranch'=>1))->find();
        $this->assign('about',$about);

        $this->display();
    }

}



?>