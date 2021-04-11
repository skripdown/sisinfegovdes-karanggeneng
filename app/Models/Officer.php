<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Officer extends Model
{
    use HasFactory;

    public static $rank = [
        'pembina utama'=>['set'=>'iv','room'=>'e'],
        'pembina utama madya'=>['set'=>'iv','room'=>'d'],
        'pembina utama muda'=>['set'=>'iv','room'=>'c'],
        'pembina tingkat i'=>['set'=>'iv','room'=>'b'],
        'pembina'=>['set'=>'iv','room'=>'a'],
        'penata tingkat i'=>['set'=>'iii','room'=>'d'],
        'penata'=>['set'=>'iii','room'=>'c'],
        'penata muda tingkat i'=>['set'=>'iii','room'=>'b'],
        'penata muda'=>['set'=>'iii','room'=>'a'],
        'pengatur tingkat i'=>['set'=>'ii','room'=>'d'],
        'pengatur'=>['set'=>'ii','room'=>'c'],
        'pengatur muda tingkat i'=>['set'=>'ii','room'=>'b'],
        'pengatur muda'=>['set'=>'ii','room'=>'a'],
        'juru tingkat i'=>['set'=>'i','room'=>'d'],
        'juru'=>['set'=>'i','room'=>'c'],
        'juru muda tingkat i'=>['set'=>'i','room'=>'b'],
        'juru muda'=>['set'=>'i','room'=>'a'],
    ];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($officer) {
            $requestmutates = $officer->requestmutates()->get();
            foreach ($requestmutates as $requestmutate) {
                $requestmutate->delete();
            }
            $approvals     = $officer->approvals()->get();
            foreach ($approvals as $approval) {
                $approval->delete();
            }
        });
    }

    public function requestmutates(): HasMany {
        return $this->hasMany(Requestmutate::class);
    }

    public function approvals(): HasMany {
        return $this->hasMany(Approval::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
