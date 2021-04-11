<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requestmutate extends Model
{
    use HasFactory;

    public function officer():BelongsTo {
        return $this->belongsTo(Officer::class);
    }
}
