<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\IpAllocation;
use App\Models\WgClient;
use App\Services\WireguardProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WgClientController extends Controller
{
    public function store(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'label' => 'required|string|max:80',
            'owner_name' => 'nullable|string|max:120',
            'owner_id' => 'nullable|string|max:50',
        ]);

        $batch->clients()->create($data);

        return back()->with('ok', 'Client ditambahkan.');
    }

    // Dummy: nanti kita isi provisioning beneran (push peer ke MikroTik + generate conf)
    public function provision(WgClient $client, WireguardProvisioner $prov)
    {
        try {
            \DB::transaction(fn() => $prov->provision($client));
            return back()->with('ok', 'Provision OK: peer dibuat + .conf siap.');
        } catch (\Throwable $e) {
            return back()->with('err', 'Provision gagal: '.$e->getMessage());
        }
    }

    public function download(WgClient $client)
    {
        if (!$client->conf_path || !Storage::disk('local')->exists($client->conf_path)) {
            return back()->with('err', 'File .conf belum ada.');
        }

        $filename = basename($client->conf_path);
        return Storage::disk('local')->download($client->conf_path, $filename);
    }

    public function revoke(WgClient $client, WireguardProvisioner $prov)
    {
        try {
            \DB::transaction(fn() => $prov->revoke($client));
            return back()->with('ok', 'Revoke OK: peer dinonaktifkan.');
        } catch (\Throwable $e) {
            return back()->with('err', 'Revoke gagal: '.$e->getMessage());
        }
    }

    public function destroy(WgClient $client)
    {
        if (!in_array($client->status, ['pending', 'revoked'], true)) {
            return back()->with('err', 'Client hanya bisa dihapus jika statusnya pending atau revoked.');
        }

        // release IP kalau masih tersisa
        if ($client->vpn_ip) {
            $ip = str_replace('/32', '', $client->vpn_ip);
            IpAllocation::where('ip', $ip)->update(['is_used' => false, 'wg_client_id' => null]);
        }
        
        $client->delete();

        return back()->with('ok', 'Client pending berhasil dihapus.');
    }

}
