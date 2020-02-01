<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    const TABLE = 'service_images';

    protected $table = self::TABLE;

    protected $fillable = [ 'path' , 'service_id' ];

    protected $hidden = [ 'is_deleted' , 'created_at' , 'updated_at' ];
}
