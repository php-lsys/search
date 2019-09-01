<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use LSYS\Search;
use LSYS\Config;

abstract class Solr extends Search implements Index{
	
	public function __construct(Config $config){
	    $options=$config->asArray()+ array
		(
				'hostname' => '127.0.0.1',
				//'login'    => SOLR_SERVER_USERNAME,
				// 'password' => SOLR_SERVER_PASSWORD,
				'port'     => '8983',
				'path'     => $this->_index,
		);
		
		$this->_solr = new \SolrClient($options);
		
	}
	
	/**
	 * @var \SolrClient
	 */
	protected $_solr;
	/**
	 * @var \SolrQuery
	 */
	protected $_query;
	
	
	protected $_field=array(
		'features'
	);
	
	
	/**
	 * 查找匹配结果
	 * @return \LSYS\Search\Solr\Result
	 */
	public function query(Query $query){
		$keyword=$query->getQuery();
		if ($keyword instanceof QueryExpr)$_query=$this->parseExpr($keyword);
		else{
			$_query='';
			foreach ($this->_field as $v){
				$_query.=$v.":*".preg_replace("/\*+/","*",str_replace(array("*",":"," ","+","-","[","]"),'*', $keyword))."* OR ";
			}
			$_query=trim($_query," OR");
		}
		$this->_query=$squery = new \SolrQuery();
		if(empty($_query))$_query='*:*';
		$this->_where($query->getWhere());
		$this->_sort($query->getSort());
		$squery->setQuery($_query);
		$squery->setStart($query->getOffset());
		$squery->setRows($query->getLimit());
		if($query->getHighlight()){
			$squery->setHighlight(1);
			$squery->setHighlightRequireFieldMatch(true);
			foreach ($query->getHighlight()->getFields() as $v){
				$squery->addHighlightField($v);
			}
		}
		$query_response = $this->_solr->query($squery);
		$result = $query_response->getResponse();
		return new \LSYS\Search\Solr\Result($this,$query, $result);
	}
	/**
	 * {@inheritDoc}
	 * @see Search::setAttr()
	 */
	public function updateIndex($pk,array $vals){
		throw new \Exception("not support");
	}
	
	public function insertIndex(array $record){
	    throw new \Exception("not support");
		$options = array
		(
				'hostname' => SOLR_SERVER_HOSTNAME,
				// 'login'    => SOLR_SERVER_USERNAME,
				//'password' => SOLR_SERVER_PASSWORD,
				'port'     => SOLR_SERVER_PORT,
				'path'     => SOLR_SERVER_PATH,
		);
		
		$client = new \SolrClient($options);
		
		$doc = new \SolrInputDocument();
		
		$doc->addField('id', 334455);
		$doc->addField('cat', 'm朋友可能觉得in ');
		$doc->addField('cat', 'ete f朋友可能觉得');
		$doc->addField('features', "wo朋友可能觉得1251");
		$doc->addField('timestamp', time());
		$updateResponse = $client->addDocument($doc);
		print_r($updateResponse->getResponse());
		/* you will have to commit changes to be written if you didn't use $commitWithin */
		$client->commit();
		
	}
	
	/**
	 * {@inheritDoc}
	 * @see Search::del()
	 */
	public function deleteIndex($pk){
	    throw new \Exception("not support");
	}
	
	public function parseExpr(QueryExpr $expr){
	    throw new \Exception("not support");
	}

}