<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Base extends Model
{
    protected $fillable = ['name', 'code'];

    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
