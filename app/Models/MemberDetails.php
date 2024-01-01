<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDetails extends Model
{
    use HasFactory;
    public $table = "member_details";
    protected $primaryKey = 'member_details_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'member_details_id',
        'user_id',
        'member_name',
        'member_image',
        'member_phone_number',
        'member_position',
        'member_details_status'
           
    ];
    public function User() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
