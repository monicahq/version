<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AggregateContactsWeek extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'date',
        'count',
        'new',
        'stale',
        'number_of_contacts',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];
}
