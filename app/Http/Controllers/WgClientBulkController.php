<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\WgClient;
use App\Services\WireguardProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WgClientBulkController extends Controller
{
    public function provision(Request $request, Batch $batch, WireguardProvisioner $prov)
    {
        $payload = $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'integer',
        ]);

        $clients = WgClient::where('batch_id', $batch->id)
            ->whereIn('id', $payload['client_ids'])
            ->get();

        $ok = 0; $skip = 0; $fail = 0;
        $log = [];

        foreach ($clients as $c) {
            if (!in_array($c->status, ['pending', 'revoked'], true)) {
                $skip++; $log[] = "SKIP #{$c->id} {$c->label} (status={$c->status})";
                continue;
            }

            try {
                DB::transaction(fn() => $prov->provision($c));
                $ok++; $log[] = "OK #{$c->id} {$c->label}";
            } catch (\Throwable $e) {
                $fail++; $log[] = "FAIL #{$c->id} {$c->label}: ".$e->getMessage();
            }
        }

        return back()->with('ok', "Bulk Provision selesai. OK={$ok}, SKIP={$skip}, FAIL={$fail}")
                     ->with('bulk_log', $log);
    }

    public function revoke(Request $request, Batch $batch, WireguardProvisioner $prov)
    {
        $payload = $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'integer',
        ]);

        $clients = WgClient::where('batch_id', $batch->id)
            ->whereIn('id', $payload['client_ids'])
            ->get();

        $ok = 0; $skip = 0; $fail = 0;
        $log = [];

        foreach ($clients as $c) {
            if ($c->status !== 'active') {
                $skip++; $log[] = "SKIP #{$c->id} {$c->label} (status={$c->status})";
                continue;
            }

            try {
                DB::transaction(fn() => $prov->revoke($c));
                $ok++; $log[] = "OK #{$c->id} {$c->label}";
            } catch (\Throwable $e) {
                $fail++; $log[] = "FAIL #{$c->id} {$c->label}: ".$e->getMessage();
            }
        }

        return back()->with('ok', "Bulk Revoke selesai. OK={$ok}, SKIP={$skip}, FAIL={$fail}")
                     ->with('bulk_log', $log);
    }
}
