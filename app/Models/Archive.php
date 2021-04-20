<?php
/** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Archive extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($archive) {
            Storage::delete($archive->path);
        });
    }

    public static function header($extension=''):array {
        if ($extension == 'doc') return ['Content-Type: application/msword'];
        if ($extension == 'docx') return ['Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if ($extension == 'xls') return ['Content-Type: application/vnd.ms-excel'];
        if ($extension == 'xlsx') return ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if ($extension == 'gif') return ['Content-Type: image/gif'];
        if ($extension == 'png') return ['Content-Type: image/png'];
        if ($extension == 'jpeg') return ['Content-Type: image/jpg'];
        if ($extension == 'jpg') return ['Content-Type: image/jpg'];
        return ['Content-Type: application/pdf'];
    }

    public function archivefile(): HasOne {
        return $this->hasOne(Archivefile::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function archivetype(): BelongsTo {
        return $this->belongsTo(Archivetype::class);
    }

    public function officer(): BelongsTo {
        return $this->belongsTo(Officer::class);
    }
}
