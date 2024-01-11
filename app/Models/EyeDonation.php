<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeDonation extends Model
{
    use HasFactory;
    public $table = "eye_donations";
    protected $primaryKey = 'eyedonation_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // eyedonation_id	eyedonation_title	eyedonation_type	eyedonation_purpose	eyedonation_achievement	eyedonation_started_in	eyedonation_size	service_needs_id	specific_needs_id	eyedonation_number	eyedonation_email	eyedonation_address	eyedonation_lat	eyedonation_long	state_id	city_id	eyedonation_history	eyedonation_image	eyedonation_status
    // 0: enable, 1:disable	created_at	updated_at	

    protected $fillable = [
        'eyedonation_id',
        'eyedonation_title',
        'eyedonation_type',
        'eyedonation_purpose',
        'eyedonation_achievement',
        'eyedonation_started_in',
        'eyedonation_size',
        'service_needs_id',
        'specific_needs_id',
        'eyedonation_number',
        'eyedonation_email',
        'eyedonation_address',
        'eyedonation_lat',
        'eyedonation_long', 
        'state_id',
        'city_id',
        'eyedonation_history',
        'eyedonation_image',
        'eyedonation_status',
    ];
    
    public function state()
    {
        return $this->belongsTo(App\Model\State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(App\Model\City::class, 'city_id');
    }

    public function serviceneeds()
    {
        return $this->belongsTo(App\Model\ServiceNeeds::class, 'service_needs_id');
    }

    public function specificneeds()
    {
        return $this->belongsTo(App\Model\SpecificNeeds::class, 'specific_needs_id');
    }
   
}
