<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search\SphinxRT;
use Foolz\SphinxQL\Drivers\ConnectionBase;
use Foolz\SphinxQL\Helper;
use LSYS\Search;
use LSYS\Search\Query;
class ResultItem extends \LSYS\Search\ResultItem{
	/**
	 * @var ConnectionBase
	 */
	protected $_connection;
	public function __construct(Search $search,Query $query,$item,ConnectionBase $conn){
		parent::__construct($search, $query, $item);
		$this->_connection=$conn;
	}
	protected $_highlight=false;
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
			if (!isset($this->_item)||!is_array($this->_item))$this->_item=array();
			$hp=Helper::create($this->_connection);
			$opts = array
			(
					"exact_phrase"		=> 0,
					"before_match"		=> $hig->getBefore(),
					"after_match"		=> $hig->getAfter(),
					"chunk_separator"	=> "...",
					"limit"				=> 180,
					"around"			=> 3,
			);
			foreach ($fields as $v){
				if (!isset($this->_item[$v]))continue;
				$h=$hp->callSnippets($this->_item[$v],$this->_search->getIndex(),$this->_query->getQuery(),$opts)->execute()->offsetGet(0);
				$this->_highlight[$v]=isset($h['snippet'])?$h['snippet']:$this->_item[$v];
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
		return null;
	}
}