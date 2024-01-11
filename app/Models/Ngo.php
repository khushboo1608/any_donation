<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ngo extends Model
{
    use HasFactory;
    public $table = "ngos";
    protected $primaryKey = 'ngo_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ngo_id',
        'user_id',
        'account_number',
        'account_holder_name',
        'ifsc_code',
        'upi_number',
        'gpay_number',
        'address_proof',
        'jj_act',
        'form_10_b',
        'a12_certificate',
        'cancelled_blank_cheque',
        'pan_of_ngo',
        'registration_certificate', 
        'achievements',
        'history',
        'purpose',
        'started_in',
        'size',
        'specific_needs_id',
        'service_needs_id',
        'ngo_status'       
    ];

    public function User() {
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }
   

}
