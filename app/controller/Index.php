<?php
namespace app\controller;

use app\BaseController;

use app\service\AdminService;

class Index extends BaseController
{
    public function adminInfo() {
        $admin_user_id = $this->request->param('admin_user_id', '');
        $role = $this->request->param('role', '');

        $data = AdminService::adminInfo($admin_user_id, $role);

        if ($data['is_delete'] == 1) {
            exception('账号信息错误，请重新登录！');
        }

        return success($data);
    }

    
}
