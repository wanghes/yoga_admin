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

    public static function chartList($where=[], $date, $order='create_time asc') {
        $field=[
            'name', 
            'out_trade_no',
            'sum(total_fee) as money',
            'weixin_prepayid',
            "DATE_FORMAT(create_time, '%Y-%m-%d') as date"
        ];
        $list = Db::name('weixin_orders')
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