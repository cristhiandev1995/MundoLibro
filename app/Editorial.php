<?php

namespace App;

use App\Traits\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Editorial extends Model
{
    use SoftDeletes, Logs;

    protected $fillable = [
        'name',
        'image',
        'fk_created_by',
        'fk_updated_by'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
