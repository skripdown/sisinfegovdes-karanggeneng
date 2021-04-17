<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Approval extends Model
{
    use HasFactory;

    public static function hasApproval($officer_id):bool {
        return Approval::all()->where('officer_id', $officer_id)->count() != 0;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($approval) {
            $modifies = $approval->modifies()->get();
            foreach ($modifies as $modify) {
                $modify->delete();
            }
        });
    }

    public function modifies(): HasMany {
        return $this->hasMany(Modify::class);
    }

    public function officer(): BelongsTo {
        return $this->belongsTo(Officer::class);
    }
}
