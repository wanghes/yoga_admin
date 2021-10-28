<?php

namespace app\service;
// 添加管理员的控制器
use think\facade\Db;
use app\common\utils\Random;

class ManageVenuesService {
    // 获取总的单课数量
    public static function getTotal($where=[]) {
        $total = Db::table('admin_user')
            ->where($where)
            ->count();
        return $total;
    }
    public static function list($pageSize=10, $page=1, $where=[], $order='create_time desc') {
        $field=[
            'admin_user.admin_user_id as admin_user_id',
            'nickname', 
            'venues.name as name', 
            'venues_id',
            'is_disable',
            'avatar',
            'init_password',
            'is_delete',
            'login_type',
            'admin_user.create_time as create_time',
            'login_time',
            'phone'
        ];
        $list = Db::name('admin_user')
            ->field($field)
            ->where($where)
            ->leftJoin("venues", 'venues.admin_user_id=admin_user.admin_user_id')
            ->page($page)
            ->limit($pageSize)
            ->order($order)
            ->select()
            ->toArray();
        return $list;
    }

    public static function add($params) {
        $data['phone'] = $params['phone'];
        $data['name'] = $params['name'];
        $data['login_type'] = 1;
        $uuid = Random::uuid();
        $data['admin_user_id'] = $uuid;
        $randID = Random::build();
        $data['venues_id'] = $randID;
        // 设置默认的8位密码
        $data['init_password'] = getRandomString(8);
        
        $data['password'] = md5($data['init_password']);

        $data['create_time'] = date('Y-m-d H:i:s');
        
         
        $insertId = Db::name('admin_user')->strict(false)->insertGetId($data);
        return $insertId;
    }


    public static function chartList($where=[], $date, $order='create_time asc') {
        $field=[
            "count(DATE_FORMAT(create_time, '%Y-%m-%d')) as num",
            "DATE_FORMAT(create_time, '%Y-%m-%d') as date"
        ];
        $list = Db::name('admin_user')
            ->field($field)
            ->where($where)
            ->whereTime('create_time', 'between', $date)
            ->order($order)
            ->group('date')
            ->select()
            ->toArray();
        return $list;
    }
}