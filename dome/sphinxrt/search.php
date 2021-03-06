<?php
use Search\Product;
use LSYS\Search\Query;
use LSYS\Search\Highlight;
use LSYS\Search\ORMReuslt;
use LSYS\ORM\Active;

include __DIR__."/Bootstarp.php";
include "Search/Product.php";
include "Search/PSearch.php";


// //one
$ps= new Product();
$res=$ps->query(
		Query::factory("ptitle")->setPage(1)
		->setHighlight(
			Highlight::factory(array("title"))
		)
	);

var_dump($res->getTotal());
var_dump($res->getTime());

$res = new ORMReuslt($res, new Active("product"));


foreach ($res as $v){
	var_dump($v->getEntity()->pk());
	var_dump($v->getEntity()->asArray());
	var_dump($v->getHighlight("title"));
}


// exit;
//sphinxrt 适配时实时索引
// $ps= new PSearch();
// $ps->query(Query::factory("ptitle")->setPage(1)
// 		->setHighlight(
//     			Highlight::factory(array("title"))
//     		));
$ps->insertIndex(array(
		'id'=>$pm->id_product,
		'title'=>$pm->title,
		'order_count'=>$pm->order_count,
		'pay_all_count'=>$pm->pay_all_count,
		'product_photo_count'=>$pm->product_photo_count,
		'comment_good_count'=>$pm->comment_good_count,
		'is_delete'=>$pm->is_delete,
		'id_product_cat'=>$pm->id_product_cat,
		'sh_price'=>$pm->sh_price,
		'discount'=>$pm->discount,
		'product_type'=>$pm->product_type,
		'id_shop'=>$pm->id_shop,
		'id_product_group'=>$pm->id_product_group,
		'id_brand'=>$pm->id_brand,
		'attrs'=>array(111,333,444)
));
