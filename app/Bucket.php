<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    protected $fillable = ['name', 'region', 'key', 'secret', 'expirationTime'];
}
