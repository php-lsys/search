<?php
namespace Search;
use LSYS\Search\Sphinx;
class Product extends Sphinx{
	protected $_index='product';
	/**
	 * 销量倒序
	 * @var integer
	 */
	const SORT_PAY_DESC=3;
	/**
	 * 销量正序
	 * @var integer
	 */
	const SORT_PAY_ASC=4;
	/**
	 * 价格倒序
	 * @var integer
	 */
	const SORT_PRICE_DESC=5;
	/**
	 * 价格正序
	 * @var integer
	 */
	const SORT_PRICE_ASC=6;
	/**
	 * 人气倒序
	 * @var integer
	 */
	const SORT_HOT_DESC=7;
	/**
	 * 人气正序
	 * @var integer
	 */
	const SORT_HOT_ASC=8;
	/**
	 * int 店铺ID
	 * @var integer
	 */
	const WHERE_SHOP=1;
	/**
	 * int 分类ID
	 * @var integer
	 */
	const WHERE_CAT=2;
	
	public function __construct($config=null){
		parent::__construct($config);
		self::$_sphinx->SetFieldWeights(array(
				'title'=>100,
				'brand_name'=>20,
				'cat_name'=>10,
				'content'=>5,
		));
	
	}
	protected function _where(array $where){
		foreach ($where as $k=>$v){
			switch ($k){
				case self::WHERE_SHOP:
					$this->_setFilter("id_shop",intval($v));
					break;
				case self::WHERE_CAT:
					$this->_setFilter("id_product_cat",intval($v));
					break;
			}
		}
	}
	protected function _sort(array $sort){
		foreach ($sort as $v){
			switch ($v){
				case self::SORT_PAY_DESC:
					self::$_sphinx->SetSortMode(SPH_SORT_ATTR_DESC,"pay_all_count");
					break;
				case self::SORT_PAY_ASC:
					self::$_sphinx->SetSortMode(SPH_SORT_ATTR_ASC,"pay_all_count");
					break;
				case self::SORT_PRICE_DESC:
					self::$_sphinx->SetSortMode(SPH_SORT_ATTR_DESC,"sh_price");
					break;
				case self::SORT_PRICE_ASC:
					self::$_sphinx->SetSortMode(SPH_SORT_ATTR_ASC,"sh_price");
					break;
				case self::SORT_HOT_DESC:
					self::$_sphinx->SetSortMode(SPH_SORT_ATTR_DESC,"order_count");
					break;
				case self::SORT_HOT_ASC:
					self::$_sphinx->SetSortMode(SPH_SORT_ATTR_ASC,"order_count");
					break;
			}
		}
	}
}
