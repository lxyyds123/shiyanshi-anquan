<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /*用户注册*/
    public function yh_register(Request $request)
    {
        $input = $request->all();

        $rule = [
            'email' => 'required|email',
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return json_success('邮箱输入要规范', null, 200);
        }

        $num = User::checkUser($request);
        $num1 = User::checkEmail($request);
        if ($num == 0 && $num1 == 0) {
            $a = self::userHandle($request);
            $user_id = User::addUser(self::userHandle($request));
            return $user_id ?
                json_success('注册成功!', $user_id, 200) :
                json_fail('注册失败!', null, 100);
        } else if ($num != 0) {
            return
                json_success('注册失败!该用户已经注册过了！', null, 100);
        } else if ($num1 != 0) {
            return
                json_success('注册失败!该邮箱已经注册过了！', null, 100);
        }
    }

    //发送邮箱验证码
    public function yh_email(Request $request)
    {
        $email = $request['email'];
        $randStr = str_shuffle('1234567890');
        $rand = substr($randStr, 0, 6);
        Mail::raw($rand, function (Message $message) {
            // 邮件接收者
            $message->to(self::getEmail(\request()));
            // 邮件主题
            $message->subject('实验室安全管理：邮箱验证码');
        });
        if (empty(Mail::failures())) {
            $datetime = Carbon::now()->toDateTimeString();
            $rand1 = ((int)$rand + 5) * 9;
            $jia = base64_encode("$rand1+$email+$datetime");
            return json_success('发送成功', $jia, 200);
        } else {
            return json_fail('发送失败', null, 100);
        }
    }

    //用户登录
    public function yh_login(Request $request)
    {
        $user_id = $request['user_id'];
        $password = $request['password'];
        $array = array('user_id' => $user_id, 'password' => $password);
        //生成token
        $token = auth('api')->attempt($array);
        return $token ?
            json_success('登录成功!', $token, 200) :
            json_fail('登录失败!账号或密码错误', null, 100);
    }

    //忘记密码
    public function yh_repassword(Request $request)
    {
        $num = User::checkMessage($request);
        if ($num == 0) {
            return json_success('填写信息不匹配!', $num, 200);
        } else {
            return self::yh_email($request);
        }
    }

    //修改密码
    public function yh_lost_pwd(Request $request){
        $user_id = $request['user_id'];
        $repassword = bcrypt($request['password']);
        $data = User::repassword($user_id, $repassword);
        return $data ?
            json_success("操作成功!", $data, 200) :
            json_fail("操作失败!", null, 100);
    }

    /*用户分数显示*/
    public function yh_show_score(Request $request)
    {
        $user_id = auth('api')->user()->user_id;
        $username = User::getName($user_id);
        if ($username) {
            $score = User::getScore($user_id);
            $array = array($username, $score);
            return json_success('操作成功!!', $array, 200);
        } else {
            return json_fail('操作失败!', null, 100);
        }
    }

    public function getEmail(Request $request)
    {
        $input = $request->all();
        $email = $input['email'];
        return $email;
    }


    protected function userHandle($request)   //对密码进行哈希256加密
    {
        $registeredInfo = $request->all();
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        return $registeredInfo;
    }

    /*
     * 查实验室*/
    public function select_lab(Request $request)
    {
        $labName = $request['lab'];
        $Msg = Lab::select_lab($labName);
        return $Msg ?
            json_success("操作成功!", $Msg, 200) :
            json_fail("操作失败!", null, 100);
    }

    /*
     * 修改密码*/
    public function update_pwd(Request $request)
    {
        $user_id = auth('api')->user()->user_id;
        $password = $request['password'];
        $repassword = bcrypt($request['new_password']);
        //判断原密码是否输入正确
        $res = User::checkPwd($user_id,$password);
        if ($res){
            $data = User::repassword($user_id, $repassword);
            return $data ?
                json_success("操作成功!", $data, 200) :
                json_fail("操作失败!", null, 100);
        }else{
            return json_success("修改失败，原密码输入错误!", null, 200);
        }

    }

    /*
     * 用户信息显示*/
    public function show_info(Request $request)
    {
        $user_id = auth('api')->user()->user_id;
        $Info = User::show_info($user_id);
        return $Info ?
            json_success("操作成功!", $Info, 200) :
            json_fail("操作失败!", null, 100);
    }

    /**
     * 修改用户信息
     */
    public function update_info(Request $request)
    {
        $user_id1 = auth('api')->user()->user_id;
        $user_name = $request['username'];
        $lab = $request['lab'];
        $email = $request['email'];
        $num1 = User::checkEmail($request);
        $num2 = DB::table('tb_user')->where('user_id', $user_id1)->value('email');
        if ($num1 == 0 || ($num1 == 1 && $email == $num2)) {
            $update = User::revise_info($user_name, $lab, $email, $user_id1);//修改用户信息
            return $update ?
                json_success("操作成功!", $update, 200) :
                json_fail("操作失败!", null, 100);
        } else if ($num1 != 0) {
            return
                json_success('修改失败!该邮箱已存在！', null, 100);
        }
    }
}
