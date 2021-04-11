<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hamlet extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($hamlet) {
            $neighboors = $hamlet->neighboors()->get();
            foreach ($neighboors as $neighboor) {
                $neighboor->delete();
            }
        });
    }

    public function neighboors(): HasMany {
        return $this->hasMany(Neighboor::class);
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
}
