<?php
namespace LSYS\Search;
/**
 * @method \LSYS\Search search()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->search)&&$di->search(new \LSYS\DI\VirtualCallback(\LSYS\Search::class));
        return $di;
    }
}