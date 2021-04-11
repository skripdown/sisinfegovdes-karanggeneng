<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operatingsystem extends Model
{
    use HasFactory;

    public function logoperatingsystems(): HasMany {
        return $this->hasMany(Logoperatingsystem::class);
    }
}
