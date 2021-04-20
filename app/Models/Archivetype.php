<?php
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Archivetype extends Model
{
    use HasFactory;

    private static $DIR = 'archive/';

    public static function makeDir($code):string {
        $dir = 'archive-' . $code;
        Storage::disk('public')->makeDirectory(self::$DIR . $dir);
        return $dir;
    }

    private static function remDir($id):bool {
        $dir = 'archive-' . $id;
        Storage::disk('public')->deleteDirectory(self::$DIR . $dir);
        return true;
    }

    public static function makeToken($length = 20):string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function makeCode($length = 6):string {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return env('ARCHIVE_PREFIX').$randomString;
    }

    public static function upload($code, $token, $file):string {
        $path     = 'public/' . self::$DIR . 'archive-' . $code;
        $filename = $token . '.' . $file->getClientOriginalExtension();
        $file->storeAs($path, $filename);

        return $path . '/' . $filename;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($archivetype) {
            self::remDir($archivetype->code);
        });
    }

    public function archives(): HasMany {
        return $this->hasMany(Archive::class);
    }

    public function officer(): BelongsTo {
        return $this->belongsTo(Officer::class);
    }
}
