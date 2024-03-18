<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'host_id',
        'version',
        'number_of_contacts',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'number_of_contacts' => 'integer',
    ];

    /**
     * Get the account record associated with the call.
     *
     * @return BelongsTo
     */
    public function host()
    {
        return $this->belongsTo(Host::class);
    }
}
