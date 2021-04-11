<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Education extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($education) {
            $default   = Education::all()->firstWhere('name', 'Tidak Ada');
            $relations = $education->citeducations()->get();
            foreach ($relations as $relation) {
                $citizen = $relation->citizen;
                $new     = new Citeducation();
                $relation->citizen()->detach($citizen->id);
                $new->citizen()->associate($citizen);
                $new->education()->associate($default);
                $new->save();
                $relation->delete();
            }
        });
    }

    public function citeducations(): HasMany {
        return $this->hasMany(Citeducation::class);
    }
}
