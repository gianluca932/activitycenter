<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    protected $fillable = [
        'category',
        'requested_by',
        'short_description',
        'date_from',
        'date_to',
        'hours',
        'activity_type_id',
'request_source_id',
    ];

    protected $casts = [
        'date_from' => 'datetime',
        'date_to' => 'datetime',
    ];

    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(Volunteer::class)
            ->withTimestamps()
        ->withPivot(['art39']);
    }

public function vehicles()
{
    return $this->belongsToMany(\App\Models\Vehicle::class)->withTimestamps();
}
    public function activityType()
{
    return $this->belongsTo(\App\Models\ActivityType::class);
}

public function requestSource()
{
    return $this->belongsTo(\App\Models\RequestSource::class);
}

}
