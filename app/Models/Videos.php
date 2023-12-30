<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    use HasFactory;
    public $table = "videos";
    protected $primaryKey = 'video_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'video_id',
        'user_id',
        'video_url',
        'video_type',
        'video_status'           
    ];

    public function user()
    {
        return $this->belongsTo(App\Model\User::class, 'user_id');
    }
    
}
