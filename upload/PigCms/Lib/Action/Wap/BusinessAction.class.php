<?php
class BusinessAction extends WapAction{

    public $token;
    public $wecha_id;
    public $type;
    public $bid;
    public function _initialize() {
        parent::_initialize();
        $this->token    = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $this->wecha_id = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $this->type     = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $this->bid      = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $arrAllow       = array('fitness','gover','food','travel','flower','property','ktv','bar','fitment','wedding','affections','housekeeper','lease');
        $orderid    =   filter_var($this->_get('orderid'),FILTER_SANITIZE_STRING);
        //if(!in_array($this->type,$arrAllow) || $this->type == '' || $this->bid =='' ){
        if(!in_array($this->type,$arrAllow) ){
            $this->error('抱歉,您的参数不合法!',U('Index/index',array('token'=>$this->token,'wecha_id'=>$wecha_id)));
        }
        $where   = array('token'=>$this->token,'type'=>$this->type,'bid'=>$this->bid);
        $busines = M('busines')->where($where)->find();
        $this->assign('busines',$busines);
        $this->assign('token',$this->token);
        $this->assign('wecha_id',$this->wecha_id);
        $this->assign('type',$this->type);
        $this->assign('bid',$this->bid);
    }


    public function index(){
        $data   = D('busines');
        $type   = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token  = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid    = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        if($bid == ''){
            $this->error('抱歉,您的参数不合法!缺少参数!',U('Index/index',array('token'=>$this->token,'wecha_id'=>$wecha_id)));
        }
        $where      = array('token'=>$token,'type'=>$type,'bid'=>$bid);
        // $count      = $data->where($where)->count();
        // $Page       = new Page($count,3);
        // $show       = $Page->show();
        // $busines     = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        // $this->assign('page',$show);
        $busines = $data->where($where)->find();
        $pic     = M('busines_pic')->where(array('bid_id'=>$busines['bid'],'token'=>$token,'type'=>$type))->find();
        $this->assign('busines',$busines);
        $show    = filter_var($this->_get('show'),FILTER_SANITIZE_STRING);
        if($show == 'intro' && $show != ''){
            $where_2 = array('token'=>$token,'type'=>$type,'bid_id'=>$busines['bid']);
            $b_main = D('busines_main')->where($where_2)->select();
            $this->assign('b_main',$b_main);
            $this->display('intro');
            exit;
        }
        $this->assign('pics',$pic);
        $this->display();
    }



    public function classify(){
        //Load('extend');
        $data       = D('busines_main');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $where      = array('token'=>$token,'type'=>$type,'bid_id'=>$bid);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,3);
        $show       = $Page->show();
        $classify   = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('page',$show);
        $this->assign('classify',$classify);
        $this->display();
    }

    public function classify_item(){
        $data       = D('busines_main');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $mid        = filter_var($this->_get('mid'),FILTER_VALIDATE_INT);
        $where_2    = array('token'=>$token,'type'=>$type,'mid'=>$mid);
        $classify   = $data->where($where_2)->find();
        $b_second   = M('busines_second');
        $where_3    = array('token'=>$token,'type'=>$type,'mid_id'=>$classify['mid']);

        //$sec_item   = $b_second->where($where_3)->order('sort desc')->select();
        $count      = $b_second->where($where_3)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $sec_item   = $b_second->where($where_3)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('bid',$bid);
        $this->assign('page',$show);
        $this->assign('sec_item',$sec_item);
        $this->assign('classify',$classify);
        $this->display();
    }


    public function project(){
        $data       = D('busines_second');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $sid        = filter_var($this->_get('sid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'sid'=>$sid);
        $t_second   = $data->where($where)->find();
        $this->assign('sec_item',$t_second);
        $this->display();
    }

    public function goCart(){
        $data       = D('busines_second');
        $t_busines  = D('busines');
        $tb_resbook = D('reservebook');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $sid        = filter_var($this->_get('sid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'sid'=>$sid);
        $second     = $data->where($where)->find();
        $where_2    = array('token'=>$token,'type'=>$type,'bid'=>$bid);
        $busines    = $t_busines->where($where_2)->field('bid,roompicurl,address,businesphone,orderInfo,compyphone')->find();
        $maindata   = M('busines_main')->where(array('mid'=>$second['mid_id'],'token'=>$token,'type'=>$type))->field('mid,name as title')->find();
        if(!empty($second) && !empty($busines)){
            $oput   = array_merge($second,$busines,$maindata);
        }
        $count      = $tb_resbook->where(array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type))->count();
        if(IS_POST){
             $_POST['type']       = filter_var($this->_post('type'),FILTER_SANITIZE_STRING);
             $_POST['bid']        = filter_var($this->_post('bid'),FILTER_VALIDATE_INT);
             $_POST['sid']        = filter_var($this->_post('sid'),FILTER_VALIDATE_INT);
             $_POST['token']      = filter_var($this->_post('token'),FILTER_SANITIZE_STRING);
             $_POST['wecha_id']   = filter_var($this->_post('wecha_id'),FILTER_SANITIZE_STRING);
             $_POST['truename']   = filter_var($this->_post('truename'),FILTER_SANITIZE_STRING);
             $_POST['tel']        = filter_var($this->_post('tel'),FILTER_SANITIZE_STRING);
             $_POST['address']    = filter_var($this->_post('address'),FILTER_SANITIZE_STRING);
             $_POST['info']       = filter_var($this->_post('info'),FILTER_SANITIZE_STRING);
             $_POST['productName']= filter_var($this->_post('productName'),FILTER_SANITIZE_STRING);
             $_POST['orderid']    = self::generateOrderSn();
             $_POST['paid']       = 0;
             $_POST['booktime']   = time();
            //先查库存
            $where_stork          = array('token'=>$_POST['token'],'type'=>$_POST['type'],'sid'=>$_POST['sid']);
            $checkdata            = $data->where($where_stork)->find();
            if(intval($checkdata['googsnumber']) <= 0){
                exit($this->error('Oh,亲,你来晚了,木有库存了,再选别的吧.',
                    U('Business/index',array('token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],
                                        'bid'=>$_POST['bid'],'type'=>$_POST['type']))));
            }
             //如果有库存->下单,减库存,->付款 ==> 付款成功()|付款失败() ]go to payReturn[
            $_POST['orderid']     = self::generateOrderSn();
            $_POST['orderName']   = $checkdata['name'];
            $_POST['payprice']    = $checkdata['oneprice'];
            $_POST['rid']         = filter_var($this->_post('sid'),FILTER_VALIDATE_INT);
            $insertdata           = $tb_resbook->data($_POST)->add();
            if($insertdata){ //减库存
                $data->where(array('sid'=>$_POST['sid'],'type'=>$_POST['type'],'token'=>$_POST['token']))->setDec('googsnumber');
                header("Content-type: text/html; charset=utf-8");
                $this->redirect('Alipay/pay/', array('from' => 'Business','orderName'=>$checkdata['name'],
                                                'price'=>$checkdata['oneprice'],'token'=>$_POST['token'],
                                                'wecha_id'=>$_POST['wecha_id'],'type'=>$_POST['type'],'bid'=>$_POST['bid'],
                                                'sid'=>$_POST['sid']), 3, '您好,准备跳转到支付页面,请不要重复刷新页面,请耐心等待...');

            }else{
                exit($this->error('Sorry,请重新下单.',
                    U('Business/index',array('token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],
                                        'bid'=>$_POST['bid'],'type'=>$_POST['type']))));
            }

        }
        $this->assign('oput',$oput);
        $this->assign('count',$count);
        $this->display();
    }

    private function  generateOrderSn(){
        date_default_timezone_set('PRC');
        list($msec, $sec) = explode(' ',microtime());
        return date('ymdHis',$sec).substr($msec,2,6);
    }

    protected function payReturn(){
        // $data       = D('busines_second');
        // $t_busines  = D('busines');
        $tb_resbook = D('reservebook');
        $orderid    =   filter_var($this->_get('orderid'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $checkOrder = $tb_resbook->where(array('orderid'=>$orderid,'token'=>$token))->find();
       //根据订单号查出$order
       if($checkOrder){//如果订单存在
            if($checkOrder['paid'] === 1){ //支付成功,发信息,跳转到订单别表页面
                $this->assign('type',$checkOrder['type']);
                $this->assign('token',$checkOrder['token']);
                $this->assign('wecha_id',$checkOrder['wecha_id']);
                //发送给商家
                Sms::sendSms($checkOrder['token'], "亲爱的,您的会员 {$checkOrder['truename']},已经购买了{$checkOrder['orderName']} 并付款成功,金额为{$checkOrder['payprice']},订单号为{$checkOrder['orderid']}. ". date('Y-m-d H:i:s',time()));
                //发给单个连锁商家
               // Sms::sendSms(token_商家ID, 短信内容);
                //发送给粉丝
                Sms::endSms($checkOrder['token'], "亲爱的 {$checkOrder['truename']},您购买的{$checkOrder['orderName']} 已经付款成功,金额为{$checkOrder['payprice']},订单号为{$checkOrder['orderid']},感谢您惠顾! ". date('Y-m-d H:i:s',time()),$checkOrder['tel']);

                self::mylist();
                exit;
            }else{
                //如果没支付，则进入另外一个判断,如果订单没有支付,这里应该回滚库存.setInc()
            M('busines_second')->where(array('sid'=>$checkOrder['rid'],'type'=>$checkOrder['type'],'token'=>$checkOrder['token']))->setInc('googsnumber');
            }

       }else{
        // $this->redirect('Business/index', array('token'=>$this->_get('token'),
        //                                         'wecha_id'=>$this->_get('wecha_id'),'type'=>$this->_get('type'),'bid'=>$this->_get('bid'),
        //                                         'sid'=>$this->_get('sid')), 2, '您好,系统里没有该订单...');
          exit('订单不存在!');
        }

    }

    public function mylist(){
        $tb_resbook = D('reservebook');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'wecha_id'=>$wecha_id);

        $count      = $tb_resbook->where($where)->count();
        $Page       = new Page($count,10);
        $show       = $Page->show();
        $books      = $tb_resbook->where($where)->order('booktime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('page',$show);
        $this->assign('books',$books);
        $this->display();
    }

    public function delOrder(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $id         = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $tb_resbook =   M('reservebook');
        $check      = $tb_resbook->where(array('id'=>$id,'token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type))->find();
        if($check){
            $tb_resbook->where(array('id'=>$check['id'],'wecha_id'=>$check['wecha_id'],'type'=>$check['type'],'token'=>$check['token']))->delete();
            $this->success('删除成功',U('Business/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type)));
             exit;
         }else{
            $this->error('非法操作',U('Business/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type)));
             exit;
         }
    }

    public function plist(){
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $get_id     = M('busines_pic')->field('bid_id,type,ablum_id')->where(array('bid_id'=>$bid,'token'=>$token,'type'=>$type))->find();
        $info=M('Photo')->field('title,picurl,id')->where(array('token'=>$token,'id'=>$get_id['ablum_id']))->find();
        $photo_list=M('Photo_list')->where(array('token'=>$token,'pid'=>$get_id['ablum_id'],'status'=>1))->order('sort desc')->select();
        //dump($photo);
        $this->assign('info',$info);
        $this->assign('photo',$photo_list);
        $this->display();
    }



    public function comments(){
        $data       = D('busines_comment');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $where      = array('token'=>session('token'),'type'=>$type,'bid_id'=>$bid);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $comments= $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('comments',$comments);
        $this->display();

    }




}