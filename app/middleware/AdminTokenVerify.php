<?php

namespace app\middleware;

use Closure;
use think\facade\Request;
use think\facade\Response;
use think\facade\Config;
use app\service\TokenService;


class AdminTokenVerify
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */

    public function handle($request, Closure $next)
    {
        $menu_url       = request_pathinfo();
        $api_white_list = Config::get('admin.api_white_list');
        // echo $menu_url;
        if (!in_array($menu_url, $api_white_list)) {
            $admin_token = admin_token();   
            $admin_user_id = admin_user_id();

            if (empty($admin_token)) {
                exception('Requests Headers：AdminToken 必填'.$admin_user_id, 401);
            }

            if (empty($admin_user_id)) {
                exception('Requests Headers：AdminUserId 必填', 401);
            }

            TokenService::verify($admin_token, $admin_user_id);
        }

        return $next($request);
    }


}