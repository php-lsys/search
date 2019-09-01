<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search\SphinxRT;
use Foolz\SphinxQL\Drivers\ConnectionBase;
use LSYS\Search;
use LSYS\Search\Query;
class Result extends \LSYS\Search\Result{
	/**
	 * @var ConnectionBase
	 */
	protected $_connection;
	/**
	 * @param Search $search
	 * @param Query $query
	 * @param array $result
	 * @param ConnectionBase $conn
	 */
	public function __construct(Search $search,Query $query,$result,ConnectionBase $conn){
		if (!isset($result['matches'])||!is_array($result['matches']))$result['matches']=array();
		$result['matches']=array_values($result['matches']);
		parent::__construct($search,$query,$result);
		$this->_connection=$conn;
		$this->_total_rows=count($result['matches']);
	}
	public function getTime(){
		$result=$this->_result;
		if (!isset($result['meta']['time']))return 0;
		return $result['meta']['time'];
	}
	public function getTotal(){
		$result=$this->_result;
		if(!isset($result['meta']['total_found'])||!isset($result['meta']['total'])) return 0;
		$total=$result['meta']['total_found']>$result['meta']['total']?$result['meta']['total']:$result['meta']['total_found'];
		return $total;
	}
	public function current(){
		if (!$this->valid()||!isset($this->_result['matches'][$this->_current_row])) return null;
		return new ResultItem(
			$this->_search,$this->_query,
			$this->_result['matches'][$this->_current_row],$this->_connection
		);
	}
}