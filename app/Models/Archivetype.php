<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Archivetype extends Model
{
    use HasFactory;

    private static $DIR = 'storage/archive/';

    public static function makeDir($code):string {
        $dir = 'archive-' . $code;
        Storage::disk('public')->makeDirectory($dir);
        return $dir;
    }

    public static function remDir($id):bool {
        $dir = 'archive-' . $id;
        Storage::disk('public')->deleteDirectory($dir);
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

    public function archives(): HasMany {
        return $this->hasMany(Archive::class);
    }

    public function officer(): BelongsTo {
        return $this->belongsTo(Officer::class);
    }
}
