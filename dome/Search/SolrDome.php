<?php
use LSYS\Search\Solr;
require_once  __DIR__."/../Bootstarp.php";
class SolrDome extends Solr{ 
	protected $_index='solr/new_core';//solr 的空间
	
	public function __construct(){
	    parent::__construct(\LSYS\Config\DI::get()->config("solr.default"));
	}
	
	protected function _where(array $where){
// 		$this->_solr;
		//your where
	}
	protected function _sort(array $sort){
// 		$this->_solr
		//your sort
	}
} 
