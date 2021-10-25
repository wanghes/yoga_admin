<?php
/*
 * @Description  : 公共文件
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-04-16
 * @LastEditTime : 2021-01-27
 */

use think\facade\Request;
use think\facade\Config;
use thans\jwt\facade\JWTAuth;


function alert($obj) {
    echo '<pre>';
    print_r($obj);
    echo '</pre>';
}

/**
 * 成功返回
 *
 * @param array   $data 成功数据
 * @param string  $msg  成功提示
 * @param integer $code 成功码
 * 
 * @return json
 */
function success($data = [], string $msg = '操作成功', int $code = 200)
{
    $res['code'] = $code;
    $res['msg']  = $msg;
    $res['data'] = $data;

    return json($res);
}

/**
 * 错误返回
 *
 * @param string  $msg  错误提示
 * @param array   $err  错误数据
 * @param integer $code 错误码
 * 
 * @return json
 */
function error(string $msg = '操作失败', $err = [], int $code = 400)
{
    $res['code'] = $code;
    $res['msg']  = $msg;
    $res['err']  = $err;

    print_r(json_encode($res, JSON_UNESCAPED_UNICODE));

    exit;
}

/**
 * 抛出异常
 *
 * @param string  $msg  异常提示
 * @param integer $code 错误码
 * 
 * @return json
 */
function exception(string $msg = '操作失败', int $code = 400)
{
    throw new \think\Exception($msg, $code);
}

/**
 * 服务器地址
 * 协议和域名
 *
 * @return string
 */
function server_url()
{
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        $http = 'https://';
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        $http = 'https://';
    } else {
        $http = 'http://';
    }

    $host = $_SERVER['HTTP_HOST'];
    $res  = $http . $host;

    return $res;
}

/**
 * 文件地址
 * 协议，域名，文件路径
 *
 * @param string $file_path 文件路径
 * 
 * @return string
 */
function file_url($file_path = '')
{
    if (empty($file_path)) {
        return '';
    }

    if (strpos($file_path, 'http') !== false) {
        return $file_path;
    }

    $server_url = server_url();

    if (stripos($file_path, '/') === 0) {
        $res = $server_url . $file_path;
    } else {
        $res = $server_url . '/' . $file_path;
    }

    return $res;
}

/**
 * 获取请求pathinfo
 * 应用/控制器/操作 
 * eg：admin/Index/index
 *
 * @return string
 */
function request_pathinfo()
{
    $request_pathinfo = app('http')->getName() . '/' . Request::pathinfo();

    return $request_pathinfo;
}

/**
 * http get 请求
 *
 * @param string $url    请求地址
 * @param array  $header 请求头部
 *
 * @return array
 */
function http_get($url, $header = [])
{
    if (empty($header)) {
        $header = [
            "Content-type:application/json;",
            "Accept:application/json"
        ];
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);

    return $response;
}

/**
 * http post 请求
 *
 * @param string $url    请求地址
 * @param array  $param  请求参数
 * @param array  $header 请求头部
 *
 * @return array
 */
function http_post($url, $param = [], $header = [])
{
    $param  = json_encode($param);

    if (empty($param)) {
        $header = [
            "Content-type:application/json;charset='utf-8'",
            "Accept:application/json"
        ];
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);

    return $response;
}


function getRandomString($length) 
{
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    $key = "";
    for($i=0; $i<$length; $i++) 
    { 
        $key .= $pattern[mt_rand(0,35)];    
    } 
    return $key;
}


/**
 * 获取请求用户id
 *
 * @return integer
 */
function admin_user_id()
{
    $admin_user_id_key = Config::get('admin.admin_user_id_key');
    $admin_user_id     = Request::header($admin_user_id_key, '');
    return $admin_user_id;
}

/**
 * 获取请求用户token
 *
 * @return string
 */
function admin_token()
{
    $admin_token_object = JWTAuth::token();
    $admin_token = "";
    if (isset($admin_token_object)) {
        $admin_token = $admin_token_object->get();
    }
    return $admin_token;
}

function ROOTPATH() 
{
    return realpath(dirname(__FILE__).'/../');
}


/**
 * 获取微信操作对象（单例模式）
 * @staticvar array $wechat 静态对象缓存对象
 * @param type $type 接口名称 ( Card|Custom|Device|Extend|Media|Oauth|Pay|Receive|Script|User ) 
 * @return \Wehcat\WechatReceive 返回接口对接
 */
function & load_wechat($type = '') {
    static $wechat = array();
    $index = md5(strtolower($type));
    if (!isset($wechat[$index])) {
    	// 定义微信公众号配置参数（这里是可以从数据库读取的哦）
       $options = array(
            'token'           => 'jinyu', // 填写你设定的key
            'appid'           => 'wx3b15302b79d459b7', // 填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'       => '5c1c9a947e4e7eeec136b7918e0a6e53', // 填写高级调用功能的密钥
            'encodingaeskey'  => '1RBRUno02FE5IcUGEvnpefBsRCBv6kDbjaowD3XIZBD', // 填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
            'mch_id'          => '1610170609', // 微信支付，商户ID（可选）
            'partnerkey'      => '30QPNJVBZU1cuE94zqAM7IH8kpTyXRFC', // 微信支付，密钥（可选）
            'ssl_cer'         => '', // 微信支付，双向证书（可选，操作退款或打款时必需）
            'ssl_key'         => '', // 微信支付，双向证书（可选，操作退款或打款时必需）
            'cachepath'       => ROOTPATH().'/weixinCache', // 设置SDK缓存目录（可选，默认位置在Wechat/Cache下，请保证写权限）
        );
        \Wechat\Loader::config($options);
        $wechat[$index] = \Wechat\Loader::get($type);
    }
    return $wechat[$index];
}