<?php /** @noinspection SpellCheckingInspection */

/** @noinspection PhpUnused */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class System extends Model
{
    use HasFactory;

    public function systemattrs(): HasMany {
        return $this->hasMany(Systemattr::class);
    }
}
