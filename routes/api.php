<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*用户(yh)*/
Route::post('register','UsersController@yh_register');//用户注册
Route::post('email','UsersController@yh_email');//发送邮箱验证码
Route::post('login','UsersController@yh_login');//用户登录
Route::post('repassword','UsersController@yh_repassword');//忘记密码
Route::post('lost_pwd','UsersController@yh_lost_pwd');//修改密码（忘记密码）
Route::get('show_score','UsersController@yh_show_score')->middleware('jwt.role:user', 'jwt.auth');//用户分数显示
/*用户(yjj)*/
Route::post('select_lab','UsersController@select_lab');//填写实验室
Route::post('update_pwd','UsersController@update_pwd')->middleware('jwt.role:user', 'jwt.auth');//修改密码
Route::get('show_info','UsersController@show_info')->middleware('jwt.role:user', 'jwt.auth');//显示信息
Route::post('update_info','UsersController@update_info')->middleware('jwt.role:user', 'jwt.auth');//修改用户信息

/*测试(yh)*/
Route::post('test','TestController@yh_test')->middleware('jwt.role:user', 'jwt.auth');//答题

/*管理员(yh)*/
Route::post('admin_login','AdminsController@admin_login');//用户登录
/*管理员(yjj)*/
Route::post('show_p','AdminsController@show_p')->middleware('jwt.role:admin', 'jwt.auth');//根据时间段和实验室，分数段，查询人数
Route::post('show_all','AdminsController@show_all')->middleware('jwt.role:admin', 'jwt.auth');//根据时间和实验室查询详细信息


Route::get('excel','AdminsController@LX_excel');//导出
