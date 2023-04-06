<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    //关联表
    protected $table = 'tb_user';
    //设置守卫
    protected $guarded = [];
    //允许查询的字段
    protected $fillable = ['username', 'password', 'user_id', 'lab', 'email', 'score'];
    //隐藏密码
    protected $hidden = ['password'];
    protected $remeberTokenName = NULL;




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'user'];
    }

    /*
     * 用户添加
     */
    public static function addUser($array = [])
    {
        try {
            //添加用户返回id
            $user_id = self::create([
                'username' => $array['username'],
                'password' => $array['password'],
                'user_id' => $array['user_id'],
                'lab' => $array['lab'],
                'email' => $array['email']
            ])->id;
            return $user_id ? $user_id : false;
        } catch (\Exception $exception) {
            logError('添加用户失败！！！', [$exception->getMessage()]);
            return false;
        }
    }

    /*
     * 查询用户是否存在
     * */
    public static function checkUser($request)
    {
        $user_id = $request['user_id'];
        try {
            $num = self::select('user_id')->where('user_id', $user_id)->count();
            return $num;
        } catch (\Exception $e) {
            logError("账号查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }
    /*
     * 判断邮箱是否存在*/
    public static function checkEmail($request)
    {
        $email= $request['email'];
        try {
            $num = self::select('email')->where('email', $email)->count();
            return $num;
        } catch (\Exception $e) {
            logError("邮箱查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }


    /*
     * 检查用户信息是否存在*/
    public static function checkMessage($request)
    {
        $user_id = $request['user_id'];
        $username = $request['username'];
        $email = $request['email'];
        try {
            $num = self::select('user_id')->where('user_id', $user_id)->where('username', $username)->where('email', $email)->count();
            return $num;
        } catch (\Exception $e) {
            logError("账号查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }

    /*获取分数*/
    public static function getScore($user_id)
    {
        try {
            $score = self::where('user_id', $user_id)->value('score');
            return $score;
        } catch (\Exception $e) {
            logError("分数查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }

    /*获取姓名*/
    public static function getName($user_id)
    {
        try {
            $username = self::where('user_id', $user_id)->value('username');
            return $username ?
                $username :
                false;
        } catch (\Exception $e) {
            logError("用户查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }

    /*将分数存入数据库*/
    public static function setScore($user_id, $score)
    {
        try {
            $res = self::where('user_id', $user_id)->update([
                'score' => $score
            ]);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError("分数查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 重置密码
     */
    public static function repassword($user_id, $repassword)
    {
        try {
            $Msg = DB::table('tb_user')
                ->where('user_id', $user_id)
                ->update([
                    'password' => $repassword
                ]);
            return $Msg;

        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 显示用户信息
     */

    public static function show_info($user_id)
    {
        try {
            $Info = DB::table('tb_user')
                ->select('username', 'user_id', 'lab', 'email')
                ->where('user_id', $user_id)
                ->get();
            return $Info;

        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 查看是否有人使用过此学号
     */

    public static function count($user_id)
    {
        try {
            $count = DB::table('tb_user')
                ->where('user_id', $user_id)
                ->count();

            return $count;

        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 修改用户信息
     */

    public static function revise_info($username, $lab, $email, $userid)
    {
        try {
            $Msg = DB::table('tb_user')
                ->where('user_id', $userid)
                ->update([
                    'username' => $username,
//                    'user_id' => $user_id,
                    'lab' => $lab,
                    'email' => $email
                ]);

            return $Msg;

        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }

    public static function judge($lab, $datetime1, $datetime2)
    {
        try {
            $array = array($datetime1, $datetime2);
            $grade_d = DB::table('tb_user')
                ->select('username')
                ->where('score', '<', 80)
                ->where('lab', 'like', '%' . $lab . '%')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->count();

            $grade_c = DB::table('tb_user')
                ->select('username')
                ->where('score', '>=', 80)
                ->where('score', '<', 90)
                ->where('lab', 'like', '%' . $lab . '%')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->count();
            $grade_b = DB::table('tb_user')
                ->select('username')
                ->where('score', '>=', 90)
                ->where('score', '<', 95)
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->count();

            $grade_a = DB::table('tb_user')
                ->select('username')
                ->where('score', '>=', 95)
                ->where('lab', 'like', '%' . $lab . '%')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->count();
            $array = array($grade_a, $grade_b, $grade_c, $grade_d);
            return $array;

        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }

    public static function selectAll($lab, $datetime1, $datetime2)
    {
        try {

            $grade_d = DB::table('tb_user')
                ->select('user_id', 'username', 'lab', 'score')
                ->where('lab', 'like', '%' . $lab . '%')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->get();

            $grade_c = DB::table('tb_user')
                ->select('user_id', 'username', 'lab', 'score')
                ->where('lab', 'like', '%' . $lab . '%')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->get();
            $grade_b = DB::table('tb_user')
                ->select('user_id', 'username', 'lab', 'score')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->get();

            $grade_a = DB::table('tb_user')
                ->select('user_id', 'username', 'lab', 'score')
                ->where('lab', 'like', '%' . $lab . '%')
                ->whereBetween('created_at', [$datetime1, $datetime2])
                ->get();
            $array = array($grade_a, $grade_b, $grade_c, $grade_d);
            return $array;

        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }

    /*判断密码是否输入正确*/
    public static function checkPwd($user_id,$password){
        try {
            $pwd = self::where('user_id', $user_id)->value('password');
            if (password_verify($password,$pwd)){
                return true;
            }else{
                return false;
            }
        } catch (\Exception $e) {
            logError("用户查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }

    public static function lx_excel($data1, $data2)
    {
        try {
            $data = self::select('user_id as 学号','username as 姓名','lab as 实验室名称','score as 成绩')
                ->whereBetween('created_at',[$data1,$data2])
                ->get();
            return $data;
        } catch (\Exception $e) {
            logError("用户查询失败！！！", [$e->getMessage()]);
            return false;
        }
    }
}
