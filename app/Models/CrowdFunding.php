<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrowdFunding extends Model
{
    use HasFactory;

    public $table = "crowd_fundings";
    protected $primaryKey = "crowdfundings_id";
    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        'crowdfundings_id',
        'crowdfundings_title',
        'crowdfundings_single_image',
        'crowdfundings_multi_image',
        'crowdfundings_type',
        'crowdfundings_purpose',
        'crowdfundings_issue',
        'crowdfundings_amount',
        'crowdfundings_patient1_name',
        'crowdfundings_patient1_age',
        'crowdfundings_patient1_image',
        'crowdfundings_patient2_name',
        'crowdfundings_patient2_age',
        'crowdfundings_patient2_image',
        'crowdfundings_medical_certificate',
        'crowdfundings_address',
        'crowdfundings_lat',
        'crowdfundings_long',
        'state_id',
        'city_id',
        'crowdfundings_account_number',
        'crowdfundings_account_holder_name',
        'crowdfundings_ifsc_code',
        'crowdfundings_upi_number',
        'crowdfundings_gpay_number',
        'crowdfundings_status'
    ];

    public function state(){
        return $this->belongsTo(App\Model\State::class, 'state_id');
    }

    public function city(){
        return $this->belongsTo(App\Model\City::class, 'city_id');
    }
}
