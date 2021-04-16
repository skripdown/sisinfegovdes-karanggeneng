<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Archive extends Model
{
    use HasFactory;

    public function archivefile(): HasOne {
        return $this->hasOne(Archivefile::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function archivetype(): BelongsTo {
        return $this->belongsTo(Archivetype::class);
    }

    public function officer(): BelongsTo {
        return $this->belongsTo(Officer::class);
    }
}
