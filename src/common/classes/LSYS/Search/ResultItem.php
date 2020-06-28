<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use LSYS\Search;

abstract class ResultItem{
	/**
	 * @var Search
	 */
	protected $_search;
	/**
	 * @var Query
	 */
	protected $_query;
	protected $_item=array();
	public function __construct(Search $search,Query $query,$item){
		$this->_search=$search;
		$this->_query=$query;
		$this->_item=$item;
	}
	public function __toString(){
		return $this->getPk();
	}
	abstract public function getPk();
	abstract public function getHighlight($field=null);
	abstract public function get($field);
}
