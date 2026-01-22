<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Volunteer extends Model
{
    protected $fillable = ['first_name', 'last_name', 'tax_code', 'base'];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
            ->withTimestamps()
        ->withPivot(['role', 'hours_on_activity', 'art39']);
    }
    public function base()
{
    return $this->belongsTo(\App\Models\Base::class);
}
}
