<?php
/*
 * @Description  : 线上单课课程管理
 */
namespace app\service;

use think\facade\Db;

class OnlineCourseService {
      // 获取总的单课数量
      public static function getTotal($where=[]) {
        $total = Db::table('courses')
            ->where($where)
            ->count();
        return $total;
    }

     // 查询列表
     public static function list($pageSize, $page, $where=[], $order='update_time desc') {
        $field = [
            "id",
            "course_name",
            "course_type",
            "course_cover",
            "course_video",
            "pay_type",
            "pay_money",
            "status",
            "create_time",
            "pid"
        ];
        
        $list = Db::name('courses')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($pageSize)
            ->order($order)
            ->select()
            ->toArray();

        return $list;
    }

    /*  
    * 添加课程
    */
    public static function addCourse($params) {
        $param['course_name'] = $params['course_name'];
        $param['course_type'] = $params['course_type'];
        $param['pay_type'] = $params['pay_type'];
        $param['pay_money'] = $params['pay_money'];
        $param['pid'] = $params['pid'];
        $param['status'] = $params['status'];
        $param['create_time'] = date('Y-m-d H:i:s');
        $param['update_time'] = date('Y-m-d H:i:s');
        $param['course_content'] = "待添加课程内容";    
        $param['admin_user_id'] = $params['admin_user_id'];    

        $insertId = Db::name('courses')->strict(false)->insertGetId($param);
        return $insertId;
    }

    public static function queryName($name) {
        $course = Db::name('courses')
            ->field('course_name')
            ->where(['course_name' => $name])
            ->find();
        return $course;
    }
  
    // 完善课程
    public static function getCourse($params) {
        $obj = Db::name('courses')
        ->where($params)
        ->find();
        return $obj;
    }

    // 完善课程
    public static function doneCourse($param) {
        $id = $param['id'];
        unset($param['id']);

        $param['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('courses')
        ->where('id', $id)
        ->update($param);

        if (empty($res)) {
            exception("完善失败", 400);
        }

        return $res;
    }

    public static function batchPids($ids, $pid) {
        $savenumber = 0;
        foreach($ids as $val){
            $savenumber += Db::name('courses')
                ->where('id', $val)
                ->update(['pid'=>$pid, 'update_time'=>date('Y-m-d H:i:s')]);
        }
        return $savenumber;
    }


    public static function updateStatus($params, $id) {
        $data['status'] = $params['status'];
        $data['update_time'] = date('Y-m-d H:i:s');

        $num = Db::name('courses')
            ->where('id', $id)
            ->update($data);
        
        return $num;
    }
    
    public static function deleteCourse($id) {
        $data['is_delete'] = 1;
        $data['update_time'] = date('Y-m-d H:i:s');

        $update = Db::name('courses')
            ->where('id', $id)
            ->update($data);
        
        return $update;
    }
}