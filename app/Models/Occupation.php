<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occupation extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($occupation) {
            $default   = Occupation::all()->firstWhere('name', 'Tidak Ada');
            $relations = $occupation->citoccupations()->get();
            foreach ($relations as $relation) {
                $citizen = $relation->citizen;
                $new     = new Citoccupation();
                $relation->citizen()->detach($citizen->id);
                $new->citizen()->associate($citizen);
                $new->occupation()->associate($default);
                $new->save();
                $relation->delete();
            }
        });
    }

    public function citoccupations(): HasMany {
        return $this->hasMany(Citoccupation::class);
    }
}
