<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use app\service\OrderService;

class Order extends BaseController
{
    public function list() {
        $page = Request::param('page', 1); 
        $pageSize = Request::param('pageSize');
        
        $list = OrderService::list($pageSize, $page);
        $total = OrderService::getTotal();

        return success([
            "list"=>$list,
            "total"=>$total
        ], "获取成功");
    }
}