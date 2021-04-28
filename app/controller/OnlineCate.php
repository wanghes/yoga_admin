<?php
namespace app\controller;

use app\BaseController;
use app\service\OnlineCateService;

class OnlineCate extends BaseController
{
    public function list() {
        $list = OnlineCateService::list();
        return success($list, "获取成功");
    }

    public function query() {
        $id = $this->request->param('id', '');
        $obj = OnlineCateService::query($id);
        return success($obj, "获取成功");
    }

    public function addCate() {
        $param['name'] = $this->request->param('name', ''); // 分类名字
        // validate(AdminCateValidate::class)->scene('cate_add')->check($param);

        try {
            $insertId = OnlineCateService::addCate($param);
            if ($insertId) {
                return success(['insertId'=>$insertId], '添加成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage(), '', 500);
        }
    }

    public function deleteCate() {
        $id = $this->request->param('id', ''); // 分类id
        if (empty($id)) {
            return error("删除id必填", '', 500);
        }
        try {
            $deleteNum = OnlineCateService::deleteCate($id);
            if ($deleteNum != 0) {
                return success(['deleteNum'=>$deleteNum], '删除成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'', 500);
        }
    } 
 
    public function renameCate() { 
        $param['id'] = $this->request->param('id', '');    // 分类id
        $param['name'] = $this->request->param('name', ''); // 分类名字

        // validate(AdminCateValidate::class)->scene('cate_edit')->check($param);
    
        try {
            $updateNum = OnlineCateService::renameCate($param);
            if ($updateNum != 0) {
                return success(['updateNum'=>$updateNum], '修改成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'', 500);
        }
    } 
}