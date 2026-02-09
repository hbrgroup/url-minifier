<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IPInfoService
{

    public ?string $country = null;

    public ?string $countryCode = null;

    public function __construct(Request $request)
    {
        $token = config('ipinfo.token');
        $ip = $request->ip();

        if (!$token || $ip === '127.0.0.1') {
            return null;
        }

        $response = Http::get("https://api.ipinfo.io/lite/{$ip}?token={$token}");

        if ($response->successful()) {
            $data = $response->json();
            $this->country = $data['country'] ?? null;
            $this->countryCode = $data['country_code'] ?? null;
        }
    }
}
