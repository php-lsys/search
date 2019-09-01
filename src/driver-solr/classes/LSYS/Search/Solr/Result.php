<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search\Solr;

use LSYS\Search\Query;
use LSYS\Search;

class Result extends \LSYS\Search\Result{
	/**
	 * @param Search $search
	 * @param Query $query
     * @param \SolrObject $result
	 */
	public function __construct(Search $search,Query $query,\SolrObject $result){
		parent::__construct($search,$query,$result);
		$this->_total_rows=count($result->response->docs);
	}
	public function getTime(){
		return $this->_result->responseHeader->QTime;
	}
	public function getTotal(){
		return $this->_result->response->numFound;
	}
	public function current(){
		if (!$this->valid()||!isset($this->_result->response->docs[$this->_current_row])) return null;
		if (isset($this->_result->highlighting)){
		$high=$this->_result->highlighting->offsetGet($this->_result->response->docs[$this->_current_row]->id);
		foreach ($high as &$v){
			$v=implode("...",$v);
		}
		}else $high=array();
		return new ResultItem(
			$this->_search,$this->_query,
			array(
				'item'=>$this->_result->response->docs[$this->_current_row],
				'highlighting'=>$high
			)
		);
	}
}