<?php
namespace app\controller;

use think\facade\Request;
use app\service\LoginService;
use app\service\AdminService;

class Login
{
    public function index()
    {
        echo Request::param('as', 1);
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }
    // 管理员登录
    public function login()
    {
        $param['name'] = Request::param('username', "");
        $param['password'] = Request::param('password', "");

        $data = [];

        $data = LoginService::login($param);
        return success($data, '登录成功');
    }   

    /**
     * 退出
     *
     * @method POST
     * 
     * @return json
     */
    public function logout()
    {
        $admin_user_id = admin_user_id();	
        // return success($admin_user_id, '退出成功');
        // validate(AdminUserValidate::class)->scene('user_id')->check($param);

        $data = LoginService::logout($admin_user_id);
        return success($data, '退出成功');
    }



    // 添加管理员的地方
    public function addAdmin() {
        try {
            $insertId = AdminService::addAdmin();
            if ($insertId) {
                return success(['insertId'=>$insertId], '添加成功');
            }
        } catch (\Exception $e) {
            return error($e->getMessage(), '', 500);
        }
    }
}   

