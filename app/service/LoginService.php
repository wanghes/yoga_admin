<?php

namespace app\service;
// 添加管理员的控制器
use think\facade\Db;
use app\common\cache\AdminUserCache;
use app\service\AdminService;

class LoginService {
    public static function login($param) {
        $name = $param['name'];
        $password = md5($param['password']);
        $field = [
            'admin_user_id', 
            'name', 
            'nickname', 
            'is_disable', 
            'is_delete', 
            'login_type', 
            'login_num'
        ];

        $where[] = ['name', '=', $name];
        $where[] = ['password', '=', $password];
        $where[] = ['is_delete', '=', 0];
        $where[] = ['is_disable', '=', 0];
        $where[] = ['login_type', '=', 0];

        $admin_user = Db::name('admin_user')
            ->field($field)
            ->where($where)
            ->find();

        if (empty($admin_user)) {
            exception('账号或密码错误');
        }

        if ($admin_user['is_disable'] == 1) {
            exception('账号已被禁用，请联系管理员');
        }

        if ($admin_user['is_delete'] == 1) {
            exception('账号已被删除，请联系管理员');
        }

        $admin_user_id = $admin_user['admin_user_id'];

    
        AdminUserCache::del($admin_user_id);

        $admin_user = AdminService::info($admin_user_id);

        $data['admin_user_id'] = $admin_user_id;
        $data["admin_token"] = $admin_user['admin_token'];
        $data['is_admin'] = true;
        $data['role'] = "admin";
    
        return $data;
    }


     /**
     * 退出
     *
     * @param integer $admin_user_id 用户id
     * 
     * @return array
     */
    public static function logout($admin_user_id)
    {
        $update['logout_time'] = date('Y-m-d H:i:s');
        
        Db::table('admin_user')
            ->where('admin_user_id', $admin_user_id)
            ->update($update);

        $update['admin_user_id'] = $admin_user_id;

        AdminUserCache::del($admin_user_id);

        return $update;
    }
}