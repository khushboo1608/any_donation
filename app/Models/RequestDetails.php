<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetails extends Model
{
    // `request_details_id`, `user_id`, `request_blood_group`, `request_unit`, `request_details_status`,
    use HasFactory;
    public $table = "request_details";
    protected $primaryKey = 'request_details_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'request_details_id',
        'user_id',
        'request_blood_group',
        'request_unit',
        'request_details_status'           
    ];

    public function user()
    {
        return $this->belongsTo(App\Model\User::class, 'user_id');
    }

}
