<?php
bpBase::loadSysClass('model', '', 0);
class function_model extends model {
	public function __construct() {
		$this->table_name = TABLE_PREFIX.'function';
		parent::__construct();
	}
}
?>