<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use LSYS\ORM;

class ORMReuslt implements \Countable, \Iterator, \SeekableIterator{
	/**
	 * @var Result
	 */
	protected $_search_result;
	/**
	 * @var \LSYS\ORM\Result
	 */
	protected $_result;
	public function __construct(Result $result,ORM $orm){
		$this->_search_result=$result;
		$this->_orm=$orm;
		$pks=[];
		foreach ($result as $v){
		    $pks[]=$v->getPk();
		}
		if (count($pks)>0){
		    $this->_result=$this->_orm->reset()->where($this->_orm->primaryKey(), "in", $pks)->findAll();
		}
	}
	/**
	 * proxy search result
	 * @return Query
	 */
	public function getQuery(){
		return $this->_search_result->getQuery();
	}
	/**
	 * proxy search result
	 */
	public function count()
	{
		return $this->_search_result->count();
	}
	/**
	 * proxy search result
	 */
	public function key()
	{
		return $this->_search_result->key();
	}
	/**
	 * proxy search result
	 */
	public function next()
	{
		return $this->_search_result->next();
	}
	/**
	 * proxy search result
	 */
	public function prev()
	{
		return $this->_search_result->prev();
	}
	/**
	 * proxy search result
	 */
	public function rewind()
	{
		return $this->_search_result->rewind();
	}
	/**
	 * proxy search result
	 */
	public function valid()
	{
		return $this->_search_result->valid();
	}
	/**
	 * proxy search result
	 */
	public function seek($offset)
	{
		return $this->_search_result->seek();
	}
	/**
	 * proxy search result
	 */
	public function getTotal(){
		return $this->_search_result->getTotal();
	}
	/**
	 * proxy search result
	 */
	public function getTime(){
		return $this->_search_result->getTime();
	}
	/**
	 * @return ORMReusltItem
	 */
	public function current (){
		$current=$this->_search_result->current();
		if ($current==null) return null;
		if ($this->_result){
    		foreach ($this->_result as $v){
    		    if($v->pk()==$current->getPk()){
    		        return new ORMReusltItem($current,$v);
    		    }
    		}
		}
		return new ORMReusltItem($current);
	}
	/**
	 * proxy other method
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call($method,$args){
		return call_user_func_array(array($this->_search_result,$method), $args);
	}
}