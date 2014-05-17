<?php
/**
* 3G 下 微学校
* @author:
**/
class SchoolAction extends  WapAction{

    public function _initialize(){
        parent::_initialize();
        $this->token    = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $this->wecha_id = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $this->assign('token',$this->token);
        $this->assign('wecha_id',$this->wecha_id);
        $t_school   = M('school_set_index');
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token);
        $setSchool  = $t_school->where($where)->find();
        $this->assign('info',$setSchool);

    }
    public function  index(){
        $t_school   = M('school_set_index');
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token);
        $setSchool  = $t_school->where($where)->find();
        $photolist  = M('photo_list')->where(array('token'=>$token,'pid'=>$setSchool['album_id']))->select();
        $this->assign('info',$setSchool);
       // var_dump($setSchool);
        $this->assign('flash',$photolist);
        $this->display();
    }

    public function public_list(){
        $cid        = filter_var($this->_get('cid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        //$t_classify = M('classify');
        //$where      = array('token'=>$token,'id'=>$cid);
        //$classid    = $t_classify->where($where)->getField('id');
        //echo $classid;
        $type      = trim(filter_var($this->_get('type'),FILTER_SANITIZE_STRING));
        if(isset($type) && $type == 'school'){
          $t_recipe= M('recipe');
          $where2  = array('token'=>$token,'type'=>'school','ishow'=>1);
          $count      = $t_recipe->where($where2)->count();
          $Page       = new Page($count,5);
          $show       = $Page->show();
          $infolist   = $t_recipe->where($where2)->order('sort desc')->field('id,title,headpic as pic')->limit($Page->firstRow.','.$Page->listRows)->select();
          $this->assign('count',$count);
          $this->assign('page',$show);
          $this->assign('readtype',$type);
          $this->assign('infolist',$infolist);
          $this->display();
          exit;
        }elseif(isset($type) && $type == 'course'){
          $t_res  = M('reservation');
          $where3  = array('token'=>$token,'type'=>'school');
          $count      = $t_res->where($where3)->count();
          $Page       = new Page($count,5);
          $show       = $Page->show();
          $infolist   = $t_res->where($where3)->field('id,title,picurl as pic,typename,typename2,typename3,keyword,date')->limit($Page->firstRow.','.$Page->listRows)->select();
          $this->assign('count',$count);
          $this->assign('page',$show);
          $this->assign('readtype',$type);
          $this->assign('infolist',$infolist);
          $this->display();
          exit;
        }elseif('assess' == $type && isset($type)){
          $t_assess  = M('school_teachers');
          $where4  = array('token'=>$token,'type'=>'school');
          $count      = $t_assess->where($where4)->count();
          $Page       = new Page($count,5);
          $show       = $Page->show();
          $infolist   = $t_assess->where($where4)->field('tid as id,tname as title,faceimg as pic,headinfo as text')->limit($Page->firstRow.','.$Page->listRows)->order('sort desc')->select();
          $this->assign('count',$count);
          $this->assign('page',$show);
          $this->assign('readtype',$type);
          $this->assign('infolist',$infolist);
          $this->display();
          exit;
        }


        $t_img      = M('img');
        $where      = array('token'=>$token,'classid'=>$cid);
        $count      = $t_img->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $infolist   = $t_img->where($where)->order('createtime desc,uptatetime desc')->field('id,text,pic,title,createtime')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('readtype',$type);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('infolist',$infolist);
        $this->display();
    }

    public function readview(){
        $id         = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $readtype   = filter_var($this->_get('readtype'),FILTER_SANITIZE_STRING);
        if(isset($readtype) && $readtype == 'school'){
           $t_recipe= M('recipe');
           $where2  = array('token'=>$token,'type'=>'school','ishow'=>1,'id'=>$id);
           $infolist= $t_recipe->where($where2)->find();
           $this->assign('recipe', $infolist);
           $this->display('recipe');
            exit;
        }elseif(isset($readtype) && $readtype == 'course'){
           $t_res   = M('reservation');
           $t_book  = M('reservebook');
           $where3  = array('token'=>$token,'type'=>'course','id'=>$id);
           $infolist= $t_res->where($where3)->find();
           $where4  = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'course');
           $count   = $t_book->where($where4)->count();
           $this->assign('count',$count);
           $this->assign('reslist', $infolist);
           $this->assign('readtype',$readtype);
           $this->display('yyclass');
           exit;
        }elseif(isset($readtype) && $readtype == 'assess'){
          $t_assess   = M('school_teachers');
          $where      =  array('token'=>$token, 'tid'=>$id);
          $info       = $t_assess->where($where)->field('tid as id,tname as title,faceimg as pic,headinfo as text,info')->find();
          $this->assign('readinfo', $info);
          $this->assign('readtype',$readtype);
          $this->display();exit;
        }

        $t_img      = M('img');
        $where      =  array('token'=>$token, 'id'=>$id);
        $info       = $t_img->where($where)->find();
        $this->assign('readinfo', $info);
        $this->assign('readtype',$readtype);
        $this->display();
    }

    public function qresults(){
       $wecha_id  = trim(filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING));
       $t_student = M('school_students');
       //check wecha_id is ok ,display to this children results.
       if(IS_POST){
          $s_name = trim(filter_var($this->_post('s_name'),FILTER_SANITIZE_STRING));
          $mobile = trim(filter_var($this->_post('mobile'),FILTER_SANITIZE_STRING));
          $token  = trim(filter_var($this->_post('token'),FILTER_SANITIZE_STRING));
          $where  = array('token'=>$token ,'mobile'=>$mobile,'s_name'=>$s_name);
          $ckdata = $t_student->where($where)->find();
          if(!empty($ckdata) && $ckdata['id']){
            $url ='http://'.$_SERVER['HTTP_HOST'];
            $url .= U('School/sscore',array('token'=>$token,'wecha_id'=>$wecha_id,'sid'=>$ckdata['id'],'check'=>time()));
            $arr=array('errno'=>1,'token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
            echo json_encode($arr);
          }else{
            $arr=array('errno'=>2,'token'=>$token,'wecha_id'=>$wecha_id);
            echo json_encode($arr);
          }
         exit;
       }
        $this->display();
    }

    public function yysave(){

       if(IS_POST){
          $t_book             = M('reservebook');
          $_POST['wecha_id']  = trim(filter_var($this->_post('wecha_id'),FILTER_SANITIZE_STRING));
          $_POST['token']     = trim(filter_var($this->_post('token'),FILTER_SANITIZE_STRING));
          $_POST['truename']  = trim(filter_var($this->_post('truename'),FILTER_SANITIZE_STRING));
          $_POST['tel']       = trim(filter_var($this->_post('mobile'),FILTER_SANITIZE_STRING));
          $_POST['housetype'] = trim(filter_var($this->_post('housetype'),FILTER_SANITIZE_STRING));
          $_POST['info']      = trim(filter_var($this->_post('info'),FILTER_SANITIZE_STRING));
          $_POST['type']      = trim(filter_var($this->_post('type'),FILTER_SANITIZE_STRING));
          $_POST['dateline']  = trim(filter_var($this->_post('dateline'),FILTER_SANITIZE_STRING));
          $_POST['address']   = trim(filter_var($this->_post('address'),FILTER_SANITIZE_STRING));
          $_POST['choose']    = trim(filter_var($this->_post('choose'),FILTER_SANITIZE_STRING));
          $_POST['booktime']  = time();
          $_POST['rid']       = trim(filter_var($this->_post('rid'),FILTER_VALIDATE_INT));
          $token              = trim(filter_var($this->_get('token'),FILTER_SANITIZE_STRING));
          $wecha_id           = trim(filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING));
          $joinnum            = $t_book->where(array('token'=>$_POST['token'],'rid'=>$_POST['rid'],'type'=>$_POST['type']))->count();
          $t_res              = M('reservation');
          $countnum = $t_res->where(array('token'=>$_POST['token'],'addtype'=>$_POST['type'],'id'=>$_POST['rid']))->getField('typename2');
          $url  ='http://'.$_SERVER['HTTP_HOST'];
          if($joinnum >= $countnum){
            $url .= U('School/public_list',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'course'));
            echo json_encode(array('errno'=>3,'token'=>$token,'wecha_id'=>$wecha_id,'msg'=>"非常抱歉,该课程已经满员,您可以看看别的选课.",'url'=>$url));exit;
          }
          $id = $t_book->data($_POST)->add();
          if($id){
             //发送给商家
            Sms::sendSms($_POST['token'], "{$_POST['truename']},预约了 {$_POST['choose']} 老师的 {$_POST['housetype']}. ". date('Y-m-d H:i:s',time()));
                //发给单个连锁商家
               // Sms::sendSms(token_商家ID, 短信内容);
                //发送给粉丝
            Sms::endSms($_POST['token'], "亲爱的 {$_POST['truename']},您预约的 由 {$_POST['choose']} 课程 [{$_POST['housetype']}],预约成功! ". date('Y-m-d H:i:s',time()),$_POST['tel']);

            $url .= U('School/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'rid'=>$_POST['rid'],'type'=>'course','check'=>time()));
            $arr=array('errno'=>1,'token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
            echo json_encode($arr);exit;
          }else{
            $arr=array('errno'=>2,'token'=>$token,'wecha_id'=>$wecha_id);
            echo json_encode($arr);exit;
          }
       }
    }

    public function mylist(){
        $t_book    = M('reservebook');
        $token     = trim(filter_var($this->_get('token'),FILTER_SANITIZE_STRING));
        $wecha_id  = trim(filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING));
        $rid       = trim(filter_var($this->_get('rid'),FILTER_VALIDATE_INT));
        $type      = trim(filter_var($this->_get('type'),FILTER_SANITIZE_STRING));
        $check     = trim(filter_var($this->_get('check'),FILTER_VALIDATE_INT));
        if(empty($check)){
            $this->error('抱歉,请输入验证信息后在查看!',U('School/index',array('token'=>$token,'wecha_id'=>$wecha_id)));
        }
        $where = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type);

        $count      = $t_book->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $infolist   = $t_book->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('booktime DESC')->select();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('books',$infolist);

      $this->display();
    }

    public function  del(){
        $id       = (int)$this->_get('id');
        $token    = $this->_get('token');
        $wecha_id = trim($this->_get('wecha_id'));
        $type = trim($this->_get('type'));
        $t_book   =   M('Reservebook');
        $check    = $t_book->where(array('id'=>$id,'wecha_id'=>$wecha_id,'token'=>$token,'type'=>$type))->find();
        if($check){
            $t_book->where(array('id'=>$check['id'],'wecha_id'=>$check['wecha_id'],'token'=>$check['token']))->delete();
            $this->success('删除成功',U('School/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$this->_get('type'),'check'=>time())));
             exit;
         }else{
            $this->error('非法操作',U('School/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$this->_get('type'),'check'=>time())));
            exit;
         }
    }

    public function sscore(){
        $t_s_score = M('school_score');
        $token     = trim(filter_var($this->_get('token'),FILTER_SANITIZE_STRING));
        $wecha_id  = trim(filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING));
        $sid       = trim(filter_var($this->_get('sid'),FILTER_VALIDATE_INT));
        $check     = trim(filter_var($this->_get('check'),FILTER_VALIDATE_INT));
        if(empty($check)){
            $this->error('抱歉,请输入验证信息后在查看!',U('School/qresults',array('token'=>$token,'wecha_id'=>$wecha_id)));
        }
        $where     =  array('token'=>$token, 'sid'=>$sid);
        $count     = $t_s_score->where($where)->count();
        $Page      = new Page($count,20);
        $show      = $Page->show();
        $arrids = $t_s_score->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $t_s_students = M('school_students');
        $t_s_classify = M('school_classify');
        $market = array();
        foreach($arrids as $k=>$val){
            $market[$k]['id']      = $val['id'];
            $market[$k]['sid']     = $val['sid'];
            $market[$k]['my_score']     = $val['my_score'];
            $market[$k]['s_name']   = $t_s_students->where(array('id'=>$val['sid'],'token'=>session('token')))->getField('s_name');
            $market[$k]['sex']   = $t_s_students->where(array('id'=>$val['sid'],'token'=>session('token')))->getField('sex');
            $market[$k]['bj_name'] = $t_s_classify->where(array('sid'=>$t_s_students->where(array('id'=>$val['sid'],'token'=>session('token')))->getField('bj_id'),'token'=>session('token')))->getField('sname');
            $market[$k]['km_name'] = $t_s_classify->where(array('sid'=>$val['km_id'],'token'=>session('token')))->getField('sname');
            $market[$k]['qh_name'] = $t_s_classify->where(array('sid'=>$val['qh_id'],'token'=>session('token')))->getField('sname');
        }
        $this->assign('countshow',$count);
        $this->assign('market',$market);
        $this->display();
    }

    public function yyclass(){
      $this->display();
    }

    public function imessage(){
        $this->display();
    }

    public function photo_lists(){
        $this->display();
    }
}