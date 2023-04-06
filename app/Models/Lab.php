<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lab extends Model
{
    protected $table = 'tb_lab';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public $timestamps = true;
    protected $guarded = [];


    public static function select_lab($labName){
        try {
            $data=DB::table('tb_lab')->select('lab')
                ->where('lab','like','%'.$labName.'%')
                ->get();

            return $data;
        } catch (\Exception $e) {
            logError('查询失败', [$e->getMessage()]);
            return false;
        }
    }
}
