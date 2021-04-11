<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Browser extends Model
{
    use HasFactory;

    public function logbrowsers(): HasMany {
        return $this->hasMany(Logbrowser::class);
    }
}
