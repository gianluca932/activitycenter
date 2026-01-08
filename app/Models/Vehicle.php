<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    protected $fillable = ['plate', 'brand', 'model', 'revision_expires_at', 'insurance_expires_at'];

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
