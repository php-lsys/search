<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
use LSYS\Search\Result;
use LSYS\Search\Query;
use LSYS\Search\QueryExpr;

abstract class Search{
	/**
	 * @var string
	 */
	protected $_index=null;
	/**
	 * @throws Exception
	 */
	public function __construct(){
		if ($this->_index==null) throw new Exception(__("Plase first set search index path."));//请先设置搜索索引
	}
	/**
	 * return search index
	 * @return string
	 */
	public function getIndex():string{
	    return strval($this->_index);
	}
	/**
	 * @param array $where
	 */
	abstract protected function _where(array $where);
	/**
	 * @param array $sort
	 */
	abstract protected function _sort(array $sort);
	/**
	 * @param Query $query
	 * @return Result
	 */
	abstract public function query(Query $query);
	/**
	 * set attributes,only update filter attribute
	 * @param string $pk
	 * @param array $vals
	 */
	abstract public function updateIndex(string $pk,array $vals);
	/**
	 * delete pk form index
	 * @param string $index
	 * @param string $pk
	 * @return bool
	 */
	abstract public function deleteIndex(string $pk);
	/**
	 * parse QueryExpr object
	 * @param QueryExpr $expr
	 * @return mixed
	 */
	abstract function parseExpr(QueryExpr $expr);
}
