<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['name', 'requester_unit', 'notes', 'status'];
    
    public function clients()
    {
        return $this->hasMany(WgClient::class);
    }

    public function refreshStatusFromClients(): void
    {
        $hasActive = $this->clients()->where('status', 'active')->exists();

        $newStatus = $hasActive ? 'generated' : 'draft';

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }

}
