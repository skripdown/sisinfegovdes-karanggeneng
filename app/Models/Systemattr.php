<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Systemattr extends Model
{
    use HasFactory;

    public function system(): BelongsTo {
        return $this->belongsTo(System::class);
    }
}
