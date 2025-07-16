<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'original_name',
        'storage_path',
        'mime_type',
        'size',
        'password',
        'max_downloads',
        'downloads',
        'expires_at',
        'user_id'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->uuid = Str::uuid();
        });
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }

    public function isExpired()
    {
        if ($this->expires_at && $this->expires_at < now()) {
            return true;
        }

        if ($this->max_downloads && $this->downloads >= $this->max_downloads) {
            return true;
        }

        return false;
    }

    public function hasPassword()
    {
        return !empty($this->password);
    }

    public function formatSize()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public static function formatStorageSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
