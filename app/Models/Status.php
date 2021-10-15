<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function user()
    {
        // 微博关系 - 多个微博对应一个用户
        // 用户和微博关系 1 对多
        return $this->belongsTo(User::class);
    }
}
