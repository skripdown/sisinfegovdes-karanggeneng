<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($user) {
            $citizen = $user->citizen;
            if ($user->officer != null) {
                $officer    = $user->officer;
                $role       = $user->role;
                $activities = $user->activities()->get();
                $officer->delete();
                $role->delete();
                foreach ($activities as $activity) {
                    $activity->delete();
                }
            }
            $citizen->delete();
        });
    }

    public function citizen(): HasOne {
        return $this->hasOne(Citizen::class);
    }

    public function role(): HasOne {
        return $this->hasOne(Role::class);
    }

    public function officer(): HasOne {
        return $this->hasOne(Officer::class);
    }

    public function archives(): HasMany {
        return $this->hasMany(Archive::class);
    }

    public function reqarchives(): HasMany {
        return $this->hasMany(Reqarchive::class);
    }

    public function albums(): HasMany {
        return $this->hasMany(Album::class);
    }

    public function photos(): HasMany {
        return $this->hasMany(Photo::class);
    }

    public function blogs(): HasMany {
        return $this->hasMany(Blog::class);
    }

    public function activities(): HasMany {
        return $this->hasMany(Activity::class);
    }
}
