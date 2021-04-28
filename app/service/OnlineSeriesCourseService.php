<?php
/*
 * @Description  : 线上系列课程管理
 */
namespace app\service;

use think\facade\Db;

class OnlineSeriesCourseService {
    // 获取总的单课数量
    public static function getTotal($where=[]) {
        $total = Db::table('many_courses')
            ->where($where)
            ->count();
        return $total;
    }
     // 查询列表
     public static function list($pageSize, $page, $where=[], $order='update_time desc') {
        $field = [
            "course_cover",
            "course_name",
            "course_cate",
            "pay_type",
            "create_time",
            "update_time",
            "many_courses.id as id",
            "price",
            "time",
            "count(many_courses.id) as nums"
        ];
        
        $list = Db::name('many_courses')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($pageSize)
            ->order($order)
            ->leftJoin(['course_price'=>'cp'],'many_courses.id=cp.course_id')
            ->group('many_courses.id')
            ->select()
            ->toArray();
        
        return $list;
    }

    public static function listByPid($pageSize, $page, $where=[], $order='order desc, update_time desc') {
        $field = [
            "id",
            "course_name",
            "course_type",
            "course_cover",
            "course_video",
            "create_time",
            'open',
            "order"
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


    public static function getCoursesEasy() {
        $list = Db::name('many_courses')
            ->field(['course_name', 'id'])
            ->select()
            ->toArray();
        
        return $list;
    }

    // 获取单个系列课中的所有的单课
    public static function getCoursesByPid($params) {
        $obj = Db::name('courses')
        ->where($params)
        ->find();
        return $obj;
    }



    // 单个系列课
    public static function getCourse($params) {
        $obj = Db::name('many_courses')
            ->where($params)
            ->find();

        $arr = Db::name('course_price')
            ->where(['course_id'=>$obj['id']])
            ->select()
            ->toArray();

        $obj['pay_money_type'] = $arr;
        return $obj;
    }


    /*  
    * 添加课程
    */
    public static function addCourse($params) {
        $param['course_name'] = $params['course_name'];
        $param['admin_user_id'] = $params['admin_user_id'];
        $pay_type = $param['pay_type'] = $params['pay_type'];
        $pay_money_type = $params['pay_money_type'];

        $param['create_time'] = date('Y-m-d H:i:s');
        $param['update_time'] = date('Y-m-d H:i:s');
        $param['course_content'] = "待添加课程内容";    

        $insertId = Db::name('many_courses')->strict(false)->insertGetId($param);

        if (count($pay_money_type) > 0) {
            $return = self::addPayMoneyType($insertId, $pay_money_type, $pay_type);
            if ($return) {
                return $insertId;
            }
        }
        
        return $insertId;
    }


    // 完善课程
    public static function doneCourse($param, $id) {
        $pay_money_type = $param['pay_money_type'];
        unset($param['pay_money_type']);
        $pay_type = $param['pay_type'];
        
        $param['update_time'] = date('Y-m-d H:i:s');
   
        $res = Db::name('many_courses')
            ->where('id', $id)
            ->update($param);

           
        Db::name('course_price')
            ->where('course_id', $id)
            ->delete();
        
        $return = self::addPayMoneyType($id, $pay_money_type, $pay_type);

        if ($return) {
            return $res;
        }
        return $return;
    }

    public static function addPayMoneyType($course_id, $pay_money_type, $pay_type) {
        $temp =[];
        
        foreach($pay_money_type as $val) {
            if (isset($val['id'])) {
                unset($val['id']);
            }
            $id = Db::name('course_price')->strict(false)->insertGetId([
                "course_id" => $course_id,
                "money_type" => $pay_type,
                "price" => $val['price'],
                "time" => $val['time']
            ]);
            array_push($temp, $id);
        }
        if (count($temp) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function removeSeriesCourse($course_id) {
        $update_time = date('Y-m-d H:i:s');
        $res = Db::name('courses')
            ->where('id', $course_id)
            ->update([
                'pid'=>0,
                'open'=>0, 
                'order'=>0,
                'update_time'=>$update_time
            ]);
        return $res;
    }   

    public static function openListen($course_id) {
        $update_time = date('Y-m-d H:i:s');
        $res = Db::name('courses')
            ->where('id', $course_id)
            ->update(['open'=>1,  'update_time'=>$update_time]);
        return $res;
    }

    public static function closeListen($course_id) {
        $update_time = date('Y-m-d H:i:s');
        $res = Db::name('courses')
            ->where('id', $course_id)
            ->update(['open'=>0,  'update_time'=>$update_time]);
        return $res;
    }

    public static function updateOrder($course_id, $order) {
        $update_time = date('Y-m-d H:i:s');
        $res = Db::name('courses')
            ->where('id', $course_id)
            ->update(['order'=>$order, 'update_time'=>$update_time]);
        return $res;
    }
}