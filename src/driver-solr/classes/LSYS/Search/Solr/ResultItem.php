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

class ResultItem extends \LSYS\Search\ResultItem{
	public function __construct(Search $search,Query $query,$item){
		parent::__construct($search, $query, $item);
	}
	public function getPk(){
		return isset($this->_item['item']->id)?$this->_item['item']->id:false;
	}
	public function getHighlight($field=null){
		//field need set to sql_field_string
		$hig=$this->_query->getHighlight();
		if ($hig==null)return is_string($field)?null:array();
		$fields=$hig->getFields();
		if (count($fields)==0)return is_string($field)?null:array();
		if (is_string($field)) return isset($this->_item['highlighting'][$field])?$this->_item['highlighting'][$field]:null;
		if (is_array($field)){
			$out=array();
			foreach ($field as $v){
				isset($this->_item['highlighting'][$v])&&$out[$v]=$this->_item['highlighting'][$v];
			}
			return $out;
		}
		return $this->_item['highlighting'];
	}
	public function get($field){
		if (isset($this->_item['item']->{$field})) return $this->_item['item']->{$field};
		return null;
	}
}