<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ip extends Model
{
    use HasFactory;

    public function logips(): HasMany {
        return $this->hasMany(Logip::class);
    }
}
