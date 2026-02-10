<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\IPInfoService;
use App\Services\QrCodeService;
use Carbon\Carbon;
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

        // Check if part of campaign
        if ($link->campaignLinks()->exists()) {
            $campaignLink = $link->campaignLinks()->first();

            if ($campaignLink->campaign->start_date && Carbon::today()->lt($campaignLink->campaign->start_date)) {
                abort(403, 'A campanha ainda não começou, este link ficará activo no dia ' . $campaignLink->campaign->start_date->format('d/m/Y'));
            }

            if ($campaignLink->campaign->end_date && Carbon::today()->gt($campaignLink->campaign->end_date)) {
                abort(403, 'A campanha já terminou, este link não está mais ativo');
            }
        }

        // Check if came from QR code
        $qr_code  = request()->query('utm_source') === 'qr_code'
            && request()->query('utm_medium') === 'qr_code'
            && request()->query('utm_campaign') === $slug;

        if ($qr_code) {
            $referrer = 'QR Code';
            $referrer_type = 'QR Code';
        } else {
            $referrer = request()->header('Referer', 'Direct');
            $referrer_type = $referrer === 'Direct' ? 'Direct' : 'Referral';
        }

        // Log the click
        $ip_address = request()->ip();
        $user_agent = request()->userAgent();
        $browser = Agent::browser();
        $platform = Agent::platform();
        $device = Agent::deviceType();
        $country = $this->ipInfoService->country;
        $countryCode = $this->ipInfoService->countryCode;

        $link->clicks()->create([
            'ip_address' => $ip_address,
            'qr_code' => $qr_code,
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
        if (!Link::where('slug', $slug)->exists()) {
            abort(404, 'Link not found');
        }

        // Generate Link URL with UTM parameters for QR code tracking
        $url = route('links.click', ['slug' => $slug]);
        $qrCode = $this->qrCodeService->generate($url . '?utm_source=qr_code&utm_medium=qr_code&utm_campaign=' . $slug);
        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
