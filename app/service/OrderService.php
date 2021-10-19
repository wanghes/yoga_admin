<?php

namespace app\service;
// 添加管理员的控制器
use think\facade\Db;
use app\common\utils\Random;

class OrderService {
    // 获取总的单课数量
    public static function getTotal($where=[]) {
        $total = Db::table('weixin_orders')
            ->where($where)
            ->count();
        return $total;
    }
    public static function list($pageSize=10, $page=1, $where=[], $order='create_time desc') {
        $field=[
            'sell_type',
            'sell_type_name', 
            'name', 
            'out_trade_no',
            'total_fee',
            'weixin_prepayid',
            'body',
            'openid',
            'create_time'
        ];
        $list = Db::name('weixin_orders')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($pageSize)
            ->order($order)
            ->select()
            ->toArray();
        return $list;
    }
}