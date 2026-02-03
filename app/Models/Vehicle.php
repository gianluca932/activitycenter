<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
protected $fillable = [
    'plate',
    'type',
    'model',
    'revision_expires_at',
    'insurance_expires_at',
    'base_id',
];
    protected $casts = [
        'revision_expires_at' => 'date',
        'insurance_expires_at' => 'date',
    ];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
            ->withTimestamps();
    }
    public function base()
{
    return $this->belongsTo(\App\Models\Base::class);
}
}
