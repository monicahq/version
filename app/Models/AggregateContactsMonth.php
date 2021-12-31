<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AggregateContactsMonth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'date',
        'count',
        'number_of_contacts',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];
}
