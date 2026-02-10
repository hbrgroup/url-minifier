<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Models\CampaignLink;
use App\Models\Link;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Str;

class CampaignObserver implements ShouldHandleEventsAfterCommit
{

    /**
     * Handle the Campaign "created" event.
     */
    public function created(Campaign $campaign): void
    {
        foreach ($campaign->channels_id as $channelId) {
            $created = false;

            do {
                $rnd = Str::random(6);
                if (!Link::where('slug', $rnd)->exists()) {
                    $created = true;
                }
            } while ($created === false);

            $link = Link::create([
                'slug' => $rnd,
                'url' => $campaign->url,
                'title' => $campaign->name,
                'qr_code' => true,
            ]);

            if ($link) {
                CampaignLink::create([
                    'campaign_id' => $campaign->id,
                    'link_id' => $link->id,
                    'channel_id' => $channelId,
                ]);
            }
        }
    }

    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        foreach ($campaign->channels_id as $channelId) {
            $channelExistsInCampaign = CampaignLink::query()
                ->where('campaign_id', $campaign->id)
                ->where('channel_id', $channelId)
                ->exists();

            if (!$channelExistsInCampaign) {
                $created = false;

                do {
                    $rnd = Str::random(6);
                    if (!Link::where('slug', $rnd)->exists()) {
                        $created = true;
                    }
                } while ($created === false);

                $link = Link::create([
                    'slug' => $rnd,
                    'url' => $campaign->url,
                    'title' => $campaign->name,
                    'qr_code' => true,
                ]);

                if ($link) {
                    CampaignLink::create([
                        'campaign_id' => $campaign->id,
                        'link_id' => $link->id,
                        'channel_id' => $channelId,
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Campaign "deleted" event.
     */
    public function deleted(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "restored" event.
     */
    public function restored(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "force deleted" event.
     */
    public function forceDeleted(Campaign $campaign): void
    {
        //
    }
}
