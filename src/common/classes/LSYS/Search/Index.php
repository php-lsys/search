<?php
/**
 * lsys search
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Search;
interface Index{
	/**
	 * insert data to search index
	 * @param mixed $data
	 * @return bool
	 */
	public function insertIndex(array $record);
}