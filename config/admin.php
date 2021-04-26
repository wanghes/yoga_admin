<?php
/*
 * @Description  : admin配置
 */

return [
    // 系统管理员id
    'admin_ids' => [1],
    // 是否记录日志
    'is_log' => true,
    // 请求头部token键名
    'admin_token_key' => 'AdminToken',
    // 请求头部user_id键名
    'admin_user_id_key' => 'AdminUserId',
    // 接口白名单
    'api_white_list' => [
        '/login',
        '/admin/add',
        '/'
    ],
    // 请求频率限制（次数/时间）
    'throttle' => [
        'number' => 3, //次数,0不限制
        'expire' => 1, //时间,单位秒
    ],
];
