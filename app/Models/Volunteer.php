<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Volunteer extends Model
{
    protected $fillable = ['fullname', 'tax_code', 'base_id', 'luogo_di_nascita', 'numero_iscrizione_regionale', 'residenza', 'cellulare', 'email', 'patenti'];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
            ->withTimestamps()
        ->withPivot([ 'art39']);
    }
    public function base()
{
    return $this->belongsTo(\App\Models\Base::class);
}
}
