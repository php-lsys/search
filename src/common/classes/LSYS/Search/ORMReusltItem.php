<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use LSYS\ORM\Entity;
class ORMReusltItem{
	/**
	 * @var ResultItem
	 */
	protected $_item;
	/**
	 * @var Entity
	 */
	protected $_entity;
	public function __construct(ResultItem $item,Entity $entity=null){
		$this->_item=$item;
		$this->_entity=$entity;
	}
	/**
	 * proxy to result item
	 */
	public function __toString(){
		return $this->_item->__toString();
	}
	/**
	 * proxy to result item
	 */
	public function getPk(){
		return $this->_item->getPk();
	}
	/**
	 * proxy to result item
	 */
	public function getHighlight($field){
		return $this->_item->getHighlight($field);
	}
	/**
	 * proxy to result item
	 */
	public function get($field){
		return $this->_item->get($field);
	}
	/**
	 * get entity
	 * @return Entity
	 */
	public function getEntity(){
		return $this->_entity;
	}
	/**
	 * proxy other method
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call($method,$args){
		return call_user_func_array(array($this->_item,$method), $args);
	}
}