<?php
/*
 * @Description  : 线上课程分类管理
 */
namespace app\service;

use think\facade\Db;

class OnlineCateService {
    // 查询
    public static function list($order='id desc') {
        $list = [];
        $list = Db::name('cates')
            ->order($order)
            ->select()
            ->toArray();
        return $list;
    }

    public static function query($id) {
        $obj = Db::name('cates')
            ->where(["id" => $id])
            ->find();
        return $obj;
    }

    /*  
    * 添加分类
    */
    public static function addCate($params) {
        $param['name'] = $params['name'];
        $insertId = Db::name('cates')->strict(false)->insertGetId($param);
        return $insertId;
    }

    // 删除
    public static function deleteCate($id) {
        $num = Db::name('cates')
            ->where('id', $id)
            ->delete();
        return $num;
    }

    // 修改
    public static function renameCate($param) {
        $num = Db::name('cates')
            ->where('id', $param['id'])
            ->update(['name' => $param['name']]);
        return $num;
    }


}