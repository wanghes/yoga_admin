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

        $where = ['sell_type' => 5];
        
        $list = OrderService::list($pageSize, $page, $where);
        $total = OrderService::getTotal();

        return success([
            "list"=>$list,
            "total"=>$total
        ], "获取成功");
    }


    public function chartList() {
        $start_date = Request::param('start_date', "");
        $end_date = Request::param('end_date', "");

        $where = ['sell_type' => 5];
        $date = [$start_date, $end_date];
        
        $list = OrderService::chartList($where, $date);

        return success([
            "list"=>$list
        ], "获取成功");
    }
}