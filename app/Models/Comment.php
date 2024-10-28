<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['product_id', 'user_id', 'comment_text'];

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id'); // Đảm bảo tên cột này chính xác
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

