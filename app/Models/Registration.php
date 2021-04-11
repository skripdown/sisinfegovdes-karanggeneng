<?php

namespace App\Models;

use App\Http\back\_Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($registration) {
            if ($registration->verified) {
                $verification = $registration->verification;
                $verification->delete();
            }
            _Image::remove($registration->id_pic, 'citizen_card');
        });
    }

    public function verification(): HasOne {
        return $this->hasOne(Verification::class);
    }
}
