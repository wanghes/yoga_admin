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
    

        $where = ['login_type' => 1];

        $list = ManageVenuesService::list($pageSize, $page, $where);
        $total = ManageVenuesService::getTotal($where);

        return success([
            "list"=>$list,
            "total"=>$total,
            "currentPage"=>$page
        ], "获取成功");
    }
}
