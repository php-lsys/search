<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use LSYS\Config;
use LSYS\Search;
use LSYS\Search\Sphinx\Result;
use LSYS\Exception;
abstract class Sphinx extends Search {
	/**
	 * SphinxClient
	 * @var \SphinxClient
	 */
	protected static $_sphinx;
	/**
	 * @param Config $config
	 */
	public function __construct(Config $config=null){
		parent::__construct();
		self::$_sphinx=\LSYS\SphinxClient\DI::get()->sphinxClient($config);
	}


// 	SPH_SORT_RELEVANCE;
// 	SPH_SORT_ATTR_DESC;
// 	SPH_SORT_ATTR_ASC;
// 	SPH_SORT_TIME_SEGMENTS;
// 	SPH_SORT_EXTENDED;
// 	SPH_SORT_EXPR;


	
	/**
	 * 设置指定过滤
	 * @param string $attribute
	 * @param int $values
	 * @param boolean $exclude
	 */
	protected function _setFilter($attribute, $values, $exclude=true){
		if(is_numeric($values)) $values=array($values);
		self::$_sphinx->SetFilter($attribute, $values, $exclude);
	}
	/**
	 * 设置范围过滤
	 * @param string $attribute
	 * @param int|float $min
	 * @param int|float $max
	 * @param boolean $exclude
	 */
	protected function _setFilterRange($attribute, $min, $max, $exclude=true){
		if(is_float($min)&&is_float($max)) self::$_sphinx->SetFilterFloatRange($attribute, $min, $max, $exclude);
		else self::$_sphinx->SetFilterRange($attribute, $min, $max, $exclude);
	}
	/**
	 * 查找匹配结果
	 * @return Result
	 */
	public function query(Query $query){
		$keyword=$query->getQuery();
		$sphinx=self::$_sphinx;
		$sphinx->ResetFilters();
		$sphinx->SetLimits ( $query->getOffset(),$query->getLimit() );
		$sphinx->SetFilter ( 'is_deleted', array(0) );
		$this->_where($query->getWhere());
		$this->_sort($query->getSort());
		if ($query instanceof QueryExpr)$result=$this->parseExpr($keyword);
		else{
			$_query=$sphinx->EscapeString($keyword);
			$result = $sphinx->query($_query,$this->_index);//查询
		}
		if (!$result)$error=$sphinx->GetLastError();
		if (is_array($result)&&!empty($result['error'])) $error=$result['error'];
		if (isset($error))throw new Exception(__("search error :msg",array(":msg"=>$error)));
		return new Result($this,$query, $result, self::$_sphinx);
	}
	/**
	 * {@inheritDoc}
	 * @see Search::setAttr()
	 */
	public function updateIndex($pk,array $vals){
		if (!$pk)return true;
		$field=array();
		$val=array();
		foreach ($vals as $k=>$v){
			$field[]=$k;
			$val[]=$v;
		}
		$s=self::$_sphinx;
		return $s->UpdateAttributes ($this->_index, $field, array($pk=>$val) );
	}
	/**
	 * {@inheritDoc}
	 * @see Search::del()
	 */
	public function deleteIndex($pk){
		if (!$pk)return true;
		$s=self::$_sphinx;
		return $s->UpdateAttributes ( $this->_index, array('is_deleted'), array($pk=>array(1)) );
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Search::parseExpr()
	 */
	public function parseExpr(QueryExpr $expr){
		$sphinx=self::$_sphinx;
		return $sphinx->query(strval($expr),$this->_index);//查询
	}
}
