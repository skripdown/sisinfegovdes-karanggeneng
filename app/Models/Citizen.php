<?php
/** @noinspection SpellCheckingInspection */

namespace App\Models;

use App\Http\back\_Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Citizen extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($citizen) {
            $religion   = $citizen->citreligion;
            $education  = $citizen->citeducation;
            $occupation = $citizen->citoccupation;
            $religion->delete();
            $education->delete();
            $occupation->delete();
            _Image::remove($citizen->pic, 'citizen_profile');
        });
    }

    public function citeducation(): HasOne {
        return $this->hasOne(Citeducation::class);
    }

    public function citreligion(): HasOne {
        return $this->hasOne(Citreligion::class);
    }

    public function citoccupation(): HasOne {
        return $this->hasOne(Citoccupation::class);
    }

    public function district(): BelongsTo {
        return $this->belongsTo(District::class);
    }

    public function hamlet(): BelongsTo {
        return $this->belongsTo(Hamlet::class);
    }

    public function neighboor(): BelongsTo {
        return $this->belongsTo(Neighboor::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function family(): BelongsTo {
        return $this->belongsTo(Family::class);
    }
}
