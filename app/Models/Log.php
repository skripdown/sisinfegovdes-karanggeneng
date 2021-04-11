<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Log extends Model
{
    use HasFactory;

    public function logoperatingsystem(): HasOne {
        return $this->hasOne(Logoperatingsystem::class);
    }

    public function logbrowser(): HasOne {
        return $this->hasOne(Logbrowser::class);
    }

    public function logdevice(): HasOne {
        return $this->hasOne(Logdevice::class);
    }

    public function logip(): HasOne {
        return $this->hasOne(Logip::class);
    }

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }
}
