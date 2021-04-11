<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citeducation extends Model
{
    use HasFactory;

    public function citizen(): BelongsTo {
        return $this->belongsTo(Citizen::class);
    }

    public function education(): BelongsTo {
        return $this->belongsTo(Education::class);
    }
}
