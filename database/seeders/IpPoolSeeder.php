<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IpAllocation;

class IpPoolSeeder extends Seeder
{
    public function run(): void
    {
        $subnet = env('WG_VPN_SUBNET', '10.100.180.0/24');
        $base = explode('/', $subnet)[0];       // 10.100.180.0
        $parts = explode('.', $base);           // [10,100,180,0]
        $prefix = "{$parts[0]}.{$parts[1]}.{$parts[2]}.";

        $start = (int) env('WG_IP_START', 10);
        $end   = (int) env('WG_IP_END', 250);

        for ($i = $start; $i <= $end; $i++) {
            IpAllocation::firstOrCreate(
                ['ip' => $prefix.$i],
                ['is_used' => false]
            );
        }
    }
}
