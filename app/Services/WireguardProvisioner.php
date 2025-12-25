<?php

namespace App\Services;

use App\Models\IpAllocation;
use App\Models\WgClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class WireguardProvisioner
{
    private function keypair(): array
    {
        if (!extension_loaded('sodium')) {
            throw new \RuntimeException('Ext sodium tidak tersedia. Aktifkan extension sodium.');
        }

        $kp = sodium_crypto_box_keypair();
        $private = sodium_crypto_box_secretkey($kp);
        $public  = sodium_crypto_box_publickey($kp);

        return [base64_encode($private), base64_encode($public)];
    }

    public function provision(WgClient $client): void
    {
        if (!in_array($client->status, ['pending', 'revoked'], true)) {
            throw new \RuntimeException('Client tidak bisa diprovision karena status: '.$client->status);
        }

        // Jika reprovision dari revoked: bersihkan data lama di DB (agar clean)
        if ($client->status === 'revoked') {
            $client->vpn_ip = null;
            $client->peer_id = null;
            $client->public_key = null;
            $client->conf_path = null;
            $client->private_key_enc = null;
            $client->save();
        }

        // Allocate IP (gunakan tabel ip_allocations)
        $alloc = IpAllocation::where('is_used', false)->lockForUpdate()->first();
        if (!$alloc) throw new \RuntimeException('IP pool habis.');

        $alloc->is_used = true;
        $alloc->save();
        $clientIp = $alloc->ip . '/32';

        // Keypair
        [$priv, $pub] = $this->keypair();

        // Add peer MikroTik
        $mt = app(MikrotikWireguard::class);
        // Jika peer sudah ada (misal addPeer sempat sukses tapi DB gagal), ambil .id existing

        $peerId = $mt->addPeer(
            env('WG_INTERFACE_NAME'),
            $pub,
            $clientIp,
            $client->label
        );

        // Build conf
        $conf = "[Interface]\n";
        $conf .= "PrivateKey = {$priv}\n";
        $conf .= "Address = {$clientIp}\n";
        if (env('WG_DNS')) $conf .= "DNS = ".env('WG_DNS')."\n";
        $conf .= "\n[Peer]\n";
        $conf .= "PublicKey = ".env('WG_SERVER_PUBLIC_KEY')."\n";
        $conf .= "Endpoint = ".env('WG_ENDPOINT')."\n";
        $conf .= "AllowedIPs = ".env('WG_ALLOWED_IPS')."\n";
        $conf .= "PersistentKeepalive = 25\n";

        $safe = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $client->label);
        $path = "wg-output/clients/{$client->id}-{$safe}.conf";
        Storage::disk('local')->put($path, $conf);

        // Save client
        $client->vpn_ip = $clientIp;
        $client->public_key = $pub;
        $client->peer_id = $peerId;
        $client->private_key_enc = Crypt::encryptString($priv);
        $client->conf_path = $path;
        $client->status = 'active';
        $client->save();

        // attach allocation
        $alloc->wg_client_id = $client->id;
        $alloc->save();
    }

    public function revoke(WgClient $client): void
    {
        if ($client->status !== 'active') {
            throw new \RuntimeException('Client bukan status active.');
        }
        if (!$client->peer_id) {
            throw new \RuntimeException('peer_id kosong.');
        }

        // Disable peer di MikroTik
        $mt = app(MikrotikWireguard::class);
        // $mt->disablePeer($client->peer_id);
        $mt->removePeer($client->peer_id);

        // release IP
        $ip = $client->vpn_ip ? str_replace('/32', '', $client->vpn_ip) : null;
        if ($ip) {
            IpAllocation::where('ip', $ip)->update(['is_used' => false, 'wg_client_id' => null]);
        }

        $client->status = 'revoked';
        $client->vpn_ip = null;
        $client->peer_id = null;
        $client->public_key = null;
        $client->conf_path = null;
        $client->private_key_enc = null;

        $client->save();
    }
}
