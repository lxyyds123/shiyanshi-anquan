<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class AdminsController extends Controller
{
    /*管理员登录*/
    public function admin_login(Request $request)
    {
        $user_id = $request['account'];
        $password = $request['password'];
        $array = array('account' => $user_id, 'password' => $password);

        $token = auth('admin')->attempt($array);   //获取token

        return $token ?
            json_success('登录成功!',$token,  200) :
            json_fail('登录失败!账号或密码错误',null, 100 ) ;
    }

    /**
     *根据时间段和实验室，分数段，查询人数
     */

    public function show_p(Request $request){
        $lab=$request['lab'];
        $datetime1=$request['datetime1'];
        $datetime2=$request['datetime2'];

        $data=User::judge($lab,$datetime1,$datetime2);
        return $data?
            json_success("操作成功!",$data,200):
            json_fail("操作失败!",null,100);
    }

    /**
     * 根据时间和实验室查询详细信息
     */
    public function show_all(Request $request){
        $lab=$request['lab'];
        $datetime1=$request['datetime1'];
        $datetime2=$request['datetime2'];

        $Info=User::selectAll($lab,$datetime1,$datetime2);
        return $Info?
            json_success("操作成功!",$Info,200):
            json_fail("操作失败!",null,100);
    }


    /**
     * 导出成绩
     */
    public function LX_excel(Request $request){
        $data1 = $request['data1'];
        $data2 = $request['data2'];
        $data = User::lx_excel($data1,$data2)->toArray();
        return (new FastExcel($data))->download( '组--优秀指导教师奖获奖名单.xlsx');


    }
}
