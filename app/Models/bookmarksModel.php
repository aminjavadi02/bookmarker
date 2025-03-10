<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;


class bookmarksModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'url', 'title', 'description'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'bookmarks';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
