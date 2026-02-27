<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $table = 'currency';
    protected $primaryKey = 'Currcode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
