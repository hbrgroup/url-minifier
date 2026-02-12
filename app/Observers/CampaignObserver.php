<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Models\CampaignLink;
use App\Models\Channel;
use App\Models\Link;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class CampaignObserver implements ShouldHandleEventsAfterCommit
{

    /**
     * Handle the Campaign "created" event.
     */
    public function created(Campaign $campaign): void
    {
        foreach ($campaign->channels_id as $channelId) {
            $this->createLink($campaign, $channelId);
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
                $this->createLink($campaign, $channelId);
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

    private function createLink(Campaign $campaign, int $channelId): void
    {
        $slug = Link::createSlug();

        $link = Link::create([
            'slug'    => $slug,
            'url'     => $campaign->url,
            'title'   => $campaign->name,
            'qr_code' => true,
            'type_of_link' => 'campaign',
        ]);

        if ($link) {
            CampaignLink::create([
                'campaign_id' => $campaign->id,
                'link_id'     => $link->id,
                'channel_id'  => $channelId,
            ]);
        }
    }
}
