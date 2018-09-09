<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
use LSYS\Search;

abstract class Result implements \Countable, \Iterator, \SeekableIterator{
	protected $_result;
	/**
	 * @var Query
	 */
	protected $_query;
	/**
	 * @var Search
	 */
	protected $_search;
	// Total number of rows and current row
	protected $_total_rows  = 0;
	protected $_current_row = 0;
	/**
	 * search record result
	 * @param  $query
	 * @param  $result
	 */
	public function __construct(Search $search,Query $query,$result){
		$this->_query=$query;
		$this->_result=$result;
		$this->_search=$search;
	}
	/**
	 * get search query object
	 * @return 
	 */
	public function get_query(){
		return $this->_query;
	}
	/**
	 * {@inheritDoc}
	 * @see \Countable::count()
	 */
	public function count()
	{
		return $this->_total_rows;
	}
	/**
	 * Implements [Iterator::key], returns the current row number.
	 *
	 *     echo key($result);
	 *
	 * @return  integer
	 */
	public function key()
	{
		return $this->_current_row;
	}
	/**
	 * Implements [Iterator::next], moves to the next row.
	 *
	 *     next($result);
	 *
	 * @return  $this
	 */
	public function next()
	{
		++$this->_current_row;
		return $this;
	}
	
	/**
	 * Implements [Iterator::prev], moves to the previous row.
	 *
	 *     prev($result);
	 *
	 * @return  $this
	 */
	public function prev()
	{
		--$this->_current_row;
		return $this;
	}
	
	/**
	 * Implements [Iterator::rewind], sets the current row to zero.
	 *
	 *     rewind($result);
	 *
	 * @return  $this
	 */
	public function rewind()
	{
		$this->_current_row = 0;
		return $this;
	}
	
	/**
	 * Implements [Iterator::valid], checks if the current row exists.
	 *
	 * [!!] This method is only used internally.
	 *
	 * @return  boolean
	 */
	public function valid()
	{
		return $this->_current_row >= 0 AND $this->_current_row < $this->_total_rows;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \SeekableIterator::seek()
	 */
	public function seek($offset)
	{
		if ($offset < 0 OR $offset >= $this->_total_rows)
		{
			return false;
		}
		$this->_current_row = $offset;
	}
	 /**
	  * get search record total
	  */
	abstract public function get_total();
	/**
	 * get search use time
	 */
	abstract public function get_time();
	/**
	 * @return ResultItem 
	 */
	abstract public function current ();
}
