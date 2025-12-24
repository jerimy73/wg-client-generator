<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Query;

class MikrotikTestController extends Controller
{
    public function index()
    {
        try {
            $client = new Client([
                'host' => env('MT_HOST'),
                'user' => env('MT_USER'),
                'pass' => env('MT_PASS'),
                'port' => (int) env('MT_PORT', 8728),
                'timeout' => (int) env('MT_TIMEOUT', 5),
            ]);

            // Ambil identitas router
            $identity = $client->query(new Query('/system/identity/print'))->read();
            $resource = $client->query(new Query('/system/resource/print'))->read();

            // Aman: tampilkan ringkas saja
            $id = $identity[0]['name'] ?? '(unknown)';
            $r  = $resource[0] ?? [];

            $data = [
                'ok' => true,
                'identity' => $id,
                'routeros_version' => $r['version'] ?? null,
                'board_name' => $r['board-name'] ?? null,
                'cpu' => $r['cpu'] ?? null,
                'cpu_count' => $r['cpu-count'] ?? null,
                'uptime' => $r['uptime'] ?? null,
                'free_memory' => $r['free-memory'] ?? null,
                'total_memory' => $r['total-memory'] ?? null,
            ];

            return view('mt.test', compact('data'));

        } catch (\Throwable $e) {
            return view('mt.test', [
                'data' => [
                    'ok' => false,
                    'error' => $e->getMessage(),
                    'host' => env('MT_HOST'),
                    'port' => env('MT_PORT'),
                    'user' => env('MT_USER'),
                ]
            ]);
        }
    }
}
