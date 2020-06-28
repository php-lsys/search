<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
class Highlight{
	/**
	 * highlight factory
	 * @param array $fields
	 * @return Highlight
	 */
	public static function factory(array $fields){
		return new static($fields);
	} 
	/**
	 * highglight config
	 * @param array $fields
	 */
	public function __construct(array $fields){
		$this->setFields($fields);
	}
	/**
	 * 高亮前缀
	 * @var string
	 */
	protected $_highlight_before="<b style='color:red;'>";
	/**
	 * 高亮后缀
	 * @var string
	 */
	protected $_highlight_after="</b>";
	/**
	 * 高亮字段
	 * @var array
	 */
	protected $_highlight_fidlds=array();
	public function setBefore($tag){
		$this->_highlight_before=$tag;
		return $this;
	}
	public function setAfter($tag){
		$this->_highlight_after=$tag;
		return $this;
	}
	public function setField($field){
		return $this->_highlight_fidlds[]=trim(strip_tags($field));
	}
	public function setFields(array $fields){
		return $this->_highlight_fidlds=$fields;
	}
	public function getBefore(){
		return $this->_highlight_before;
	}
	public function getAfter(){
		return $this->_highlight_after;
	}
	public function getFields(){
		return $this->_highlight_fidlds;
	}
}
