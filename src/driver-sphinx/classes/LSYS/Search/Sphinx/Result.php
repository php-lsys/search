<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search\Sphinx;
use LSYS\Search\Query;
use LSYS\Search;

class Result extends \LSYS\Search\Result{
	protected $_spinx_client;
	/**
	 * @param Query $query
	 * @param mixed $result
	 * @param \SphinxClient $spinx_client
	 */
	public function __construct(Search $search,Query $query,$result,\SphinxClient $spinx_client){
		if (!isset($result['matches'])||!is_array($result['matches']))$result['matches']=array();
		$result['matches']=array_values($result['matches']);
		parent::__construct($search,$query,$result);
		$this->_spinx_client=$spinx_client;
		$this->_total_rows=count($result['matches']);
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Search\Result::getTime()
	 */
	public function getTime(){
		$result=$this->_result;
		if (!isset($result['time']))return 0;
		return $result['time'];
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Search\Result::getTotal()
	 */
	public function getTotal(){
		$result=$this->_result;
		if(!isset($result['total_found'])||!isset($result['total'])) return 0;
		$total=($result['total_found']>$result['total']?$result['total']:$result['total_found']);
		return $total;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Search\Result::current()
	 */
	public function current(){
		if (!$this->valid()||!isset($this->_result['matches'][$this->_current_row])) return null;
		return new ResultItem(
			$this->_search,$this->_query,
			$this->_result['matches'][$this->_current_row],$this->_spinx_client
		);
	}
}
