<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use app\service\ManageVenuesService;

class ManageVenues extends BaseController
{
    public function add()
    {
        $params['phone'] = Request::param('phone', ""); 
        $params['name'] = Request::param('name', ""); 
        
        $insertId = ManageVenuesService::add($params);
        return success([
            "insertId"=>$params
        ], "添加成功");
        if (isset($insertId)) {
            return success([
                "insertId"=>$insertId
            ], "添加成功");
        }

        return error("添加失败");
    }

    /**
     * login_type = 0 是系统管理员
     * login_type = 1 是场馆主管理员
     */
    public function list() {
        $page = Request::param('page', 1); 
        $pageSize = Request::param('pageSize');
        $phone =  Request::param('phone', "");
        $name =  Request::param('name', "");
        $status =  Request::param('status', 0);

        $where = ['login_type' => 1];   

        if (!empty($phone)) {
            $where['phone'] = $phone;
        }

        if (!empty($name)) {
            $where['name'] = $name;
        }

        if ($status != 0) {
            if ($status == 1) {
                $where['is_disable'] = 0;
                $where['is_delete'] = 0;
            } else if ($status == 2) {
                $where['is_disable'] = 1;
            } else if ($status == 3) {
                $where['is_delete'] = 1;
            }
        }

        $list = ManageVenuesService::list($pageSize, $page, $where);
        $total = ManageVenuesService::getTotal($where);

        return success([
            "list"=>$list,
            "total"=>$total,
            "currentPage"=>$page
        ], "获取成功");
    }

    public function chartList() {
        $start_date = Request::param('start_date', "");
        $end_date = Request::param('end_date', "");

        $where = ['login_type' => 1];
        $date = [$start_date, $end_date];
        
        $list = ManageVenuesService::chartList($where, $date);

        return success($list, "获取成功");
    }
}
