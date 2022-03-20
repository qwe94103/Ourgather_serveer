<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notice extends Model
{
    protected $table = 'notice';

    protected $fillable = [
        'user_id','desc','action','url','to_user_id','type'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}


