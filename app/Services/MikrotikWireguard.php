<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;

class MikrotikWireguard
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'host' => env('MT_HOST'),
            'user' => env('MT_USER'),
            'pass' => env('MT_PASS'),
            'port' => (int) env('MT_PORT', 8728),
            'timeout' => (int) env('MT_TIMEOUT', 5),
        ]);
    }

    public function findPeerByPublicKey(string $iface, string $publicKey): ?array
    {
        $q = (new Query('/interface/wireguard/peers/print'))
            ->where('interface', $iface)
            ->where('public-key', $publicKey);

        $res = $this->client->query($q)->read();
        return $res[0] ?? null;
    }

    public function addPeer(string $iface, string $publicKey, string $allowedAddress, string $comment): string
    {
        // 1) Idempotent: kalau peer dengan public-key sudah ada, gunakan itu
        $existing = $this->findPeerByPublicKey($iface, $publicKey);
        if ($existing && !empty($existing['.id'])) {
            // optional: update allowed-address/comment biar konsisten
            $this->setPeer($existing['.id'], [
                'allowed-address' => $allowedAddress,
                'comment' => $comment,
                'disabled' => 'no',
            ]);
            return $existing['.id'];
        }

        // 2) Add peer
        $q = (new Query('/interface/wireguard/peers/add'))
            ->equal('interface', $iface)
            ->equal('public-key', $publicKey)
            ->equal('allowed-address', $allowedAddress)
            ->equal('comment', $comment);

        $res = $this->client->query($q)->read();

        // Kadang ada ret=.id, kadang tidak
        $peerId = $res[0]['ret'] ?? null;
        if ($peerId) return $peerId;

        // 3) Fallback: cari lagi setelah add
        $found = $this->findPeerByPublicKey($iface, $publicKey);
        if ($found && !empty($found['.id'])) {
            return $found['.id'];
        }

        throw new \RuntimeException('Peer dibuat tetapi .id tidak bisa ditemukan via API.');
    }

    public function setPeer(string $peerId, array $fields): void
    {
        $q = new Query('/interface/wireguard/peers/set');
        $q->equal('.id', $peerId);
        foreach ($fields as $k => $v) {
            $q->equal($k, $v);
        }
        $this->client->query($q)->read();
    }

    public function disablePeer(string $peerId): void
    {
        $this->setPeer($peerId, ['disabled' => 'yes']);
    }

    public function removePeer(string $peerId): void
    {
        $q = (new Query('/interface/wireguard/peers/remove'))
            ->equal('.id', $peerId);

        $this->client->query($q)->read();
    }
}
