<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
class Query{
	/**
	 * query factory
	 * @param string $index
	 * @return Query
	 */
	public static function factory($query){
		return new static($query);
	}
	//int
	protected $_offset=0;
	//int
	protected $_limit=10;
	//
	protected $_key_word;
	protected $_where=array();
	protected $_sort=array();
	protected $_highlight;
	public function __construct($string){
		if (is_object($string)) $this->_key_word=$string;
		else $this->_key_word=trim(preg_replace("/\s+/",' ', strval($string)));
	}
	/**
	 * is empty search
	 * @return boolean
	 */
	public function emptyQuery(){
		if (empty($this->_key_word)) return true;
		return false;
	}
	public function setLimit($limit){
		$limit=intval($limit);
		$this->_limit=$limit<0?0:$limit;
		return $this;
	}
	public function setPage($page,$limit=10){
		$page=$page<=0?1:intval($page);
		$offset=($page-1)*$limit;
		$this->setLimit($limit);
		$this->setOffset($offset);
		return $this;
	}
	public function setOffset($offset){
		$offset=intval($offset);
		$this->_offset=$offset<0?0:$offset;
		return $this;
	}
	public function setWhere(array $where){
		$this->_where=$where;
		return $this;
	}
	public function setSort($sort){
		$this->_sort[]=$sort;
		return $this;
	}
	public function setHighlight(Highlight $highlight){
		$this->_highlight=$highlight;
		return $this;
	}
	public function getLimit(){
		return $this->_limit;
	}
	public function getOffset(){
		return $this->_offset;
	}
	public function getQuery(){
		return $this->_key_word;
	}
	public function getWhere(){
		return $this->_where;
	}
	public function getSort(){
		return $this->_sort;
	}
	/**
	 * @return Highlight
	 */
	public function getHighlight(){
		return $this->_highlight;
	}
}
