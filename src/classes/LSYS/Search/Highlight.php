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
		$this->set_fields($fields);
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
	public function set_before($tag){
		$this->_highlight_before=$tag;
		return $this;
	}
	public function set_after($tag){
		$this->_highlight_after=$tag;
		return $this;
	}
	public function set_field($field){
		return $this->_highlight_fidlds[]=trim(strip_tags($field));
	}
	public function set_fields(array $fields){
		return $this->_highlight_fidlds=$fields;
	}
	public function get_before(){
		return $this->_highlight_before;
	}
	public function get_after(){
		return $this->_highlight_after;
	}
	public function get_fields(){
		return $this->_highlight_fidlds;
	}
}
