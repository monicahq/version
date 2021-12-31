<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'host_id',
        'uuid',
        'version',
        'number_of_contacts',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'number_of_contacts' => 'integer',
    ];
}
