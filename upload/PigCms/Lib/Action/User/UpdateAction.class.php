<?php
class UpdateAction extends UserAction{


    public function index(){
        $model = new Model();

        $d  = $model->query("ALTER TABLE  `".C('DB_PREFIX')."alipay_config` ADD  `mchid` VARCHAR( 100 ) NOT NULL;");
        echo "更新成功！";


    }


}