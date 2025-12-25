<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpAllocation extends Model
{
    protected $fillable = [
        'ip',
        'is_used',
        'wg_client_id',
    ];

    protected $casts = [
        'is_used' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(WgClient::class, 'wg_client_id');
    }
}
