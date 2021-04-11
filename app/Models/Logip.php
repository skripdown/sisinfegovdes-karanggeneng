<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logip extends Model
{
    use HasFactory;

    public function log(): BelongsTo {
        return $this->belongsTo(Log::class);
    }

    public function ip(): BelongsTo {
        return $this->belongsTo(Ip::class);
    }
}
