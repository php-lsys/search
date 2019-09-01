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

class ResultItem extends \LSYS\Search\ResultItem{
	/**
	 * @var \SphinxClient
	 */
	protected $_spinx_client;
	protected $_highlight=false;
	public function __construct(Search $search,Query $query,$item,\SphinxClient $spinx_client){
		parent::__construct($search, $query, $item);
		$this->_spinx_client=$spinx_client;
	}
	public function getPk(){
		return isset($this->_item['id'])?$this->_item['id']:false;
	}
	public function getHighlight($field=null){
		//field need set to sql_field_string
		$hig=$this->_query->getHighlight();
		if ($hig==null)return is_string($field)?null:array();
		$fields=$hig->getFields();
		if (count($fields)==0)return is_string($field)?null:array();
		
		if (!is_array($this->_highlight)){
			$this->_highlight=array();
			if (!isset($this->_item['attrs'])||!is_array($this->_item['attrs']))$this->_item['attrs']=array();
			$qvalues=$qkey=array();
			foreach ($fields as $v){
				if (isset($this->_item['attrs'][$v])){
					$qkey[]=$v;
					$qvalues[]=$this->_item['attrs'][$v];
				}
			}
			if (count($qvalues)==0) return is_string($field)?null:array();
			$opts = array
			(
				"exact_phrase"		=> 0,
				"before_match"		=> $hig->getBefore(),
				"after_match"		=> $hig->getAfter(),
				"chunk_separator"	=> " ... ",
				"limit"				=> 120,
				"around"			=> 3,
			);
			$hrow=$this->_spinx_client->BuildExcerpts ( $qvalues,$this->_search->getIndex(), $this->_query->getQuery(), $opts );
			if ($hrow===false) $hrow=$qvalues;
			foreach ($hrow as $k=>$v){
				$this->_highlight[$qkey[$k]]=$v;
			}
		}
		if (is_string($field)) return isset($this->_highlight[$field])?$this->_highlight[$field]:null;
		if (is_array($field)){
			$out=array();
			foreach ($field as $v){
				isset($this->_highlight[$v])&&$out[$v]=$this->_highlight[$v];
			}
			return $out;
		}
		return $this->_highlight;
	}
	public function get($field){
		if (isset($this->_item[$field])) return $this->_item[$field];
		if (isset($this->_item['attrs'][$field])) return $this->_item['attrs'][$field];
		return null;
	}
}
