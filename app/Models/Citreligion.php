<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citreligion extends Model
{
    use HasFactory;

    public function citizen(): BelongsTo {
        return $this->belongsTo(Citizen::class);
    }

    public function religion(): BelongsTo {
        return $this->belongsTo(Religion::class);
    }
}
