<?php

namespace app\service;
// 添加管理员的控制器
use think\facade\Db;
use app\common\utils\Random;
use app\common\cache\AdminUserCache;
use app\service\TokenService;


class AdminService {
    public static function addAdmin() {
        $data['name'] = "管理员";
        $data['nickname'] = "管理员";
        $data['login_type'] = 0;
        $uuid = Random::uuid();
        $data['admin_user_id'] = $uuid;
        // 设置默认的8位密码
        $data['init_password'] = getRandomString(8);
        
        $data['password'] = md5($data['init_password']);

        $data['create_time'] = date('Y-m-d H:i:s');
    
        $insertId = Db::name('admin_user')->strict(false)->insertGetId($data);

        return $insertId;
    }
    /**
     * 用户信息
     *
     * @param integer $admin_user_id 用户id
     * 
     * @return array
     */
    public static function info($admin_user_id)
    {
        $admin_user = AdminUserCache::get($admin_user_id);
        
        if (empty($admin_user)) {
            // 管理员登录
            $admin_user = Db::name('admin_user')
                ->where('admin_user_id', $admin_user_id)
                ->find();

            if (empty($admin_user)) {
                exception('用户不存在：' . $admin_user_id);
            }
            
            $admin_user['admin_user_id'] = $admin_user_id;
            $admin_user['login_time'] = date('Y-m-d H:i:s');
            $admin_user['role'] = 'admin'; 
          
            $admin_user['admin_token'] = TokenService::create($admin_user);
            AdminUserCache::set($admin_user_id, $admin_user);
        }

        return $admin_user;
    }
}