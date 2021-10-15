<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * 允许可写字段,未指定都不可写，都被保护
     * @var string[]
     */
    protected $fillable = ['content'];

    public function user()
    {
        // 微博关系 - 多个微博对应一个用户
        // 用户和微博关系 1 对多
        return $this->belongsTo(User::class);
    }
}
