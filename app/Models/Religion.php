<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Religion extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($religion) {
            $default = Religion::all()->firstWhere('name', 'Tidak Ada');
            $relations = $religion->citreligions()->get();
            foreach ($relations as $relation) {
                $citizen = $relation->citizen;
                $new     = new Citreligion();
                $relation->citizen()->detach($citizen->id);
                $new->citizen()->associate($citizen);
                $new->religion()->associate($default);
                $new->save();
                $relation->delete();
            }
        });
    }

    public function citreligions(): HasMany {
        return $this->hasMany(Citreligion::class);
    }
}
