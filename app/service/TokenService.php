<?php
/*
 * @Description  : Token
 */

namespace app\service;

use think\facade\Config;
use app\common\cache\AdminUserCache;
use thans\jwt\facade\JWTAuth;

class TokenService
{
    /**
     * Token生成
     * 
     * @param array $admin_user 用户数据
     * 
     * @return string
     */
    public static function create($admin_user = [])
    {
        
        $data = [
            'admin_user_id' => $admin_user['admin_user_id'],
            'role'          => $admin_user['role'],
            'login_type'    => $admin_user['login_type'],
            'login_time'    => $admin_user['login_time']
        ];

        $token = JWTAuth::builder($data);
        return $token;
    }


    
    /**
     * Token验证
     *
     * @param string  $token         token
     * @param integer $admin_user_id 用户id
     * @return json
     */
    public static function verify($token, $admin_user_id = 0)
    {
        try {
            $payload = JWTAuth::auth();
        } catch (\Exception $e) {
            exception('账号登录状态已过期', 401);
        }

        $admin_user_id_token = $payload['admin_user_id']->getValue();

        if ($admin_user_id != $admin_user_id_token) {
            exception('账号请求信息错误', 401);
        } else {
            $admin_user = AdminUserCache::get($admin_user_id);
            if (empty($admin_user)) {
                exception('账号登录状态失效', 401);
            } else {
                if ($token != $admin_user['admin_token']) {
                    exception('账号已在另一处登录', 401);
                } else {
                    if ($admin_user['is_disable'] == 1) {
                        exception('账号已被禁用', 401);
                    }
                    if ($admin_user['is_delete'] == 1) {
                        exception('账号已被删除', 401);
                    }
                }
            }
        }
    }
}
