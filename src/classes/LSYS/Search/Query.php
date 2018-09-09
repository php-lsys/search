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
	public function empty_query(){
		if (empty($this->_key_word)) return true;
		return false;
	}
	public function set_limit($limit){
		$limit=intval($limit);
		$this->_limit=$limit<0?0:$limit;
		return $this;
	}
	public function set_page($page,$limit=10){
		$page=$page<=0?1:intval($page);
		$offset=($page-1)*$limit;
		$this->set_limit($limit);
		$this->set_offset($offset);
		return $this;
	}
	public function set_offset($offset){
		$offset=intval($offset);
		$this->_offset=$offset<0?0:$offset;
		return $this;
	}
	public function set_where(array $where){
		$this->_where=$where;
		return $this;
	}
	public function set_sort($sort){
		$this->_sort[]=$sort;
		return $this;
	}
	public function set_highlight(Highlight $highlight){
		$this->_highlight=$highlight;
		return $this;
	}
	public function get_limit(){
		return $this->_limit;
	}
	public function get_offset(){
		return $this->_offset;
	}
	public function get_query(){
		return $this->_key_word;
	}
	public function get_where(){
		return $this->_where;
	}
	public function get_sort(){
		return $this->_sort;
	}
	/**
	 * @return Highlight
	 */
	public function get_highlight(){
		return $this->_highlight;
	}
}
