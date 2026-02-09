<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\IPInfoService;
use App\Services\QrCodeService;
use Jenssegers\Agent\Facades\Agent;

class LinkController extends Controller
{

    public function __construct(
        public readonly IPInfoService $ipInfoService,
        public readonly QrCodeService $qrCodeService,
    )
    {

    }

    public function click(string $slug)
    {
        $link = Link::where('slug', $slug)->first();

        if (!$link) {
            abort(404, 'Link not found');
        }

        // Log the click
        $ip_address = request()->ip();
        $user_agent = request()->userAgent();
        $browser = Agent::browser();
        $platform = Agent::platform();
        $device = Agent::deviceType();
        $country = $this->ipInfoService->country;
        $countryCode = $this->ipInfoService->countryCode;
        $referrer = request()->header('Referer', 'Direct');
        $referrer_type = $referrer === 'Direct' ? 'Direct' : 'Referral';

        $link->clicks()->create([
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'country' => $country,
            'country_code' => $countryCode,
            'referrer' => $referrer,
            'referrer_type' => $referrer_type,
        ]);

        return response()->redirectTo($link->url);
    }

    public function create_qrcode(string $slug)
    {
        $url = Link::where('slug', $slug)->value('url');

        if (!$url) {
            abort(404, 'Link not found');
        }

        $qrCode = $this->qrCodeService->generate($url);
        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
