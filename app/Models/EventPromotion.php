<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPromotion extends Model
{
    use HasFactory;
    public $table = "event_promotions";
    protected $primaryKey = 'event_promotions_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_promotions_id',
        'event_promotions_title',
        'event_promotions_type',
        'event_promotions_purpose',
        'event_promotions_number',
        'event_promotions_email',
        'event_promotions_address',
        'event_promotions_lat',
        'event_promotions_long',
        'state_id',
        'city_id',
        'event_promotions_history',
        'event_promotions_image',
        'event_promotions_status'         
    ];

    public function user()
    {
        return $this->belongsTo(App\Model\User::class, 'user_id');
    }

    public function state()
    {
        return $this->belongsTo(App\Model\State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(App\Model\City::class, 'city_id');
    }


}
