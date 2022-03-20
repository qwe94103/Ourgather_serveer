<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class friend extends Model
{
    protected $table = 'friend';

    protected $fillable = [
        'user_id','to_user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function touser(){
        return $this->belongsTo(User::class,'to_user_id', 'id');
    }
}
