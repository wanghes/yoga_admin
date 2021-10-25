<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('/', 'Test/index');
Route::post('/login', 'Login/login');
Route::post('/logout', 'Login/logout');
Route::post('/admin/add', 'Login/addAdmin');
Route::post('/info', 'Index/adminInfo');


// 场馆主
Route::post('/venues/add', 'ManageVenues/add');
Route::get('/venues/list', 'ManageVenues/list');

// 购买订单
Route::post('/order/list', 'Order/list');



// 微信
Route::post('/wx/menu/create', 'Weixin/createMenu');
Route::post('/wx/menu/get', 'Weixin/getMenu');



// 线上课程分类管理
Route::get('/online/cate/list', 'OnlineCate/list');
Route::post('/online/cate/add', 'OnlineCate/addCate');
Route::get('/online/cate/query', 'OnlineCate/query');
Route::delete('/online/cate/delete', 'OnlineCate/deleteCate');
Route::put('/online/cate/rename', 'OnlineCate/renameCate');

// 线上课程单课
Route::get('/online/course/list', 'OnlineCourse/list');
Route::post('/online/course/add', 'OnlineCourse/addCourse');
Route::post('/online/course/done', 'OnlineCourse/doneCourse');
Route::get('/online/course/get_course', 'OnlineCourse/getCourse');
Route::get('/online/course/list_by_pid', 'OnlineSeriesCourse/listByPid');
Route::put('/online/course/batch_pids', 'OnlineCourse/batchPids');
Route::put('/online/course/status', 'OnlineCourse/updateStatus');
Route::put('/online/course/delete', 'OnlineCourse/deleteCourse');

// 线上课程系列课
Route::post('/online/series/add', 'OnlineSeriesCourse/addCourse');
Route::post('/online/series/done', 'OnlineSeriesCourse/doneCourse');
Route::get('/online/series/list', 'OnlineSeriesCourse/list');
Route::get('/online/series/get_course', 'OnlineSeriesCourse/getCourse');
Route::get('/online/series/get_series_courses_easy', 'OnlineSeriesCourse/getCoursesEasy');
Route::put('/online/series/remove_course_from_series', 'OnlineSeriesCourse/removeSeriesCourse');
Route::put('/online/series/open_listen', 'OnlineSeriesCourse/openListen');
Route::put('/online/series/close_listen', 'OnlineSeriesCourse/closeListen');
Route::put('/online/series/update_order', 'OnlineSeriesCourse/updateOrder');