<?php
namespace app\controller;

use app\BaseController;
use app\service\OnlineCourseService;

class OnlineCourse extends BaseController
{
    public function list() {
        $page = $this->request->param('page', 1); 
        $pageSize = $this->request->param('pageSize');
        $status = $this->request->param('status', "");
        $course_name = $this->request->param('course_name', "");
        $admin_user_id = $this->admin_user_id;
        $where =["pid" => 0];

        $where['admin_user_id'] = $admin_user_id;

        if ($status != '') {
            $where['status'] = intval($status);
        }

        if ($course_name != '') {
            $where['course_name'] = $course_name;
        }

       
        $list = OnlineCourseService::list($pageSize, $page, $where);
        $total = OnlineCourseService::getTotal($where);

        return success([
            "list"=>$list,
            "total"=>$total,
            "currentPage"=>$page
        ], "获取成功");
    }

    // 添加单课
    public function addCourse() {
        $course_name = $param['course_name'] = $this->request->param('course_name', ''); // 课程名字
        $param['course_type'] = $this->request->param('course_type', '');  // 课程类型：1视频录播无互动，2视频录播有互动，3音频录播无互动，4，音频录播有互动
        $param['pay_type'] = $this->request->param('pay_type', 0); // 课程付费类型
        $param['pid'] = $this->request->param('pid', 0); // 默认不设置系列课 pid=0
        $param['status'] = 2; // 未上传资源的状态
        $param['admin_user_id'] = $this->admin_user_id;


        if ($param['pay_type'] == 1) {
            $param['pay_money'] = $this->request->param('pay_money'); // 付费课的金额
        } else {
            $param['pay_money']  = 0;
        }

        $course = OnlineCourseService::queryName($course_name);

        if ($course) {
            return success(null, '课程名字已存在：' . $course_name, 500);
        }

        try {
            $insertId = OnlineCourseService::addCourse($param);
            if ($insertId) {
                return success(['insertId'=>$insertId], '添加成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    // 获取单课详情
    public function getCourse() {
        $param['id'] = $this->request->param('id', '');
        $data = OnlineCourseService::getCourse($param);
        return success($data);
    }
    
    // 完善单课
    public function doneCourse() {
        $param['id'] = $this->request->param('id', ''); 
        $param['course_name'] = $this->request->param('course_name', '');  
        $param['course_cover'] = $this->request->param('course_cover', ''); 
        $param['course_video'] = $this->request->param('course_video', ''); 
        $param['course_leader'] = $this->request->param('course_leader', ''); 
        $param['leader_intro'] = $this->request->param('leader_intro', ""); 
        $param['course_content'] = $this->request->param('course_content', ""); 
        $param['status'] = 1;
        $param['pay_type'] = $this->request->param('pay_type', 0); 
        $param['pay_money'] = $this->request->param('pay_money', 0); 
        $param['play_time'] = $this->request->param('play_time', date('Y-m-d H:i:s'));
        
        try {
            $res = OnlineCourseService::doneCourse($param);
            if ($res) {
                return success(['update'=>true], '更新成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    // 批量修改单课为系列课的子课
    public function batchPids() {
        $ids = $this->request->param('ids', []); 
        $pid = $this->request->param('pid', 0);
        
        try {
            $updateNum = OnlineCourseService::batchPids($ids, $pid);
            if ($updateNum) {
                return success(['update'=>true], '更新成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
    
    public function updateStatus() {
        $id = $this->request->param('id', "");
        $params['status'] = $this->request->param('status', 1); 
        $update = OnlineCourseService::updateStatus($params, $id);

        if (isset($update)) {
            return success([
                "update"=>$update,
            ], "状态切换成功");
        }

        return error("状态切换失败");
    }


    public function deleteCourse() {
        $id = $this->request->param('id', "");
        exception('账号已被禁用，请联系管理员', 401);
        $del = OnlineCourseService::deleteCourse($id);

        if (isset($del)) {
            return success([
                "delete"=>$del,
            ], "删除成功");
        }

        return error("删除失败");
    }
}