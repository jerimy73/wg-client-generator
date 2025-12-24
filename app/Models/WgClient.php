<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WgClient extends Model
{
    protected $fillable = ['batch_id','label','owner_name','owner_id','status'];
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function ipAllocation()
    {
        return $this->hasOne(IpAllocation::class);
    }


}
