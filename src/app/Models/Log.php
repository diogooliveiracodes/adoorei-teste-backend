<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
        'client_ip',
        'user_id',
        'created_at',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    use HasFactory;



    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
