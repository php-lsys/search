<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
class QueryExpr{
	/**
	 * query factory
	 * @param string $index
	 */
	public static function factory($string=''){
		return new static($string);
	}
	protected $_string;
	public function __construct($string=''){
		$this->_string=trim(preg_replace('/\s+/', ' ', $string));
	}	
	public function __toString(){
		return $this->_string;
	}
}
