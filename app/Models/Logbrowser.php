<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbrowser extends Model
{
    use HasFactory;

    public function log(): BelongsTo {
        return $this->belongsTo(Log::class);
    }

    public function browser(): BelongsTo {
        return $this->belongsTo(Browser::class);
    }
}
