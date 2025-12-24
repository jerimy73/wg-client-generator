<?php

namespace App\Observers;

use App\Models\WgClient;

class WgClientObserver
{
    public function created(WgClient $wgClient): void
    {
        $wgClient->batch?->refreshStatusFromClients();
    }

    public function updated(WgClient $wgClient): void
    {
        // status berubah pending->active, active->revoked, dll
        if ($wgClient->wasChanged('status')) {
            $wgClient->batch?->refreshStatusFromClients();
        }
    }

    public function deleted(WgClient $wgClient): void
    {
        // setelah delete, batch mungkin kembali draft
        $wgClient->batch?->refreshStatusFromClients();
    }
}
