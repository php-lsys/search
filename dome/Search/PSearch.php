<?php
use LSYS\Search\SphinxRT;
class PSearch extends SphinxRT{ 
	protected $_index='rt3';
	protected $_index_columns = array (/*rt field*/
		'content'=>'',
		'content11'=>'',
		'guid'=>0,
		'title'=>'',
		'gpa'=>0.0,
		'ts_added'=>0,
		'author'=>'',
		'attrs'=>array(),
	);
	protected $_map_columns = array (/*rt_attr_string field*/
		'content'=>'_content',
		'content11'=>'_content11',
		'title'=>'_title',
	);
	public function __construct(){
		parent::__construct(\LSYS\Config\DI::get()->config("sphinx.rt"));
	}
	protected function _where(array $where){
// 		$this->_sphinxql;
	}
	protected function _sort(array $sort){
// 		$this->_sphinxql;		
	}
} 
