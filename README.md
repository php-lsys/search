#搜索接口
> 统一的搜索引擎封装,关于搜索PHP搜索引擎资料可参考:http://php.net/manual/zh/refs.search.php
> 目的在于在开发业务需求时不要关系底层搜索的实现,方便在业务变更时快速切换底层搜索引擎而无需修改代码


示例代码:
```
//关于 product 参考:dome/Search/Product.php
$ps= new Product();
$res=$ps->query(
		Query::factory("ptitle")->set_page(1)
		->set_highlight(
			Highlight::factory(array("title"))
		)
	);

var_dump($res->get_total());
var_dump($res->get_time());

foreach ($res as $v){
	var_dump($v->pk());
	var_dump($v->get_highlight("title"));
}

```


```
//配合Lorm使用,可将搜索结果直接转换为ORM,方便使用
//需先加载 composer require lsys/orm
//更多关于ORM的操作参见 lsys/orm
//转换为ORM结果
$res = new ORMReuslt($res, new Active("product"));
foreach ($res as $v){
	var_dump($v->get_entity()->pk());
	var_dump($v->get_entity()->asArray());
	var_dump($v->get_highlight("title"));
}
```