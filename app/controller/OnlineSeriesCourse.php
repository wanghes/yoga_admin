<?php
namespace app\controller;

use app\service\OnlineCourseService;
use app\service\OnlineSeriesCourseService;
use app\BaseController;

class OnlineSeriesCourse extends BaseController
{
    public function list() {
        $page = $this->request->param('page', 1); 
        $pageSize = $this->request->param('pageSize', 5);
        $course_cate = $this->request->param('course_cate', "");
        $course_name = $this->request->param('course_name', "");
        $admin_user_id = $this->admin_user_id;

        $where =[];
        $where['admin_user_id'] = $admin_user_id;
        if (!empty($course_cate)) {
            $where['course_cate'] = $course_cate;
        }

        if (!empty($course_name)) {
            $where['course_name'] = $course_name;
        }
        
        $list = OnlineSeriesCourseService::list($pageSize, $page, $where);
        $total = OnlineSeriesCourseService::getTotal($where);
        
        return success([
            "list"=>$list,
            "total"=>$total,
            "currentPage"=>$page
        ], "获取成功");
    }

    public function getCoursesEasy() {
        $list = OnlineSeriesCourseService::getCoursesEasy();
        return success($list, "获取成功");
    }

    public function listByPid() {
        $page = $this->request->param('page', 1); 
        $pageSize = $this->request->param('pageSize');
        $course_name = $this->request->param('course_name', "");
        $pid = $this->request->param('id', '');
        $where =[ "pid" => $pid];

        if (!empty($course_name)) {
            $where['course_name'] = $course_name;
        }
        

        $list = OnlineSeriesCourseService::listByPid($pageSize, $page, $where);
        $total = OnlineCourseService::getTotal($where);
       
        return success([
            "list"=>$list,
            "total"=>$total,
            "currentPage"=>$page
        ], "获取成功");
    }

    public function addCourse() {
        $param['course_name'] = $this->request->param('course_name', ''); // 课程名字
        // pay_type = 1: 付费课的金额; pay_type = 2,3,4: 按时付费课的金额 2：天， 3：月，4：年
        $param['pay_type'] = $this->request->param('pay_type', 0); // 课程付费类型
        $param['admin_user_id'] = $this->admin_user_id;
        $param['pay_money_type'] = $this->request->param('pay_money_type', []);

        // validate(AdminManyCourseValidate::class)->scene('manycourse_add')->check($param);
  
        try {
            $insertId = OnlineSeriesCourseService::addCourse($param);
          
            if ($insertId) {
                return success(['insertId'=>$insertId], '添加成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function getCourse() {
        $param['id'] = $this->request->param('id', '');
        $data = OnlineSeriesCourseService::getCourse($param);
        return success($data);
    }

    public function doneCourse() {
        $id = $this->request->param('id', ''); 
        $param['course_name'] = $this->request->param('course_name', '');  
        $param['course_cate'] = $this->request->param('course_cate', ''); // 课程分类
        $param['course_num'] = $this->request->param('course_num', ''); // 课程计划
        $param['course_cover'] = $this->request->param('course_cover', ''); // 课程封面
        $param['course_content'] = $this->request->param('course_content', ""); 
        
        // pay_type = 1: 付费课的金额; pay_type = 2,3,4: 按时付费课的金额 2：天， 3：月，4：年
        $param['pay_type'] = $this->request->param('pay_type', 1); 
        $param['pay_money_type'] = $this->request->param('pay_money_type', []);
        
        try {
            $update = OnlineSeriesCourseService::doneCourse($param, $id);
            if ($update) {
                return success(['update'=>$update], '更新成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function removeSeriesCourse() {
        $course_id = $this->request->param('id', ''); 
        try {
            $delete_num = OnlineSeriesCourseService::removeSeriesCourse($course_id); 
            if ($delete_num) {
                return success(['update'=>$delete_num]);
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    // 开启试听
    public function openListen() {
        $course_id = $this->request->param('id', ''); 
    
        try {
            $num = OnlineSeriesCourseService::openListen($course_id); 
            if ($num) {
                return success(['update'=>$num]);
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }


    public function closeListen() {
        $course_id = $this->request->param('id', ''); 

        
        try {
            $num = OnlineSeriesCourseService::closeListen($course_id); 
            if ($num) {
                return success(['update'=>$num]);
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function updateOrder() {
        $course_id = $this->request->param('id', ''); 
        $order = $this->request->param('order', ''); 
        try {
            $num = OnlineSeriesCourseService::updateOrder($course_id, $order); 
            if ($num) {
                return success(['update'=>$num]);
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}