<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function yh_Test(\App\Http\Requests\Test $request)
    {
        $user_id = auth('api')->user()->user_id;

        $a1 = $request['a1'];
        $a2 = $request['a2'];
        $a3 = $request['a3'];
        $a4 = $request['a4'];
        $a5 = $request['a5'];
        $a6 = $request['a6'];
        $a7 = $request['a7'];
        $a8 = $request['a8'];
        $a9 = $request['a9'];
        $a10 = $request['a10'];
        $a11 = $request['a11'];
        $a12 = $request['a12'];
        $a13 = $request['a13'];
        $a14 = $request['a14'];
        $a15 = $request['a15'];
        $a16 = $request['a16'];
        $a17 = $request['a17'];
        $a18 = $request['a18'];
        $a19 = $request['a19'];
        $a20 = $request['a20'];
        $b1 = $request['b1'];
        $b2 = $request['b2'];
        $b3 = $request['b3'];
        $b4 = $request['b4'];
        $b5 = $request['b5'];
        $b6 = $request['b6'];
        $b7 = $request['b7'];
        $b8 = $request['b8'];
        $b9 = $request['b9'];
        $b10 = $request['b10'];
        $b11 = $request['b11'];
        $b12 = $request['b12'];
        $b13 = $request['b13'];
        $b14 = $request['b14'];
        $c1 = $request['c1'];
        $c2 = $request['c2'];
        $c3 = $request['c3'];
        $c4 = $request['c4'];
        $c5 = $request['c5'];

        //判断题
        $panduan = array($a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10, $a11, $a12, $a13, $a14, $a15, $a16, $a17, $a18, $a19, $a20);
        $panduan_t = array('对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '对', '错', '对', '对', '对');
        $score = 0;
        for ($i = 0; $i < count($panduan); $i++) {
            if ($panduan[$i] == $panduan_t[$i]) {
                $score+=2.5;
            }
        }

        //单选题
        $danxuan = array($b1, $b2, $b3, $b4, $b5, $b6, $b7, $b8, $b9, $b10, $b11, $b12, $b13, $b14);
        $danxuan_t = array('A', 'A', 'B', 'C', 'B', 'C', 'A', 'A', 'A', 'D', 'C', 'B', 'B', 'C');
        for ($i = 0; $i < count($danxuan); $i++) {
            if ($danxuan[$i] == $danxuan_t[$i]) {
                $score+=2.5;
            }
        }

        //多选题
        $duoxuan = array($c1, $c2, $c3, $c4, $c5);
        $duoxuan_t = array('ABCD', 'ACD', 'BD', 'ABCD', 'ABCD');
        for ($i = 0; $i < count($duoxuan); $i++) {
            if ($duoxuan[$i] == $duoxuan_t[$i]) {
                $score+=2.5;
            }
        }

        //获取分数并判断最高分，将最高分存入数据库
        $score_db = User::getScore($user_id);
        if ($score > $score_db) {
            $res = User::setScore($user_id, $score);
            return $res ?
                json_success('提交成功!(分数已经修改)', $score, 200) :
                json_fail('提交失败', null, 100);
        } else {
            return json_success('提交成功!(分数未经修改)', $score, 200);
        }
    }
}
