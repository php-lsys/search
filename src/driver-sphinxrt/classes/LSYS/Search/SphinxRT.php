<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Helper;
use LSYS\Search\SphinxRT\Result;
use LSYS\Search;
use LSYS\Exception;
use LSYS\Config;
/*
 * add sphinx rt support 
 * plase run : composer require foolz/sphinxql-query-builder
 */
abstract class SphinxRT extends Search implements Index{
	/**
	 * @var Connection
	 */
	protected static $_connection;
	protected static $_connection_list=array();
	/**
	 * connect shpinx mysql interface
	 */
	protected static function _connection(Config $config){
		$name=$config->name();
		if (!isset(self::$_connection_list[$name])){
			$s = new Connection();
			$_config=$config->asArray()+array(
				'host'=>'localhost',
				'port'=>9306,
			);
			$s->setParams($_config);
			self::$_connection_list[$name]=&$s;
		}
		return self::$_connection=self::$_connection_list[$name];
	}
	//your fill it --
	protected $_index_columns = array (/*rt field*/);
	protected $_map_columns = array (/*rt_attr_string field*/);
	//your fill it --
	
	/**
	 * @var SphinxQL
	 */
	protected $_sphinxql;
	
	public function __construct(Config $config){
		parent::__construct($config);
		$this->_index_columns['id']=NULL;
		foreach ($this->_map_columns as $k=>$v) $this->_index_columns[$v]=isset($this->_index_columns[$k])?$this->_index_columns[$k]:'';
		self::_connection($config);
		$this->_sphinxql=SphinxQL::create(self::$_connection);
	}
	/**
	 * find match record
	 * @return Result
	 */
	public function query(Query $query){
		$keyword=$query->getQuery();
		$this->_sphinxql->reset()->select('*');
		$this->_where($query->getWhere());
		$this->_sort($query->getSort());
		$this->_sphinxql->from($this->_index);
		if ($keyword instanceof QueryExpr){
			$this->parseExpr($keyword);
		}else{
			if(!$query->emptyQuery())$this->_sphinxql->match('*', strval($keyword));
		}
		try{
			$result = @$this->_sphinxql->execute();
		}catch (\Exception $e){
		    $message=$e->getMessage();
		    if (DIRECTORY_SEPARATOR === '\\'&&$this->_isGb2312($message)){
		        if(PHP_SAPI!=='cli'||PHP_SAPI==='cli'&&version_compare(PHP_VERSION,'7.0.0',">=")){
		            $message=iconv("gb2312", "utf-8",$message);//windows in china : cover string
		        }
		    }
		    throw new Exception($message,$e->getCode(),$e);
		}
		$data=$result->fetchAllAssoc();
		$result->freeResult();
		if (!is_array($data))$data=array();
		foreach ($data as $k=>$v){
			foreach ($this->_map_columns as $kk=>$vv){
				$data[$k][$kk]=isset($v[$vv])?$v[$vv]:'';
				unset($data[$k][$vv]);
			}
		}
		$result=Helper::create(self::$_connection)->showMeta()->execute();
		$meta=array();
		foreach ($result->fetchAllAssoc() as $v){
			$meta[$v['Variable_name']]=$v['Value'];
		}
		$result->freeResult();
		return new Result($this,$query, array(
			'matches'=>$data,
			'meta'=>$meta,
		), self::$_connection);
	}
	private function _isGb2312($str)
	{
	    for($i=0; $i<strlen($str); $i++) {
	        $v = ord( $str[$i] );
	        if( $v > 127) {
	            if( ($v >= 228) && ($v <= 233) )
	            {
	                if(($i+2) >= (strlen($str)- 1)) return true;  // not enough characters
	                $v1 = ord( $str[$i+1] );
	                $v2 = ord( $str[$i+2] );
	                if( ($v1 >= 128) && ($v1 <=191) && ($v2 >=128) && ($v2 <= 191) ) // utf编码
	                    return false;
	                    else
	                        return true;
	            }
	        }
	    }
	    return true;
	}
	/**
	 * {@inheritDoc}
	 * @see Search::setAttr()
	 */
	public function updateIndex($pk,array $vals){
		return $result = SphinxQL::create(self::$_connection)
		->update($this->_index)
		->where('id','=',$pk)
		->set($vals)
		->execute()
		->getStored();
	}
	/**
	 * {@inheritDoc}
	 * @see Search::deleteIndex()
	 */
	public function deleteIndex($pk){
		return SphinxQL::create(self::$_connection)->delete()
			->from($this->_index)
			->where('id', '=', intval($pk))
			->execute()
			->getStored();
	}
	/**
	 * ->insertIndex(array( 'id'=>1, 'title'=>'test','multi'=>array(1,2)));
	 * {@inheritDoc}
	 * @see Index::insertIndex()
	 */
	public function insertIndex(array $record){
		$sq=SphinxQL::create(self::$_connection)->insert()
		->into($this->_index)
		->columns(array_keys($this->_index_columns));
		$sp = $this->_sphinxql;
		foreach (func_get_args() as $record){
			if (!isset($record['id']))continue;
			$sp->reset()
			->select('id')
			->from($this->_index)
			->where("id", "=",intval($record['id']));
			try{
				$result = $this->_sphinxql->execute();
			}catch (\Exception $e){}
			if(boolval($result->count()))$this->deleteIndex($record['id']);
			$value=array();
			foreach ($this->_index_columns as $k=>$v){
				$value[$k]=isset($record[$k])?$record[$k]:$v;
			}
			foreach ($this->_map_columns as $k=>$v){
				$value[$v]=isset($value[$k])?$value[$k]:'';
			}
			$sq->values($value);
		}
		if (!isset($value)) return false;
		return $sq->execute()->getAffectedRows();
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Search::parseExpr()
	 */
	public function parseExpr(QueryExpr $expr){
		$c=explode("or",strtolower(strval($expr)));
		$expr=array();
		foreach ($c as $v){
			$k=explode(":", $v);
			if (count($k)==2){
				$expr[trim($k[0])]=trim($k[1],' "');
			}
		}
		if (is_array($expr)){
			foreach ($expr as $k=>$v){
				$this->_sphinxql->match($k,SphinxQL::expr($v));
			}
		}
	}
	/**
	 * create index conf
	 * @param string $path
	 * @return string
	 */
	public function toIndexConf($path='/var/lib/sphinxsearch/data/'){
		$field=array();
		$fkey=array_values($this->_map_columns);
		foreach ($this->_index_columns as $k=>$v){
			if (in_array($k, $fkey)||$k=='id')continue;
			if (is_float($v)){
				$field[]="rt_attr_float		= {$k}";
			}else if (is_bool($v)){
				$field[]="rt_attr_bool		= {$k}";
			}else if (is_int($v)){
				$field[]="rt_attr_uint		= {$k}";
			}else if (is_array($v)){
				$field[]="rt_attr_multi		= {$k}";//为整数数组
			}else{
				$field[]="rt_field			= {$k}";
			}
		}
		foreach ($this->_map_columns as $k=>$v){
			$field[]="rt_attr_string		= {$v}";
		}
		$field=implode("\n\t", $field);
		$tpl=<<<TPL
index {$this->_index}
{
	type				= rt
	path				= {$path}{$this->_index}
	{$field}
}
TPL;
		return $tpl;
	}
}