<?php

namespace app\controller;

use think\facade\Request;

class Weixin
{
    public function getMenu()
    {
        $menu = &load_wechat('menu');

        // 取消发布微信菜单
        $result = $menu->getMenu();

        // 处理创建结果
        if ($result === FALSE) {
            // 接口失败的处理
            return success(null, $menu->errMsg, 500);
        } else {
            // 接口成功的处理
            return success($result, "成功");
        }
    }
    public function createMenu()
    {
        $data = Request::param('menus', "{}");
        // 实例微信菜单接口
        $menu = &load_wechat('menu');

        // 创建微信菜单
        $result = $menu->createMenu(json_decode($data));

        // 处理创建结果
        if ($result === FALSE) {
            // 接口失败的处理
            return success(null, $menu->errMsg, 500);
        } else {
            // 接口成功的处理
            return success($result, "成功");
        }
    }
}
