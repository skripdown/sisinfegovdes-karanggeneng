<?php
/** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Neighboor extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($neighboor) {
            $families = $neighboor->families()->get();
            foreach ($families as $family) {
                $family->delete();
            }
        });
    }

    public function families(): HasMany {
        return $this->hasMany(Family::class);
    }

    public function citizens(): HasMany {
        return $this->hasMany(Citizen::class);
    }

    public function district(): BelongsTo {
        return $this->belongsTo(District::class);
    }

    public function hamlet(): BelongsTo {
        return $this->belongsTo(Hamlet::class);
    }
}
